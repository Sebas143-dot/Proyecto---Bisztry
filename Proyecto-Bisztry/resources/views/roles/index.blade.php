@extends('layouts.app')

@section('title', 'Roles y Permisos')
@section('page-title', 'Gestión de Roles')
@section('page-description', 'Administra los roles de usuario de la aplicación.')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h3>Listado de Roles</h3>
            <p>Define los grupos de permisos para los usuarios.</p>
        </div>
        <div class="card-actions">
            <a href="{{ route('roles.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Rol
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre del Rol</th>
                        <th>Nº de Usuarios</th>
                        <th>Guard</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                    <tr>
                        <td class="font-bold">{{ $role->name }}</td>
                        <td>{{ $role->users_count }}</td>
                        <td><span class="badge info">{{ $role->guard_name }}</span></td>
                        <td class="text-right">
                            <div class="actions-buttons">
                                <a href="#" class="btn-icon warning" title="Editar Rol"><i class="fas fa-edit"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center">No hay roles creados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<style>
.actions-buttons { display: flex; justify-content: flex-end; gap: 0.5rem; }
.btn-icon { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50%; text-decoration: none; color: white; border: none; cursor: pointer;}
.btn-icon.warning { background-color: var(--warning-color); }
</style>
@endsection