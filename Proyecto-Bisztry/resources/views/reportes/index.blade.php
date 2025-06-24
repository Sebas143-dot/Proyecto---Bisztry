@extends('layouts.app')

@section('title', 'Reportes')
@section('page-title', 'Reportes')
@section('page-description', 'Visualiza estadísticas y genera informes')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="tabs text-sm">
        <a href="#" class="tab tab-active">Ventas</a>
        <a href="#" class="tab">Clientes</a>
        <a href="#" class="tab">Productos</a>
        <a href="#" class="tab">KPIs</a>
    </div>
    <div class="flex items-center gap-2">
        <select class="select select-bordered select-sm">
            <option>Último mes</option>
            <option>Últimos 3 meses</option>
            <option>Último año</option>
        </select>
        <button class="btn btn-primary btn-sm">
            <i class="fas fa-download mr-1"></i> Exportar PDF
        </button>
    </div>
</div>

{{-- KPIs Horizontales --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="card bg-white shadow-sm">
        <div class="card-body">
            <h3 class="card-title text-sm font-semibold text-gray-500">Ventas totales</h3>
            <p class="text-2xl font-bold">${{ number_format($ventasTotales, 2) }}</p>
            <p class="text-green-600 text-sm mt-1">+{{ $variacionVentas }}% desde el mes pasado</p>
        </div>
    </div>
    <div class="card bg-white shadow-sm">
        <div class="card-body">
            <h3 class="card-title text-sm font-semibold text-gray-500">Ticket promedio</h3>
            <p class="text-2xl font-bold">${{ number_format($ticketPromedio, 2) }}</p>
            <p class="text-green-600 text-sm mt-1">+{{ $variacionTicket }}% desde el mes pasado</p>
        </div>
    </div>
    <div class="card bg-white shadow-sm">
        <div class="card-body">
            <h3 class="card-title text-sm font-semibold text-gray-500">Tasa de conversión</h3>
            <p class="text-2xl font-bold">{{ number_format($tasaConversion, 1) }}%</p>
            <p class="text-green-600 text-sm mt-1">+{{ $variacionConversion }}% desde el mes pasado</p>
        </div>
    </div>
    <div class="card bg-white shadow-sm">
        <div class="card-body">
            <h3 class="card-title text-sm font-semibold text-gray-500">Pedidos completados</h3>
            <p class="text-2xl font-bold">{{ $pedidosCompletados }}</p>
            <p class="text-green-600 text-sm mt-1">+{{ $variacionPedidos }}% desde el mes pasado</p>
        </div>
    </div>
</div>

{{-- Gráfico de barras de ventas --}}
<div class="card mb-6">
    <div class="card-body">
        <h3 class="text-lg font-semibold mb-2">Ventas mensuales</h3>
        <p class="text-sm text-gray-500 mb-4">Comparativa de ventas durante el último año</p>
        <canvas id="ventasChart" height="100"></canvas>
    </div>
</div>

{{-- Gráficos de Distribución --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="card">
        <div class="card-body text-center">
            <h3 class="text-lg font-semibold mb-1">Ventas por canal</h3>
            <p class="text-sm text-gray-500 mb-3">Distribución de ventas por canal de venta</p>
            <i class="fas fa-chart-pie text-4xl text-gray-400"></i>
            <p class="mt-2 font-semibold">Gráfico de distribución</p>
            <p class="text-sm text-gray-500">Aquí se mostrará un gráfico de distribución de ventas por canal<br>(Meta Ads, TikTok Ads, Instagram, WhatsApp, Shopify)</p>
        </div>
    </div>
    <div class="card">
        <div class="card-body text-center">
            <h3 class="text-lg font-semibold mb-1">Ventas por método de pago</h3>
            <p class="text-sm text-gray-500 mb-3">Distribución de ventas por método de pago</p>
            <i class="fas fa-chart-pie text-4xl text-gray-400"></i>
            <p class="mt-2 font-semibold">Gráfico de distribución</p>
            <p class="text-sm text-gray-500">Aquí se mostrará un gráfico de distribución de ventas por método de pago<br>(Efectivo, Transferencias, PayPal, USDT)</p>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('ventasChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Ventas Mensuales',
                data: {!! json_encode($valores) !!},
                backgroundColor: 'rgba(79, 70, 229, 0.8)',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush
@endsection
