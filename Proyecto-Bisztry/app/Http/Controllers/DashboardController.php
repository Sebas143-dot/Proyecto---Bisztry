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
        // --- INICIO DE LA CORRECCIÓN DE TOTALES ---
        // Usamos DB::raw para poder sumar las dos columnas en la base de datos
        $ingresosTotales = (float) Pedido::where('esta_cod', 'ENT')->sum(DB::raw('pedi_total + pedi_costo_envio'));
        
        $ingresosMesActual = (float) Pedido::where('esta_cod', 'ENT')
            ->whereBetween('pedi_fecha', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum(DB::raw('pedi_total + pedi_costo_envio'));
        // --- FIN DE LA CORRECCIÓN DE TOTALES ---

        $stats = [
            'ingresos_totales' => $ingresosTotales,
            'ingresos_mes_actual' => $ingresosMesActual,
            'pedidos_nuevos' => Pedido::where('esta_cod', 'PEN')->count(),
            'clientes_nuevos_mes' => Cliente::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count(),
        ];

        $ventasRaw = Pedido::select(
                DB::raw('DATE(pedi_fecha) as fecha'),
                DB::raw('SUM(pedi_total + pedi_costo_envio) as total') // También corregimos aquí
            )
            ->where('esta_cod', 'ENT')
            ->where('pedi_fecha', '>=', Carbon::now()->subDays(6))
            ->groupBy(DB::raw('DATE(pedi_fecha)'))
            ->get()
            ->keyBy(fn($venta) => Carbon::parse($venta->fecha)->format('Y-m-d'));

        $labelsGrafico = [];
        $dataGrafico = [];
        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::now()->subDays($i)->format('Y-m-d');
            $labelsGrafico[] = Carbon::parse($fecha)->format('D, M j');
            $dataGrafico[] = $ventasRaw->get($fecha)->total ?? 0;
        }

        $graficoVentas = ['labels' => $labelsGrafico, 'data' => $dataGrafico];
            
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
