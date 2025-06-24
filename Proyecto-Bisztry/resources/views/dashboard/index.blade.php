@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Vista general de tu negocio.')

@section('content')
<div class="stats-cards">
    <div class="card stat-card">
        <div class="card-header">
            <h3>Ingresos totales (Entregados)</h3>
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="card-body">
            <h2>${{ number_format($stats['ingresos_totales'], 2) }}</h2>
        </div>
    </div>
    <div class="card stat-card">
        <div class="card-header">
            <h3>Pedidos Nuevos (Pendientes)</h3>
            <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="card-body">
            <h2>{{ $stats['pedidos_nuevos'] }}</h2>
        </div>
    </div>
    <div class="card stat-card">
        <div class="card-header">
            <h3>Clientes Registrados</h3>
            <i class="fas fa-users"></i>
        </div>
        <div class="card-body">
            <h2>{{ $stats['clientes_activos'] }}</h2>
        </div>
    </div>
    <div class="card stat-card">
        <div class="card-header">
            <h3>Productos con Bajo Stock</h3>
            <i class="fas fa-box"></i>
        </div>
        <div class="card-body">
            <h2>{{ $stats['inventario_bajo'] }}</h2>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Últimos Pedidos</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pedidosRecientes as $pedido)
                    <tr>
                        <td class="font-bold">PED-{{ $pedido->pedi_id }}</td>
                        <td>{{ $pedido->cliente->clie_nombre }} {{ $pedido->cliente->clie_apellido }}</td>
                        <td>{{ \Carbon\Carbon::parse($pedido->pedi_fecha)->format('d/m/Y') }}</td>
                        <td><span class="badge info">{{ $pedido->estado->esta__detalle ?? 'N/A' }}</span></td>
                        <td>${{ number_format($pedido->pedi_total, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No hay pedidos recientes.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection