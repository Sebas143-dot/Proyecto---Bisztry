<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Categoria;
use App\Models\MetodoPago;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        // --- 1. CONFIGURACIÓN DE FECHAS (CON FILTROS) ---
        $periodo = $request->input('periodo', 'mes_actual'); // 'mes_actual' por defecto
        
        switch ($periodo) {
            case 'ultimos_30_dias':
                $fechaInicio = Carbon::now()->subDays(30);
                $fechaFin = Carbon::now();
                break;
            case 'este_anio':
                $fechaInicio = Carbon::now()->startOfYear();
                $fechaFin = Carbon::now()->endOfYear();
                break;
            case 'mes_actual':
            default:
                $fechaInicio = Carbon::now()->startOfMonth();
                $fechaFin = Carbon::now()->endOfMonth();
                break;
        }

        // --- 2. CONSULTA BASE (PARA NO REPETIR CÓDIGO) ---
        $pedidosCompletadosQuery = Pedido::where('esta_cod', 'ENT')
                                        ->whereBetween('pedi_fecha', [$fechaInicio, $fechaFin]);

        // --- 3. CÁLCULO DE KPIS PRINCIPALES ---
        $kpis = [
            'ventasTotales' => (float) (clone $pedidosCompletadosQuery)->sum('pedi_total'),
            'pedidosCompletados' => (clone $pedidosCompletadosQuery)->count(),
        ];
        $kpis['ticketPromedio'] = ($kpis['pedidosCompletados'] > 0) ? $kpis['ventasTotales'] / $kpis['pedidosCompletados'] : 0;

        // --- 4. DATOS PARA GRÁFICO PRINCIPAL (VENTAS DIARIAS/MENSUALES) ---
        $formatoFecha = ($periodo === 'este_anio') ? '%Y-%m' : '%Y-%m-%d';
        $labelFormato = ($periodo === 'este_anio') ? 'M' : 'd M';
        
        $ventasAgrupadas = (clone $pedidosCompletadosQuery)
            ->select(
                DB::raw("to_char(pedi_fecha, '$formatoFecha') as fecha_agrupada"),
                DB::raw('SUM(pedi_total) as total')
            )
            ->groupBy('fecha_agrupada')
            ->orderBy('fecha_agrupada', 'asc')
            ->get();
        
        $graficoPrincipal = [
            'labels' => $ventasAgrupadas->map(fn($item) => Carbon::parse($item->fecha_agrupada)->format($labelFormato)),
            'valores' => $ventasAgrupadas->pluck('total')
        ];

        // --- 5. DATOS PARA GRÁFICOS SECUNDARIOS ---
        $ventasPorCategoria = Categoria::select('categorias.cate_detalle')
            ->join('productos', 'categorias.cate_id', '=', 'productos.cate_id')
            ->join('variantes_prod', 'productos.prod_cod', '=', 'variantes_prod.prod_cod')
            ->join('detalles_pedidos', 'variantes_prod.var_id', '=', 'detalles_pedidos.var_id')
            ->join('pedidos', 'detalles_pedidos.pedi_id', '=', 'pedidos.pedi_id')
            ->where('pedidos.esta_cod', 'ENT')
            ->whereBetween('pedidos.pedi_fecha', [$fechaInicio, $fechaFin])
            ->select('categorias.cate_detalle', DB::raw('SUM(detalles_pedidos.cantidad * variantes_prod.var_precio) as total'))
            ->groupBy('categorias.cate_detalle')
            ->orderBy('total', 'desc')
            ->get();

        $ventasPorMetodoPago = MetodoPago::join('pedidos', 'metodos_pago.meto_cod', '=', 'pedidos.meto_cod')
            ->where('pedidos.esta_cod', 'ENT')
            ->whereBetween('pedidos.pedi_fecha', [$fechaInicio, $fechaFin])
            ->select('metodos_pago.medo_detale', DB::raw('COUNT(pedidos.pedi_id) as cantidad'))
            ->groupBy('metodos_pago.medo_detale')
            ->get();
            
        return view('reportes.index', compact('kpis', 'graficoPrincipal', 'ventasPorCategoria', 'ventasPorMetodoPago', 'periodo'));
    }
}