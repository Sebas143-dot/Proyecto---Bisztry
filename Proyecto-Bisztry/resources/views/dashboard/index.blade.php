@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Resumen general de tu negocio en tiempo real.')

@section('content')

{{-- Tarjetas de Estadísticas (KPIs) --}}
<div class="stats-cards">
    <a href="#" class="card stat-card">
        <div class="card-header">
            <h3>Ingresos del Mes</h3>
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="card-body">
            <h2>${{ number_format($stats['ingresos_mes_actual'], 2) }}</h2>
            <p class="trend">Total histórico: ${{ number_format($stats['ingresos_totales'], 2) }}</p>
        </div>
    </a>
    <a href="{{ route('pedidos.index') }}" class="card stat-card">
        <div class="card-header">
            <h3>Pedidos Nuevos (Pendientes)</h3>
            <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="card-body">
            <h2>{{ $stats['pedidos_nuevos'] }}</h2>
            <p class="trend">Esperando procesamiento</p>
        </div>
    </a>
    <a href="{{ route('clientes.index') }}" class="card stat-card">
        <div class="card-header">
            <h3>Nuevos Clientes (Este mes)</h3>
            <i class="fas fa-users"></i>
        </div>
        <div class="card-body">
            <h2>+{{ $stats['clientes_nuevos_mes'] }}</h2>
            <p class="trend">Desde el inicio del mes</p>
        </div>
    </a>
</div>

{{-- Contenedor principal con Gráfico y Tablas --}}
<div class="main-grid">
    <div class="card chart-card">
        <div class="card-header">
            <h3>Ventas de los Últimos 7 Días</h3>
        </div>
        <div class="card-body">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <div class="side-grid">
        <div class="card">
            <div class="card-header"><h3>Top 5 Productos Vendidos</h3></div>
            <div class="card-body">
                <ul class="ranking-list">
                    @forelse($topProductos as $producto)
                        <li>
                            <span class="product-name">{{ $producto->prod_nombre }}</span>
                            <span class="product-sales">{{ $producto->total_vendido }} uds.</span>
                        </li>
                    @empty
                        <li class="empty">Aún no hay datos de ventas.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h3>Pedidos Recientes</h3></div>
            <div class="card-body">
                <ul class="ranking-list">
                    @forelse($pedidosRecientes as $pedido)
                    <li class="pedido-item">
                        <div class="pedido-info">
                            <span class="product-name">
                                PED-{{$pedido->pedi_id}} | {{ $pedido->cliente->clie_nombre ?? 'N/A' }}
                            </span>
                            <span class="text-muted text-small">{{ \Carbon\Carbon::parse($pedido->pedi_fecha)->diffForHumans() }}</span>
                        </div>
                        <span class="badge info">${{ number_format($pedido->pedi_total, 2) }}</span>
                    </li>
                    @empty
                        <li class="empty">No hay pedidos recientes.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.main-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; }
.side-grid { display: flex; flex-direction: column; gap: 1.5rem; }
.stat-card { text-decoration: none; color: inherit; }
.stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1); transition: all 0.3s ease; }
.stat-card .card-body h2 { font-size: 2rem; font-weight: 700; }
.stat-card .card-body .trend { font-size: 0.8rem; color: var(--text-muted); }
.ranking-list { list-style: none; }
.ranking-list li { display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid var(--border-color); }
.ranking-list li:last-child { border-bottom: none; }
.ranking-list .product-name { font-weight: 600; }
.ranking-list .product-sales { font-weight: 500; color: var(--primary-color); }
.ranking-list .pedido-info { display: flex; flex-direction: column; }
.ranking-list .text-small { font-size: 0.75rem; }
.ranking-list .empty { justify-content: center; color: var(--text-muted); }
</style>
@endsection

@push('scripts')
{{-- Incluimos la librería Chart.js y renderizamos nuestro gráfico --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($graficoVentas['labels']) !!},
                datasets: [{
                    label: 'Ventas ($)',
                    data: {!! json_encode($graficoVentas['data']) !!},
                    backgroundColor: 'rgba(79, 70, 229, 0.8)',
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ` Ventas: $${context.raw.toFixed(2)}`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush