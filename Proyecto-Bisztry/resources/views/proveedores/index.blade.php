@extends('layouts.app')

@section('title', 'Proveedores')
@section('page-title', 'Gestión de Proveedores')
@section('page-description', 'Administra todos tus proveedores desde un solo lugar.')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h3>Listado de Proveedores</h3>
            <p>Se encontraron {{ $proveedores->total() }} proveedores.</p>
        </div>
        <div class="card-actions">
            <a href="{{ route('proveedores.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Proveedor
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('proveedores.index') }}" class="filters">
            <div class="search-box"><input type="text" name="search" placeholder="Buscar por RUC, nombre o contacto..." value="{{ request('search') }}"></div>
            <button type="submit" class="btn btn-outline">Buscar</button>
            <a href="{{ route('proveedores.index') }}" class="btn btn-outline">Limpiar</a>
        </form>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>RUC</th>
                        <th>Nombre del Proveedor</th>
                        <th>Contacto</th>
                        <th>Teléfono / Email</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proveedores as $proveedor)
                    <tr>
                        <td class="font-bold">{{ $proveedor->prov_ruc }}</td>
                        <td>{{ $proveedor->prov_nombre }}</td>
                        <td>{{ $proveedor->prov_contacto }}</td>
                        <td>
                            <div>{{ $proveedor->prov_telefono }}</div>
                            <div class="text-secondary text-small">{{ $proveedor->prov_email }}</div>
                        </td>
                        <td class="text-right">
                            <div class="actions-buttons">
                                <a href="{{ route('proveedores.show', $proveedor) }}" class="btn-icon info" title="Ver Detalles"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('proveedores.edit', $proveedor) }}" class="btn-icon warning" title="Editar"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('proveedores.destroy', $proveedor) }}" method="POST" onsubmit="return confirm('¿Estás seguro?');" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon danger" title="Eliminar"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center"><div class="empty-state"><i class="fas fa-truck"></i><h3>No se encontraron proveedores</h3><p>Prueba a limpiar los filtros o añade un nuevo proveedor.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-container">{{ $proveedores->appends(request()->query())->links() }}</div>
    </div>
</div>
<style>
.filters { display: flex; gap: 1rem; align-items: center; margin-bottom: 1.5rem; }
.filters .search-box { flex-grow: 1; }
.filters .search-box input { width: 100%; }
.actions-buttons { display: flex; justify-content: flex-end; gap: 0.5rem; }
.btn-icon { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50%; text-decoration: none; color: white; border: none; cursor: pointer;}
.btn-icon.info { background-color: var(--info-color); }
.btn-icon.warning { background-color: var(--warning-color); }
.btn-icon.danger { background-color: var(--danger-color); }
.pagination-container { margin-top: 1.5rem; }
.text-small { font-size: 0.8em; }
</style>
@endsection