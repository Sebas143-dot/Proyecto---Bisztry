@extends('layouts.app')

@section('title', 'Reportes')
@section('page-title', 'Centro de Reportes')
@section('page-description', 'Analiza el rendimiento de tu negocio con datos clave.')

@section('content')
<div x-data="{ tab: 'ventas' }">
    {{-- Navegación de Pestañas (Tabs) y Filtros --}}
    <div class="tabs-container">
        <div class="tabs">
            <a href="#" class="tab" :class="{ 'tab-active': tab === 'ventas' }" @click.prevent="tab = 'ventas'"><i class="fas fa-chart-line"></i> Ventas</a>
            <a href="#" class="tab" :class="{ 'tab-active': tab === 'productos' }" @click.prevent="tab = 'productos'"><i class="fas fa-box"></i> Productos</a>
        </div>
        
        <div class="filtros-fecha">
            <form id="filtro-form" method="GET" action="{{ route('reportes.index') }}">
                <select name="periodo" class="select-filtro" onchange="this.form.submit()">
                    <option value="mes_actual" {{ $periodo == 'mes_actual' ? 'selected' : '' }}>Este Mes</option>
                    <option value="ultimos_30_dias" {{ $periodo == 'ultimos_30_dias' ? 'selected' : '' }}>Últimos 30 días</option>
                    <option value="este_anio" {{ $periodo == 'este_anio' ? 'selected' : '' }}>Este Año</option>
                </select>
            </form>
            
            {{-- Los botones de exportación solo se muestran si el usuario tiene el permiso --}}
            @can('ver-reportes')
                <a href="{{ route('reportes.exportar.pdf', ['periodo' => $periodo]) }}" class="btn btn-outline" target="_blank"><i class="fas fa-file-pdf"></i> PDF</a>
                <a href="{{ route('reportes.exportar.excel', ['periodo' => $periodo]) }}" class="btn btn-outline"><i class="fas fa-file-excel"></i> Excel</a>
            @endcan
        </div>
    </div>

    {{-- Pestaña "Ventas" --}}
    <div x-show="tab === 'ventas'" x-transition.opacity>
        <div class="stats-cards">
            <div class="card stat-card"><div class="card-body"><h3 class="stat-title">Ventas Totales</h3><p class="stat-value">${{ number_format($kpis['ventasTotales'], 2) }}</p></div></div>
            <div class="card stat-card"><div class="card-body"><h3 class="stat-title">Ticket Promedio</h3><p class="stat-value">${{ number_format($kpis['ticketPromedio'], 2) }}</p></div></div>
            <div class="card stat-card"><div class="card-body"><h3 class="stat-title">Pedidos Completados</h3><p class="stat-value">{{ $kpis['pedidosCompletados'] }}</p></div></div>
        </div>
        <div class="card">
            <div class="card-header"><h3>Rendimiento de Ventas ({{ ucfirst(str_replace('_', ' ', $periodo)) }})</h3></div>
            <div class="card-body" style="height: 350px;"><canvas id="graficoPrincipalVentas"></canvas></div>
        </div>
    </div>

    {{-- Pestaña "Productos" --}}
    <div x-show="tab === 'productos'" x-transition.opacity.duration.500ms>
        <div class="report-grid">
            <div class="card">
                <div class="card-header"><h3>Ventas por Categoría</h3></div>
                <div class="card-body" style="height: 350px;"><canvas id="graficoCategorias"></canvas></div>
            </div>
            <div class="card">
                <div class="card-header"><h3>Ventas por Método de Pago</h3></div>
                <div class="card-body" style="height: 350px;"><canvas id="graficoMetodosPago"></canvas></div>
            </div>
        </div>
    </div>
</div>

{{-- Estilos para la página de Reportes --}}
<style>
.tabs-container { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; margin-bottom: 1.5rem; }
.tabs { display: flex; gap: 1.5rem; }
.tab { display:flex; align-items:center; gap: 0.5rem; padding: 0.75rem 0.25rem; font-weight: 600; color: #6b7280; text-decoration: none; border-bottom: 3px solid transparent; transition: all 0.2s; }
.tab:hover { color: #4f46e5; }
.tab.tab-active { color: #4f46e5; border-bottom-color: #4f46e5; }
.filtros-fecha { display: flex; gap: 0.75rem; align-items: center; }
.select-filtro { border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem 0.75rem; background-color: white; }
.stats-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem; }
.stat-card .stat-title { font-size: 0.875rem; font-weight: 500; color: #6b7280; }
.stat-card .stat-value { font-size: 1.75rem; font-weight: 700; color: #111827; margin-top: 0.5rem; }
.report-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1.5rem; }
</style>
@endsection

@push('scripts')
{{-- Script para renderizar TODOS los gráficos con los datos del controlador --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Gráfico Principal de Barras
        new Chart(document.getElementById('graficoPrincipalVentas'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($graficoPrincipal['labels']) !!},
                datasets: [{
                    label: 'Ventas ($)',
                    data: {!! json_encode($graficoPrincipal['valores']) !!},
                    backgroundColor: 'rgba(79, 70, 229, 0.8)',
                    borderRadius: 4
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });

        // Gráfico de Pie para Categorías
        new Chart(document.getElementById('graficoCategorias'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($ventasPorCategoria->pluck('cate_detalle')) !!},
                datasets: [{
                    data: {!! json_encode($ventasPorCategoria->pluck('total')) !!},
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });

        // Gráfico de Pie para Métodos de Pago
        new Chart(document.getElementById('graficoMetodosPago'), {
            type: 'pie',
            data: {
                labels: {!! json_encode($ventasPorMetodoPago->pluck('medo_detale')) !!},
                datasets: [{
                    data: {!! json_encode($ventasPorMetodoPago->pluck('cantidad')) !!},
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });
    });
</script>
@endpush
