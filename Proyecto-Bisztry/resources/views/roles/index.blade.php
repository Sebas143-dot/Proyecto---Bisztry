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
        
        {{-- El botón "Nuevo Rol" solo se muestra si el usuario tiene el permiso --}}
        @can('gestionar-roles')
        <div class="card-actions">
            <a href="{{ route('roles.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Rol
            </a>
        </div>
        @endcan
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre del Rol</th>
                        <th>Nº de Usuarios</th>
                        <th>Guard</th>
                        
                        {{-- La columna de "Acciones" solo se muestra si el usuario tiene permiso --}}
                        @can('gestionar-roles')
                            <th class="text-right">Acciones</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                    <tr>
                        <td class="font-bold">{{ $role->name }}</td>
                        <td>{{ $role->users_count }}</td>
                        <td><span class="badge info">{{ $role->guard_name }}</span></td>
                        
                        {{-- Los botones de acción solo se muestran si el usuario tiene permiso --}}
                        @can('gestionar-roles')
                        <td class="text-right">
                            <div class="actions-buttons">
                                {{-- Botón Editar --}}
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn-icon warning" title="Editar Rol">
                                    <i class="fas fa-edit"></i>
                                </a>

                                {{-- Botón Eliminar (se protege el formulario completo) --}}
                                {{-- No se puede eliminar el rol Super-Admin para evitar bloqueos --}}
                                @if($role->name != 'Super-Admin')
                                <form id="delete-form-{{ $role->id }}" action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-icon danger" title="Eliminar Rol"
                                            onclick="confirmDelete({{ $role->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                        @endcan
                    </tr>
                    @empty
                    <tr>
                        {{-- Ajustamos el colspan para que la tabla no se descuadre --}}
                        <td colspan="@can('gestionar-roles') 4 @else 3 @endcan" class="text-center">No hay roles creados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.actions-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
}
.btn-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    text-decoration: none;
    color: white;
    border: none;
    cursor: pointer;
}
.btn-icon.warning {
    background-color: #f59e0b; /* Usando un color directamente */
}
.btn-icon.danger {
    background-color: #ef4444; /* Usando un color directamente */
}
.badge.info { 
    background-color: #dbeafe; 
    color: #1e40af; 
}
</style>

@can('gestionar-roles')
<script>
    function confirmDelete(roleId) {
        if (confirm('¿Estás seguro de que deseas eliminar este rol? Esta acción no se puede deshacer.')) {
            document.getElementById('delete-form-' + roleId).submit();
        }
    }
</script>
@endcan
@endsection
