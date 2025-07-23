@extends('layouts.app')

@section('title', 'Pedidos')
@section('page-title', 'Gestión de Pedidos')
@section('page-description', 'Visualiza y administra todos los pedidos de tus clientes.')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h3>Listado de Pedidos</h3>
            <p>Se encontraron {{ $pedidos->total() }} pedidos en total.</p>
        </div>
        
        {{-- El botón "Nuevo Pedido" solo se muestra si el usuario tiene el permiso para crear --}}
        @can('crear-pedidos')
        <div class="card-actions">
            <a href="{{ route('pedidos.create.step1') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Nuevo Pedido
            </a>
        </div>
        @endcan
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('pedidos.index') }}" class="flex flex-col sm:flex-row items-center gap-3 mb-4">
            {{-- Campo de búsqueda --}}
            <input 
                type="text" 
                name="search" 
                placeholder="Buscar por ID o nombre de cliente..." 
                value="{{ request('search') }}"
                class="w-full sm:w-64 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            >

            {{-- Selector de estado --}}
            <select 
                name="estado_filtro" 
                onchange="this.form.submit()"
                class="w-full sm:w-52 px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white focus:ring-indigo-500 focus:border-indigo-500"
            >
                <option value="todos">Todos los estados</option>
                @foreach($estados as $estado)
                    <option value="{{ $estado->esta_cod }}" {{ request('estado_filtro') == $estado->esta_cod ? 'selected' : '' }}>
                        {{ $estado->esta_detalle }}
                    </option>
                @endforeach
            </select>

            {{-- Botón Limpiar --}}
            <a href="{{ route('pedidos.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300 shadow">
                <i class="fas fa-times mr-2"></i> Limpiar
            </a>
        </form>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th class="text-right">Total</th>
                        
                        {{-- La columna de "Acciones" solo se muestra si el usuario tiene permiso para gestionar --}}
                        @can('gestionar-pedidos')
                            <th class="text-right">Acciones</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse($pedidos as $pedido)
                    <tr>
                        <td class="font-bold">PED-{{ $pedido->pedi_id }}</td>
                        <td>{{ $pedido->cliente->clie_nombre ?? 'N/A' }} {{ $pedido->cliente->clie_apellido ?? '' }}</td>
                        <td>{{ \Carbon\Carbon::parse($pedido->pedi_fecha)->format('d/m/Y') }}</td>
                        <td>
                            @php
                                $estadoClass = '';
                                switch($pedido->esta_cod) {
                                    case 'PEN': $estadoClass = 'warning'; break;
                                    case 'PRO': $estadoClass = 'info'; break;
                                    case 'ENV': $estadoClass = 'primary'; break;
                                    case 'ENT': $estadoClass = 'success'; break;
                                    case 'CAN': $estadoClass = 'danger'; break;
                                }
                            @endphp
                            <span class="badge {{ $estadoClass }}">{{ $pedido->estado->esta_detalle ?? 'Sin estado' }}</span>
                        </td>
                        <td class="text-right font-bold">${{ number_format($pedido->pedi_total + $pedido->pedi_costo_envio, 2) }}</td>
                        
                        {{-- Los botones de acción solo se muestran si el usuario tiene permiso para gestionar --}}
                        @can('gestionar-pedidos')
                        <td class="text-right">
                            <div class="actions-buttons">
                                <a href="{{ route('pedidos.show', $pedido) }}" class="btn-icon info" title="Ver Detalles del Pedido"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('pedidos.edit', $pedido) }}" class="btn-icon warning" title="Editar Productos del Pedido"><i class="fas fa-edit"></i></a>
                            </div>
                        </td>
                        @endcan
                    </tr>
                    @empty
                    <tr>
                        {{-- Ajustamos el colspan para que la tabla no se descuadre --}}
                        <td colspan="@can('gestionar-pedidos') 6 @else 5 @endcan" class="text-center">
                            <div class="empty-state">
                                <i class="fas fa-clipboard-list"></i>
                                <h3>No se encontraron pedidos</h3>
                                <p>No hay pedidos que coincidan con los filtros actuales.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-container">{{ $pedidos->appends(request()->all())->links() }}</div>
    </div>
</div>
<style>
.filters { display: flex; gap: 1rem; align-items: center; margin-bottom: 1.5rem; }
.filters .search-box { flex-grow: 1; }
.filters .search-box input, .filters .filter-group select { padding: 0.6rem; border: 1px solid #d1d5db; border-radius: 0.375rem; }
.actions-buttons { display: flex; justify-content: flex-end; gap: 0.5rem; }
.btn-icon { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50%; text-decoration: none; color: white; border: none; cursor: pointer;}
.btn-icon.info { background-color: #3b82f6; }
.btn-icon.warning { background-color: #f59e0b; }
.pagination-container { margin-top: 1.5rem; }
.badge.primary { background-color: #e0e7ff; color: #3730a3; }
/* Asegúrate de tener definidos los colores para los otros badges si no lo están ya */
.badge.warning { background-color: #fef3c7; color: #92400e; }
.badge.info { background-color: #dbeafe; color: #1e40af; }
.badge.success { background-color: #d1fae5; color: #065f46; }
.badge.danger { background-color: #fee2e2; color: #991b1b; }
</style>
@endsection
