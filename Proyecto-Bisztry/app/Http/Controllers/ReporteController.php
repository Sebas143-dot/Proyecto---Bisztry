<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\VarianteProd;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {
        $mesActual = date('m');
        $anioActual = date('Y');
        $mesAnterior = date('m', strtotime('-1 month'));
        $anioAnterior = date('Y', strtotime('-1 month'));

        // Ventas actuales
        $ventasActual = Pedido::where('esta_cod', 'ENT')
            ->whereMonth('pedi_fecha', $mesActual)
            ->whereYear('pedi_fecha', $anioActual)
            ->sum('pedi_total');

        $ventasPasadas = Pedido::where('esta_cod', 'ENT')
            ->whereMonth('pedi_fecha', $mesAnterior)
            ->whereYear('pedi_fecha', $anioAnterior)
            ->sum('pedi_total');

        $variacionVentas = $ventasPasadas > 0 ? (($ventasActual - $ventasPasadas) / $ventasPasadas) * 100 : 0;

        // Ticket Promedio
        $totalPedidosMesActual = Pedido::where('esta_cod', 'ENT')
            ->whereMonth('pedi_fecha', $mesActual)
            ->whereYear('pedi_fecha', $anioActual)
            ->count();

        $ticketPromedio = $totalPedidosMesActual > 0 ? $ventasActual / $totalPedidosMesActual : 0;

        $totalPedidosMesAnterior = Pedido::where('esta_cod', 'ENT')
            ->whereMonth('pedi_fecha', $mesAnterior)
            ->whereYear('pedi_fecha', $anioAnterior)
            ->count();

        $ventasMesAnterior = Pedido::where('esta_cod', 'ENT')
            ->whereMonth('pedi_fecha', $mesAnterior)
            ->whereYear('pedi_fecha', $anioAnterior)
            ->sum('pedi_total');

        $ticketPromedioAnterior = $totalPedidosMesAnterior > 0 ? $ventasMesAnterior / $totalPedidosMesAnterior : 0;
        $variacionTicket = $ticketPromedioAnterior > 0 ? (($ticketPromedio - $ticketPromedioAnterior) / $ticketPromedioAnterior) * 100 : 0;

        // Tasa de conversión
        $intentosCompra = 11200;
        $intentosCompraAnterior = 10000;

        $tasaConversion = $intentosCompra > 0 ? ($totalPedidosMesActual / $intentosCompra) * 100 : 0;
        $tasaConversionAnterior = $intentosCompraAnterior > 0 ? ($totalPedidosMesAnterior / $intentosCompraAnterior) * 100 : 0;

        $variacionConversion = $tasaConversionAnterior > 0 ? (($tasaConversion - $tasaConversionAnterior) / $tasaConversionAnterior) * 100 : 0;

        // Pedidos completados
        $pedidosCompletados = $totalPedidosMesActual;
        $variacionPedidos = $totalPedidosMesAnterior > 0 ? (($pedidosCompletados - $totalPedidosMesAnterior) / $totalPedidosMesAnterior) * 100 : 0;

        // Stats adicionales
        $stats = [
            'pedidos_mes' => $totalPedidosMesActual,
            'clientes_activos' => DB::table('clientes')->count(),
            'ingresos_totales' => Pedido::where('esta_cod', 'ENT')->sum('pedi_total'),
            'bajo_stock' => VarianteProd::whereColumn('var_stok_actual', '<=', 'var_stock_min')->count(),
        ];

        // Reportes de ventas mensuales (últimos 12 meses)
        $ventasMensuales = Pedido::selectRaw("TO_CHAR(pedi_fecha, 'Mon') as mes")
            ->selectRaw("SUM(pedi_total) as total")
            ->where('esta_cod', 'ENT')
            ->groupByRaw("TO_CHAR(pedi_fecha, 'Mon'), EXTRACT(MONTH FROM pedi_fecha)")
            ->orderByRaw("EXTRACT(MONTH FROM pedi_fecha)")
            ->limit(12)
            ->get();

        $labels = $ventasMensuales->pluck('mes');
        $valores = $ventasMensuales->pluck('total');

        // Productos con bajo stock
        $productosBajoStock = VarianteProd::whereColumn('var_stok_actual', '<=', 'var_stock_min')->get();

        return view('reportes.index', [
            'ventasTotales' => $ventasActual,
            'variacionVentas' => $variacionVentas,
            'ticketPromedio' => $ticketPromedio,
            'variacionTicket' => $variacionTicket,
            'tasaConversion' => $tasaConversion,
            'variacionConversion' => $variacionConversion,
            'pedidosCompletados' => $pedidosCompletados,
            'variacionPedidos' => $variacionPedidos,
            'stats' => $stats,
            'productosBajoStock' => $productosBajoStock,
            'labels' => $labels,
            'valores' => $valores
        ]);
    }
}
