<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\VarianteProd;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // --- CÁLCULO DE ESTADÍSTICAS (KPIs) ---
        $stats = [
            'ingresos_totales' => (float) Pedido::where('esta_cod', 'ENT')->sum('pedi_total'),
            'ingresos_mes_actual' => (float) Pedido::where('esta_cod', 'ENT')->whereBetween('pedi_fecha', [now()->startOfMonth(), now()->endOfMonth()])->sum('pedi_total'),
            'pedidos_nuevos' => Pedido::where('esta_cod', 'PEN')->count(),
            'clientes_nuevos_mes' => Cliente::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count(),
        ];

        // --- DATOS PARA GRÁFICO DE VENTAS (ÚLTIMOS 7 DÍAS) - VERSIÓN A PRUEBA DE ERRORES ---
        
        // 1. Obtenemos los datos de ventas de la base de datos
        $ventasRaw = Pedido::select(
                DB::raw('DATE(pedi_fecha) as fecha'),
                DB::raw('SUM(pedi_total) as total')
            )
            ->where('esta_cod', 'ENT')
            ->where('pedi_fecha', '>=', Carbon::now()->subDays(6))
            ->groupBy(DB::raw('DATE(pedi_fecha)'))
            ->get();
        
        // --- INICIO DE LA CORRECCIÓN DEFINITIVA ---
        // 2. Creamos nuestro mapa de datos manualmente para evitar cualquier problema de tipo de dato.
        $ventasMapeadas = [];
        foreach($ventasRaw as $venta) {
            // Forzamos la fecha a ser un string con formato 'Y-m-d' antes de usarla como llave del array.
            // Esta es la corrección clave que evita el error 'Illegal offset type'.
            $fechaKey = Carbon::parse($venta->fecha)->format('Y-m-d');
            $ventasMapeadas[$fechaKey] = $venta->total;
        }

        // 3. Preparamos los arrays para el gráfico, rellenando con 0 los días sin ventas
        $labelsGrafico = [];
        $dataGrafico = [];
        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::now()->subDays($i)->format('Y-m-d');
            $labelsGrafico[] = Carbon::parse($fecha)->format('D, M j');
            // Usamos nuestro mapa manual para obtener el valor, o 0 si no existe.
            $dataGrafico[] = $ventasMapeadas[$fecha] ?? 0;
        }
        // --- FIN DE LA CORRECCIÓN DEFINITIVA ---

        $graficoVentas = [
            'labels' => $labelsGrafico,
            'data' => $dataGrafico,
        ];
            
        $topProductos = DB::table('detalles_pedidos')
            ->join('variantes_prod', 'detalles_pedidos.var_id', '=', 'variantes_prod.var_id')
            ->join('productos', 'variantes_prod.prod_cod', '=', 'productos.prod_cod')
            ->select('productos.prod_nombre', DB::raw('SUM(detalles_pedidos.cantidad) as total_vendido'))
            ->groupBy('productos.prod_cod', 'productos.prod_nombre')
            ->orderByDesc('total_vendido')
            ->take(5)
            ->get();
            
        $pedidosRecientes = Pedido::with('cliente', 'estado')
            ->latest('pedi_fecha')
            ->take(5)
            ->get();

        return view('dashboard.index', compact('stats', 'pedidosRecientes', 'graficoVentas', 'topProductos'));
    }
}
