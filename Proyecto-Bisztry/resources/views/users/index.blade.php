@extends('layouts.app')

@section('title', 'Gestión de Usuarios')
@section('page-title', 'Usuarios del Sistema')
@section('page-description', 'Administra las cuentas de usuario y sus roles.')
{{-- Bloque para mostrar mensajes de éxito (AHORA CON BOTÓN DE CIERRE) --}}
@if (session('success'))
    <div x-data="{ show: true }" 
         x-init="setTimeout(() => show = false, 5000)" 
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 flex justify-between items-center shadow-md rounded-lg" 
         role="alert">

        <p class="font-medium">{{ session('success') }}</p>

        <button @click="show = false" class="text-green-500 hover:text-green-700 focus:outline-none">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
@endif

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