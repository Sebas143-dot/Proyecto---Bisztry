@extends('layouts.app')

@section('title', 'Roles y Sistema')
@section('page-title', 'Roles y Sistema')
@section('page-description', 'Administra usuarios, permisos y configuración del sistema.')

@section('content')

{{-- Navegación con pestañas --}}
<div class="tabs mb-6">
    <ul class="tab-list">
        <li><a href="#" class="tab-link active">Usuarios</a></li>
        <li><a href="#" class="tab-link disabled">Roles y permisos</a></li>
        <li><a href="#" class="tab-link disabled">Configuración</a></li>
    </ul>
</div>

{{-- Tarjeta con tabla de usuarios --}}
<div class="card shadow-sm">
    <div class="card-header d-flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold">Usuarios del sistema</h2>
            <p class="text-sm text-muted">Gestiona los usuarios que tienen acceso al sistema</p>
        </div>
        <a href="#" class="btn btn-primary">
            <i class="fas fa-user-plus mr-1"></i> Nuevo usuario
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover w-full">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Último acceso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                <span class="badge badge-role">
                                    {{ $usuario->rol ?? 'Sin rol' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-success">Activo</span>
                            </td>
                            <td>{{ $usuario->last_login ?? 'N/A' }}</td>
                            <td>
                                <button class="btn btn-sm btn-light">...</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No hay usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Paginación --}}
<div class="mt-4">
    {{ $usuarios->links() }}
</div>

{{-- Estilos personalizados --}}
<style>
.tabs {
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 1rem;
}
.tab-list {
    display: flex;
    gap: 1rem;
    list-style: none;
    padding: 0;
    margin: 0;
}
.tab-link {
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-weight: 600;
    color: #374151;
    background-color: transparent;
    text-decoration: none;
    transition: background-color 0.2s ease;
}
.tab-link:hover {
    background-color: #f3f4f6;
}
.tab-link.active {
    background-color: #e0e7ff;
    color: #3730a3;
    cursor: default;
}
.tab-link.disabled {
    color: #9ca3af;
    pointer-events: none;
    background-color: #f9fafb;
}
.badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 0.25rem;
}
.badge-role {
    background-color: #ede9fe;
    color: #6d28d9;
}
.badge-success {
    background-color: #d1fae5;
    color: #065f46;
}
.card {
    background-color: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    overflow: hidden;
}
.card-header {
    padding: 1rem;
    border-bottom: 1px solid #e5e7eb;
}
.card-body {
    padding: 1rem;
}
.table {
    width: 100%;
    border-collapse: collapse;
}
.table th, .table td {
    padding: 0.75rem;
    border-bottom: 1px solid #e5e7eb;
}
.table th {
    background-color: #f9fafb;
    font-weight: 600;
    color: #374151;
}
</style>

@endsection
