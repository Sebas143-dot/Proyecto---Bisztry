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
            'ingresos_mes_actual' => (float) Pedido::where('esta_cod', 'ENT')->whereYear('pedi_fecha', now()->year)->whereMonth('pedi_fecha', now()->month)->sum('pedi_total'),
            'pedidos_nuevos' => Pedido::where('esta_cod', 'PEN')->count(),
            'clientes_nuevos_mes' => Cliente::whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count(),
        ];

        // --- DATOS PARA GRÁFICO DE VENTAS (ÚLTIMOS 7 DÍAS) ---
        $ventasDiarias = Pedido::select(
                DB::raw('DATE(pedi_fecha) as fecha'),
                DB::raw('SUM(pedi_total) as total')
            )
            ->where('pedi_fecha', '>=', Carbon::now()->subDays(6))
            ->groupBy('fecha')
            ->orderBy('fecha', 'asc')
            ->get()
            ->pluck('total', 'fecha');
        
        $labelsGrafico = [];
        $dataGrafico = [];
        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::now()->subDays($i)->format('Y-m-d');
            $labelsGrafico[] = Carbon::parse($fecha)->format('D, M j'); // Formato: "Lun, Jun 24"
            $dataGrafico[] = $ventasDiarias->get($fecha, 0); // Si no hay ventas, pone 0
        }

        $graficoVentas = [
            'labels' => $labelsGrafico,
            'data' => $dataGrafico,
        ];

        // --- TOP 5 PRODUCTOS MÁS VENDIDOS ---
        $topProductos = DB::table('detalles_pedidos')
            ->join('variantes_prod', 'detalles_pedidos.var_id', '=', 'variantes_prod.var_id')
            ->join('productos', 'variantes_prod.prod_cod', '=', 'productos.prod_cod')
            ->select('productos.prod_nombre', DB::raw('SUM(detalles_pedidos.cantidad) as total_vendido'))
            ->groupBy('productos.prod_nombre')
            ->orderBy('total_vendido', 'desc')
            ->take(5)
            ->get();
            
        // --- PEDIDOS RECIENTES ---
        $pedidosRecientes = Pedido::with('cliente', 'estado')
            ->latest('pedi_fecha')
            ->take(5)
            ->get();

        // Devolvemos la vista con todas las variables que hemos preparado
        return view('dashboard.index', compact('stats', 'pedidosRecientes', 'graficoVentas', 'topProductos'));
    }
}