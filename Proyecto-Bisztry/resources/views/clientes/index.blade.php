@extends('layouts.app')

@section('title', 'Clientes')
@section('page-title', 'Lista de Clientes')
@section('page-description', 'Gestiona y visualiza la información de tus clientes.')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h3>Todos los Clientes</h3>
            <p>Se encontraron {{ $clientes->total() }} clientes.</p>
        </div>
        <div class="card-actions">
            <a href="{{ route('clientes.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus"></i>
                Nuevo Cliente
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="filters">
            <form method="GET" action="{{ route('clientes.index') }}">
                <div class="search-box wide">
                    <input type="text" name="search" placeholder="Buscar por nombre, apellido o email..." value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-outline">
                    <i class="fas fa-search"></i>
                    Buscar
                </button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Ciudad</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $cliente)
                    <tr>
                        <td class="font-bold">{{ $cliente->clie_id }}</td>
                        <td>{{ $cliente->clie_nombre }} {{ $cliente->clie_apellido }}</td>
                        <td>{{ $cliente->clie_email }}</td>
                        <td>{{ $cliente->clie_telefono ?: 'N/A' }}</td>
                        <td>{{ $cliente->ciudad->ciud_nombre ?? 'N/A' }}</td>
                        <td class="text-right">
                            <div class="actions-buttons">
                                <a href="{{ route('clientes.show', $cliente) }}" class="btn-icon info" title="Ver Detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('clientes.edit', $cliente) }}" class="btn-icon warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar a este cliente?');" style="display:inline;">
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
                        <td colspan="6" class="text-center">No se encontraron clientes.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
           {{ $clientes->appends(request()->except('page'))->links() }}
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