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

        // --- INICIO DE LA CORRECCIÓN DE TOTALES ---
        $kpis = [
            'ventasTotales' => (float) (clone $pedidosCompletadosQuery)->sum(DB::raw('pedi_total + pedi_costo_envio')),
            'pedidosCompletados' => (clone $pedidosCompletadosQuery)->count(),
        ];
        $kpis['ticketPromedio'] = ($kpis['pedidosCompletados'] > 0) ? $kpis['ventasTotales'] / $kpis['pedidosCompletados'] : 0;

        $formatoFechaSQL = ($periodo === 'este_anio') ? 'YYYY-MM' : 'YYYY-MM-DD';
        $labelFormatoPHP = ($periodo === 'este_anio') ? 'M Y' : 'd M';
        
        $ventasAgrupadas = (clone $pedidosCompletadosQuery)
            ->select(
                DB::raw("to_char(pedi_fecha, '$formatoFechaSQL') as fecha_agrupada"),
                DB::raw('SUM(pedi_total + pedi_costo_envio) as total') // También corregimos aquí
            )
            ->groupBy('fecha_agrupada')->orderBy('fecha_agrupada', 'asc')->get();
        // --- FIN DE LA CORRECCIÓN DE TOTALES ---
        
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
}
