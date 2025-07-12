@extends('layouts.app')

@section('title', 'Editar Rol')
@section('page-title', 'Editar Rol')
@section('page-description', 'Modifica el nombre y los permisos asignados a este rol.')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-8">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold mb-4">Formulario de Edición</h3>
        
        <form action="{{ route('roles.update', $role->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Campo: Nombre del Rol --}}
            <div>
                <label for="name" class="block font-medium text-sm text-gray-700">Nombre del Rol *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $role->name) }}" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('name')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
            </div>

            {{-- Campo: Permisos agrupados --}}
            <div class="space-y-6">
                @forelse ($permissions as $group => $perms)
                <div class="border border-gray-200 rounded-md p-4">
                    <h4 class="text-lg font-semibold mb-3 capitalize">{{ $group }}</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach ($perms as $permission)
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="permissions[]" id="perm_{{ $permission->id }}" value="{{ $permission->name }}"
                                class="text-indigo-600 rounded focus:ring-indigo-500"
                                {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">
                                {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @empty
                    <p class="text-red-500">No hay permisos definidos. Ejecuta el Seeder de permisos.</p>
                @endforelse
            </div>

            {{-- Botones de acción --}}
            <div class="flex space-x-4 mt-6">
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 shadow">
                    <i class="fas fa-save"></i> Actualizar Rol
                </button>
                <a href="{{ route('roles.index') }}"
                    class="px-6 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 shadow">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
