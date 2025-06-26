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

class PedidoController extends Controller
{
    public function index(Request $request) {
        $estados = EstadoPedido::all();
        $query = Pedido::with('cliente', 'estado')->latest('pedi_fecha');
        if ($request->filled('estado_filtro') && $request->estado_filtro != 'todos') {
            $query->where('esta_cod', $request->estado_filtro);
        }
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('pedi_id', 'like', "%{$search}%")
                  ->orWhereHas('cliente', fn($clienteQuery) => $clienteQuery->where('clie_nombre', 'like', "%{$search}%"));
            });
        }
        $pedidos = $query->paginate(15);
        return view('pedidos.index', compact('pedidos', 'estados'));
    }

    public function createStep1() {
        session()->forget('pedido');
        $clientes = Cliente::orderBy('clie_nombre')->get();
        return view('pedidos.create-step-1', compact('clientes'));
    }

    public function postStep1(Request $request) {
        $validated = $request->validate(['clie_id' => 'required|exists:clientes,clie_id']);
        session(['pedido.cliente_id' => $validated['clie_id'], 'pedido.carrito' => []]);
        return redirect()->route('pedidos.create.step2');
    }

    public function createStep2() {
        if (!session()->has('pedido.cliente_id')) { return redirect()->route('pedidos.create.step1'); }
        $variantes = VarianteProd::with('producto', 'talla', 'color')->where('var_stok_actual', '>', 0)->get();
        $carrito = session('pedido.carrito', []);
        return view('pedidos.create-step-2', compact('variantes', 'carrito'));
    }

    public function addToCart(Request $request) {
        $request->validate(['variante_id' => 'required|exists:variantes_prod,var_id', 'cantidad' => 'required|integer|min:1']);
        $variante = VarianteProd::find($request->variante_id);
        if ($request->cantidad > $variante->var_stok_actual) { return back()->with('error', 'No hay suficiente stock.'); }
        $carrito = session('pedido.carrito', []);
        $carrito[$request->variante_id] = [
            'nombre' => sprintf('%s (%s, %s)', $variante->producto->prod_nombre, $variante->talla->tall_detalle, $variante->color->col_detalle),
            'cantidad' => (int)$request->cantidad, 'precio' => (float)$variante->var_precio,
            'subtotal' => (int)$request->cantidad * (float)$variante->var_precio
        ];
        session(['pedido.carrito' => $carrito]);
        return back()->with('success', 'Producto añadido/actualizado.');
    }

    public function removeFromCart(Request $request) {
        $request->validate(['variante_id' => 'required']);
        $carrito = session('pedido.carrito', []);
        unset($carrito[$request->variante_id]);
        session(['pedido.carrito' => $carrito]);
        return back()->with('success', 'Producto eliminado.');
    }

    public function createStep3() {
        $pedidoSession = session('pedido');
        if (empty($pedidoSession) || empty($pedidoSession['carrito'])) { return redirect()->route('pedidos.create.step2')->with('error', 'Tu carrito está vacío.'); }
        $cliente = Cliente::find($pedidoSession['cliente_id']);
        $metodosPago = MetodoPago::all(); $serviciosEntrega = ServicioEntrega::all(); $estados = EstadoPedido::all();
        return view('pedidos.create-step-3', compact('cliente', 'pedidoSession', 'metodosPago', 'serviciosEntrega', 'estados'));
    }

    public function store(Request $request) {
        $pedidoSession = session('pedido');
        if (empty($pedidoSession)) { return redirect()->route('pedidos.create.step1'); }
        $validated = $request->validate(['pedi_fecha' => 'required|date', 'esta_cod' => 'required|exists:estados_pedidos,esta_cod', 'meto_cod' => 'required|exists:metodos_pago,meto_cod', 'serv_id' => 'required|exists:servicios_entrega,serv_id', 'pedi_costo_envio' => 'required|numeric|min:0']);
        try {
            DB::transaction(function () use ($validated, $pedidoSession, $request) {
                $totalPedido = array_reduce($pedidoSession['carrito'], fn($sum, $item) => $sum + $item['subtotal'], 0);
                $pedido = Pedido::create(array_merge($validated, ['clie_id' => $pedidoSession['cliente_id'], 'pedi_direccion' => $request->pedi_direccion, 'pedi_total' => $totalPedido, 'pedi_costo_envio' => $validated['pedi_costo_envio']]));
                foreach ($pedidoSession['carrito'] as $var_id => $item) {
                    $variante = VarianteProd::find($var_id);
                    if ($variante->var_stok_actual < $item['cantidad']) { throw new \Exception('Stock insuficiente para ' . $variante->sku); }
                    $pedido->detalles()->create(['var_id' => $var_id, 'cantidad' => $item['cantidad']]);
                    $variante->decrement('var_stok_actual', $item['cantidad']);
                }
            });
        } catch (\Exception $e) { return back()->with('error', 'Error: ' . $e->getMessage()); }
        session()->forget('pedido');
        return redirect()->route('pedidos.index')->with('success', '¡Pedido creado!');
    }

    public function show(Pedido $pedido) {
        $pedido->load('cliente.ciudad', 'estado', 'metodoPago', 'servicioEntrega', 'detalles.variante.producto', 'detalles.variante.talla', 'detalles.variante.color');
        return view('pedidos.show', compact('pedido'));
    }

    /**
     * Muestra el formulario para editar un pedido completo.
     */
    public function edit(Pedido $pedido)
    {
        $pedido->load('cliente', 'detalles.variante.producto', 'detalles.variante.talla', 'detalles.variante.color');
        $estados = EstadoPedido::all();
        $variantesParaSelect2 = VarianteProd::with('producto', 'talla', 'color')->get()
            ->map(function ($variante) {
                $texto = sprintf('%s (Talla: %s, Color: %s) | Stock: %d', $variante->producto->prod_nombre, $variante->talla->tall_detalle, $variante->color->col_detalle, $variante->var_stok_actual);
                return ['id' => $variante->var_id, 'text' => $texto, 'datos_completos' => ['var_id' => $variante->var_id, 'nombre' => sprintf('%s (%s, %s)', $variante->producto->prod_nombre, $variante->talla->tall_detalle, $variante->color->col_detalle), 'precio' => (float)$variante->var_precio, 'stock_max' => (int)$variante->var_stok_actual]];
            });
        return view('pedidos.edit', compact('pedido', 'estados', 'variantesParaSelect2'));
    }

    /**
     * Actualiza un pedido, su contenido y el stock correspondiente.
     */
    public function update(Request $request, Pedido $pedido)
    {
        $validatedData = $request->validate(['esta_cod' => 'required|exists:estados_pedidos,esta_cod', 'carrito' => 'required|string']);
        $nuevoCarritoItems = json_decode($validatedData['carrito'], true);
        if (empty($nuevoCarritoItems)) { return back()->with('error', 'El carrito no puede quedar vacío.'); }

        try {
            DB::transaction(function () use ($pedido, $nuevoCarritoItems, $validatedData) {
                $detallesOriginales = $pedido->detalles->keyBy('var_id');
                foreach ($detallesOriginales as $detalle) { $detalle->variante->increment('var_stok_actual', $detalle->cantidad); }
                $pedido->detalles()->delete();
                $nuevoTotal = 0;
                foreach ($nuevoCarritoItems as $item) {
                    $variante = VarianteProd::find($item['var_id']);
                    if (!$variante || $variante->var_stok_actual < $item['cantidad']) { throw new \Exception("Stock insuficiente para: " . ($item['nombre'] ?? 'ID '.$item['var_id'])); }
                    $pedido->detalles()->create(['var_id' => $item['var_id'], 'cantidad' => $item['cantidad']]);
                    $variante->decrement('var_stok_actual', $item['cantidad']);
                    $nuevoTotal += $item['cantidad'] * $item['precio'];
                }
                $pedido->update(['esta_cod' => $validatedData['esta_cod'], 'pedi_total' => $nuevoTotal]);
            });
        } catch (\Exception $e) { return back()->with('error', 'Error al actualizar: ' . $e->getMessage())->withInput(); }
        return redirect()->route('pedidos.show', $pedido)->with('success', 'Pedido actualizado exitosamente.');
    }

    public function destroy(Pedido $pedido) {
        // En un sistema real, se preferiría cancelar un pedido y devolver el stock.
        try {
            DB::transaction(function () use ($pedido) {
                foreach ($pedido->detalles as $detalle) { $detalle->variante->increment('var_stok_actual', $detalle->cantidad); }
                $pedido->detalles()->delete(); $pedido->delete();
            });
        } catch (\Exception $e) { return back()->with('error', 'Error al eliminar: ' . $e->getMessage()); }
        return redirect()->route('pedidos.index')->with('success', 'Pedido eliminado y stock devuelto.');
    }
}
