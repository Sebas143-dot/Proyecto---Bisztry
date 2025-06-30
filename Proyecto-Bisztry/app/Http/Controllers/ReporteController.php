<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\VarianteProd;
use Illuminate\Support\Facades\DB;
// --- INICIO DE LÍNEAS AÑADIDAS ---
use App\Models\Categoria;
use App\Models\MetodoPago;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PedidosReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
// --- FIN DE LÍNEAS AÑADIDAS ---

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $periodo = $request->input('periodo', 'mes_actual');
        switch ($periodo) {
            case 'ultimos_30_dias':
                $fechaInicio = Carbon::now()->subDays(29)->startOfDay();
                $fechaFin = Carbon::now()->endOfDay();
                break;
            case 'este_anio':
                $fechaInicio = Carbon::now()->startOfYear();
                $fechaFin = Carbon::now()->endOfYear();
                break;
            default:
                $fechaInicio = Carbon::now()->startOfMonth();
                $fechaFin = Carbon::now()->endOfMonth();
                break;
        }

        $pedidosCompletadosQuery = Pedido::where('esta_cod', 'ENT')->whereBetween('pedi_fecha', [$fechaInicio, $fechaFin]);

        // --- CÁLCULO DE TOTALES (VERSIÓN ANTERIOR) ---
        // Aquí se suma únicamente el 'pedi_total' (total de productos)
        $kpis = [
            'ventasTotales' => (float) (clone $pedidosCompletadosQuery)->sum('pedi_total'),
            'pedidosCompletados' => (clone $pedidosCompletadosQuery)->count(),
        ];
        $kpis['ticketPromedio'] = ($kpis['pedidosCompletados'] > 0) ? $kpis['ventasTotales'] / $kpis['pedidosCompletados'] : 0;

        $formatoFechaSQL = ($periodo === 'este_anio') ? 'YYYY-MM' : 'YYYY-MM-DD';
        $labelFormatoPHP = ($periodo === 'este_anio') ? 'M Y' : 'd M';
        
        $ventasAgrupadas = (clone $pedidosCompletadosQuery)
            ->select(
                DB::raw("to_char(pedi_fecha, '$formatoFechaSQL') as fecha_agrupada"),
                // El cálculo del gráfico también usa solo 'pedi_total'
                DB::raw('SUM(pedi_total) as total')
            )
            ->groupBy('fecha_agrupada')->orderBy('fecha_agrupada', 'asc')->get();
        // --- FIN DE LA REVERSIÓN ---
        
        $graficoPrincipal = [
            'labels' => $ventasAgrupadas->map(fn($item) => Carbon::parse($item->fecha_agrupada)->format($labelFormatoPHP)),
            'valores' => $ventasAgrupadas->pluck('total')
        ];

        $ventasPorCategoria = Categoria::join('productos', 'categorias.cate_id', '=', 'productos.cate_id')
            ->join('variantes_prod', 'productos.prod_cod', '=', 'variantes_prod.prod_cod')
            ->join('detalles_pedidos', 'variantes_prod.var_id', '=', 'detalles_pedidos.var_id')
            ->join('pedidos', 'detalles_pedidos.pedi_id', '=', 'pedidos.pedi_id')
            ->where('pedidos.esta_cod', 'ENT')->whereBetween('pedidos.pedi_fecha', [$fechaInicio, $fechaFin])
            ->select('categorias.cate_detalle', DB::raw('SUM(detalles_pedidos.cantidad * variantes_prod.var_precio) as total'))
            ->groupBy('categorias.cate_detalle')->orderBy('total', 'desc')->get();

        $ventasPorMetodoPago = MetodoPago::join('pedidos', 'metodos_pago.meto_cod', '=', 'pedidos.meto_cod')
            ->where('pedidos.esta_cod', 'ENT')->whereBetween('pedidos.pedi_fecha', [$fechaInicio, $fechaFin])
            ->select('metodos_pago.medo_detale', DB::raw('COUNT(pedidos.pedi_id) as cantidad'))
            ->groupBy('metodos_pago.medo_detale')->get();
            
        return view('reportes.index', compact('kpis', 'graficoPrincipal', 'ventasPorCategoria', 'ventasPorMetodoPago', 'periodo'));
    }

    // --- INICIO DE MÉTODOS DE EXPORTACIÓN MEJORADOS ---

    /**
     * Genera y descarga un reporte de ventas en formato PDF.
     */
    public function exportarPDF(Request $request)
    {
        $periodo = $request->input('periodo', 'mes_actual');
        $periodoDescriptivo = '';
        switch ($periodo) {
            case 'ultimos_30_dias':
                $fechaInicio = Carbon::now()->subDays(29)->startOfDay();
                $fechaFin = Carbon::now()->endOfDay();
                $periodoDescriptivo = 'Últimos 30 Días';
                break;
            case 'este_anio':
                $fechaInicio = Carbon::now()->startOfYear();
                $fechaFin = Carbon::now()->endOfYear();
                $periodoDescriptivo = 'Año ' . $fechaInicio->year;
                break;
            default:
                $fechaInicio = Carbon::now()->startOfMonth();
                $fechaFin = Carbon::now()->endOfMonth();
                $periodoDescriptivo = ucfirst(Carbon::now('America/Guayaquil')->locale('es')->monthName) . ' ' . $fechaInicio->year;
                break;
        }

        $pedidosQuery = Pedido::where('esta_cod', 'ENT')->whereBetween('pedi_fecha', [$fechaInicio, $fechaFin]);
        
        $pedidos = (clone $pedidosQuery)->with(['cliente', 'detalles.variante.producto'])->get();
        
        // --- CÁLCULO DE KPIS Y TOTALES PARA EL REPORTE ---
        $kpis = [
            'ventasTotales' => (clone $pedidosQuery)->sum(DB::raw('pedi_total + pedi_costo_envio')),
            'pedidosCompletados' => $pedidos->count(),
            'totalProductos' => (clone $pedidosQuery)->sum('pedi_total'), // Suma solo de productos
            'totalEnvios' => (clone $pedidosQuery)->sum('pedi_costo_envio'), // Suma solo de envíos
        ];
        $kpis['ticketPromedio'] = ($kpis['pedidosCompletados'] > 0) ? $kpis['ventasTotales'] / $kpis['pedidosCompletados'] : 0;
        
        $fechaGeneracion = Carbon::now('America/Guayaquil')->format('d/m/Y H:i:s') . ' (GMT-5)';

        $pdf = Pdf::loadView('pdf.reporte_ventas', compact('pedidos', 'kpis', 'periodoDescriptivo', 'fechaGeneracion'));
        
        return $pdf->download('reporte-ventas-bizstry-'.date('Y-m-d').'.pdf');
    }

    /**
     * Genera y descarga un reporte de pedidos en formato Excel.
     */
    public function exportarExcel(Request $request)
    {
        $periodo = $request->input('periodo', 'mes_actual');
        switch ($periodo) {
            case 'ultimos_30_dias': $fechaInicio = Carbon::now()->subDays(29)->startOfDay(); $fechaFin = Carbon::now()->endOfDay(); break;
            case 'este_anio': $fechaInicio = Carbon::now()->startOfYear(); $fechaFin = Carbon::now()->endOfYear(); break;
            default: $fechaInicio = Carbon::now()->startOfMonth(); $fechaFin = Carbon::now()->endOfMonth(); break;
        }
        
        return Excel::download(new PedidosReportExport($fechaInicio, $fechaFin, $periodo), 'reporte-pedidos-bizstry-'.date('Y-m-d').'.xlsx');
    }
    // --- FIN DE MÉTODOS DE EXPORTACIÓN AÑADIDOS ---
}
