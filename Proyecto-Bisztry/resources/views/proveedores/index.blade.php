@extends('layouts.app')

@section('title', 'Proveedores')
@section('page-title', 'Lista de Proveedores')
@section('page-description', 'Gestiona la información de tus proveedores.')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h3>Todos los Proveedores</h3>
            <p>Se encontraron {{ $proveedores->total() }} proveedores.</p>
        </div>
        <div class="card-actions">
            <a href="{{ route('proveedores.create') }}" class="btn btn-primary">
                <i class="fas fa-truck"></i>
                Nuevo Proveedor
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>RUC</th>
                        <th>Nombre del Proveedor</th>
                        <th>Contacto</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proveedores as $proveedor)
                    <tr>
                        <td class="font-bold">{{ $proveedor->prov_ruc }}</td>
                        <td>{{ $proveedor->prov_nombre }}</td>
                        <td>{{ $proveedor->prov_contacto }}</td>
                        <td>{{ $proveedor->prov_telefono }}</td>
                        <td>{{ $proveedor->prov_email }}</td>
                        <td class="text-right">
                            <div class="actions-buttons">
                                <a href="{{ route('proveedores.show', $proveedor) }}" class="btn-icon info" title="Ver Detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('proveedores.edit', $proveedor) }}" class="btn-icon warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('proveedores.destroy', $proveedor) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este proveedor?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon danger" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No hay proveedores registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-container">
           {{ $proveedores->links() }}
        </div>
    </div>
</div>
<style>
.actions-buttons { display: flex; justify-content: flex-end; gap: 0.5rem; }
.btn-icon { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50%; text-decoration: none; color: white; border: none; cursor: pointer;}
.btn-icon.info { background-color: #3b82f6; }
.btn-icon.warning { background-color: #f59e0b; }
.btn-icon.danger { background-color: #ef4444; }
.pagination-container { margin-top: 1.5rem; }
</style>
@endsection