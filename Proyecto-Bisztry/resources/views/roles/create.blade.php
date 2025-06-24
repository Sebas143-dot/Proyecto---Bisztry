@extends('layouts.app')

@section('title', 'Nuevo Rol')
@section('page-title', 'Crear Nuevo Rol')
@section('page-description', 'Asigna un nombre y permisos al nuevo rol.')

@section('content')
<div class="card">
    <div class="card-header"><h3>Formulario de Rol</h3></div>
    <div class="card-body">
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nombre del Rol *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
            
            <div class="form-group">
                <label>Permisos para este Rol</label>
                <div class="permissions-grid">
                    @forelse ($permissions as $permission)
                    <div class="permission-item">
                        <input type="checkbox" name="permissions[]" id="perm_{{ $permission->id }}" value="{{ $permission->name }}">
                        <label for="perm_{{ $permission->id }}">{{ $permission->name }}</label>
                    </div>
                    @empty
                    <p>No hay permisos definidos. Ejecuta el Seeder de permisos.</p>
                    @endforelse
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Rol</button>
                <a href="{{ route('roles.index') }}" class="btn btn-outline"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>
</div>
<style>
.permissions-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; }
.permission-item { display: flex; align-items: center; gap: 0.5rem; background: var(--bg-color); padding: 0.75rem; border-radius: var(--radius-md); }
.permission-item input { width: 1rem; height: 1rem; }
.text-danger { font-size: 0.75rem; color: var(--danger-color); margin-top: 0.25rem; }
</style>
@endsection