@extends('layouts.app')

@section('title', 'Gestión de Usuarios')
@section('page-title', 'Usuarios del Sistema')
@section('page-description', 'Administra las cuentas de usuario y sus roles.')

@section('content')
<div class="card">
    <div class="card-header">
        <div><h3>Listado de Usuarios</h3></div>
        <div class="card-actions">
            <a href="{{ route('roles.index') }}" class="btn btn-outline"><i class="fas fa-shield-alt"></i> Gestionar Roles</a>
            <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fas fa-user-plus"></i> Nuevo Usuario</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr><th>Nombre</th><th>Email</th><th>Rol Asignado</th><th class="text-right">Acciones</th></tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="font-bold">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @forelse($user->getRoleNames() as $rol)
                                <span class="badge primary">{{ $rol }}</span>
                            @empty
                                <span class="badge">Sin Rol</span>
                            @endforelse
                        </td>
                        <td class="text-right">
                            <div class="actions-buttons">
                                <a href="{{ route('users.edit', $user) }}" class="btn-icon warning" title="Editar Usuario"><i class="fas fa-edit"></i></a>
                                @if(!($user->hasRole('Super-Admin')))
                                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar a este usuario?');" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon danger" title="Eliminar Usuario"><i class="fas fa-trash"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center">No hay usuarios registrados.</td></tr>
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
.btn-icon.danger { background-color: var(--danger-color); }
</style>
@endsection