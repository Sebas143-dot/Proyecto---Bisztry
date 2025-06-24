<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\Producto;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas Reales
        $ingresosTotales = Pedido::where('esta_cod', 'ENT')->sum('pedi_total');
        $pedidosNuevos = Pedido::where('esta_cod', 'PEN')->count();
        $clientesActivos = Cliente::count(); // Simplificado, se puede mejorar la lógica de "activo"
        $productosBajoStock = \App\Models\VarianteProd::whereRaw('var_stok_actual <= var_stock_min')->count();

        $stats = [
            'ingresos_totales' => $ingresosTotales,
            'pedidos_nuevos' => $pedidosNuevos,
            'clientes_activos' => $clientesActivos,
            'inventario_bajo' => $productosBajoStock
        ];

        // Pedidos Recientes
        $pedidosRecientes = Pedido::with('cliente')
            ->orderBy('pedi_fecha', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.index', compact('stats', 'pedidosRecientes'));
    }
}