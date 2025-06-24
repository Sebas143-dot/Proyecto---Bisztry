<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\VarianteProd;
use App\Models\EstadoPedido;
use App\Models\MetodoPago;
use App\Models\ServicioEntrega;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PedidoController extends Controller
{
    /**
     * Muestra una lista paginada de todos los pedidos.
     */
public function index(Request $request)
    {
        // Obtenemos todos los estados para el desplegable de filtro
        $estados = EstadoPedido::all();

        // Empezamos la consulta a la base de datos
        $query = Pedido::with('cliente', 'estado')->latest('pedi_fecha');

        // Si se envió un filtro de estado, lo aplicamos
        if ($request->filled('estado_filtro') && $request->estado_filtro != 'todos') {
            $query->where('esta_cod', $request->estado_filtro);
        }

        // Si se envió un término de búsqueda, lo aplicamos
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('pedi_id', 'like', "%{$search}%")
                  ->orWhereHas('cliente', function($clienteQuery) use ($search) {
                      $clienteQuery->where('clie_nombre', 'like', "%{$search}%")
                                   ->orWhere('clie_apellido', 'like', "%{$search}%");
                  });
            });
        }
        
        $pedidos = $query->paginate(15);

        // Devolvemos la vista con los pedidos y también los estados para el filtro
        return view('pedidos.index', compact('pedidos', 'estados'));
    }

    /**
     * Muestra el formulario para crear un nuevo pedido.
     * Reúne todos los datos necesarios para los desplegables del formulario.
     */
    public function create()
    {
        $clientes = Cliente::orderBy('clie_apellido')->get();
        $metodosPago = MetodoPago::all();
        $serviciosEntrega = ServicioEntrega::all();
        $estados = EstadoPedido::all();

        // -- INICIO DE LA MEJORA --
        // Obtenemos solo las variantes que tienen stock
        $variantesConStock = VarianteProd::with('producto', 'talla', 'color')
            ->where('var_stok_actual', '>', 0)
            ->get();
        
        // Pre-formateamos los datos para Select2 aquí en el backend, que es más seguro.
        $variantesParaSelect2 = $variantesConStock->map(function ($variante) {
            // Creamos el texto que se mostrará en el desplegable
            $texto = sprintf(
                '%s (Talla: %s, Color: %s) | Stock: %d | SKU: %s',
                $variante->producto->prod_nombre ?? 'Producto sin nombre',
                $variante->talla->tall_detalle ?? 'N/A',
                $variante->color->col_detalle ?? 'N/A',
                $variante->var_stok_actual,
                $variante->sku ?? 'N/A'
            );
            
            // Devolvemos un array con el formato que Select2 y nuestro JS necesitan
            return [
                'id' => $variante->var_id,
                'text' => $texto,
                'datos_completos' => [ // Incluimos los datos necesarios para el carrito
                    'var_id' => $variante->var_id,
                    'nombre' => sprintf('%s (%s, %s)', $variante->producto->prod_nombre, $variante->talla->tall_detalle, $variante->color->col_detalle),
                    'precio' => (float) $variante->var_precio,
                    'stock_max' => (int) $variante->var_stok_actual,
                ]
            ];
        });
        // -- FIN DE LA MEJORA --

        // Pasamos la nueva variable '$variantesParaSelect2' a la vista
        return view('pedidos.create', compact('clientes', 'variantesParaSelect2', 'metodosPago', 'serviciosEntrega', 'estados'));
    }

    /**
     * Valida y guarda un nuevo pedido en la base de datos usando una transacción.
     */
    public function store(Request $request)
    {
        // 1. Validación inicial de los datos principales del pedido
        $request->validate([
            'clie_id' => 'required|exists:clientes,clie_id',
            'pedi_fecha' => 'required|date',
            'esta_cod' => 'required|exists:estados_pedidos,esta_cod',
            'meto_cod' => 'required|exists:metodos_pago,meto_cod',
            'serv_id' => 'required|exists:servicios_entrega,serv_id',
            'pedi_costo_envio' => 'required|numeric|min:0',
            'carrito' => 'required|array|min:1',
            // Validación de cada item dentro del carrito
            'carrito.*.var_id' => 'required|exists:variantes_prod,var_id',
            'carrito.*.cantidad' => 'required|integer|min:1',
            'carrito.*.precio' => 'required|numeric|min:0',
        ]);

        // 2. Transacción de Base de Datos: La "Caja de Seguridad"
        try {
            DB::transaction(function () use ($request) {
                // Calculamos el total del pedido
                $totalPedido = 0;
                foreach ($request->carrito as $item) {
                    $totalPedido += $item['cantidad'] * $item['precio'];
                }

                // 3. Creación del Pedido principal
                $pedido = Pedido::create([
                    'clie_id' => $request->clie_id,
                    'pedi_fecha' => $request->pedi_fecha,
                    'esta_cod' => $request->esta_cod,
                    'meto_cod' => $request->meto_cod,
                    'serv_id' => $request->serv_id,
                    'pedi_direccion' => $request->pedi_direccion,
                    'pedi_costo_envio' => $request->pedi_costo_envio,
                    'pedi_total' => $totalPedido,
                ]);

                // 4. Creación de los Detalles del Pedido y actualización de Stock
                foreach ($request->carrito as $item) {
                    // Se crea el detalle del pedido
                    $pedido->detalles()->create([
                        'var_id' => $item['var_id'],
                        'cantidad' => $item['cantidad'],
                        // El precio se podría guardar aquí también si quisieras un histórico
                    ]);
                    
                    // Se descuenta el stock
                    $variante = VarianteProd::find($item['var_id']);
                    if ($variante->var_stok_actual < $item['cantidad']) {
                        // Si el stock cambió mientras hacíamos el pedido, lanzamos un error y la transacción se revierte.
                        throw new \Exception('Stock insuficiente para el producto SKU: ' . $variante->sku);
                    }
                    $variante->decrement('var_stok_actual', $item['cantidad']);
                }
            });
        } catch (\Exception $e) {
            // Si algo falla, la transacción se revierte y mostramos un error.
            return back()->with('error', 'Error al crear el pedido: ' . $e->getMessage())->withInput();
        }
        
        return redirect()->route('pedidos.index')->with('success', '¡Pedido creado exitosamente!');
    }

    /**
     * Muestra los detalles completos de un pedido específico.
     */
    public function show(Pedido $pedido)
    {
        // Carga todas las relaciones para mostrar una vista completa y detallada
        $pedido->load('cliente.ciudad', 'estado', 'metodoPago', 'servicioEntrega', 'detalles.variante.producto', 'detalles.variante.talla', 'detalles.variante.color');
        return view('pedidos.show', compact('pedido'));
    }

    /**
     * Muestra el formulario para editar el estado o datos de un pedido.
     */
    public function edit(Pedido $pedido)
    {
        $estados = EstadoPedido::all();
        // Carga el cliente para mostrar su nombre
        $pedido->load('cliente');
        return view('pedidos.edit', compact('pedido', 'estados'));
    }

    /**
     * Actualiza los datos de un pedido (principalmente su estado).
     */
    public function update(Request $request, Pedido $pedido)
    {
        $request->validate([
            'esta_cod' => 'required|exists:estados_pedidos,esta_cod',
        ]);

        $pedido->update(['esta_cod' => $request->esta_cod]);

        // Lógica futura: Si el estado es "Cancelado", se podría re-ingresar el stock.

        return redirect()->route('pedidos.index')->with('success', 'Estado del pedido actualizado.');
    }

    /**
     * Elimina un pedido. (¡CUIDADO!)
     */
    public function destroy(Pedido $pedido)
    {
        // NOTA: Esta acción no devuelve el stock al inventario. Es una eliminación simple.
        // En una aplicación real, se prefiere cancelar un pedido en lugar de borrarlo.
        $pedido->detalles()->delete();
        $pedido->delete();
        return redirect()->route('pedidos.index')->with('success', 'Pedido eliminado.');
    }
}