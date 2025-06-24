@extends('layouts.app')

@section('title', 'Nuevo Usuario')
@section('page-title', 'Crear Nuevo Usuario')
@section('page-description', 'Completa los datos para crear una nueva cuenta.')

@section('content')
<div class="card">
    <div class="card-header"><h3>Formulario de Usuario</h3></div>
    <div class="card-body">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group"><label for="name">Nombre Completo *</label><input type="text" id="name" name="name" value="{{ old('name') }}" required>@error('name')<span class="text-danger">{{ $message }}</span>@enderror</div>
                <div class="form-group"><label for="email">Email *</label><input type="email" id="email" name="email" value="{{ old('email') }}" required>@error('email')<span class="text-danger">{{ $message }}</span>@enderror</div>
                <div class="form-group"><label for="password">Contraseña *</label><input type="password" id="password" name="password" required>@error('password')<span class="text-danger">{{ $message }}</span>@enderror</div>
                <div class="form-group"><label for="password_confirmation">Confirmar Contraseña *</label><input type="password" id="password_confirmation" name="password_confirmation" required></div>
                <div class="form-group full-width">
                    <label for="role">Asignar Rol *</label>
                    <select id="role" name="role" required>
                        <option value="">-- Seleccionar un rol --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Crear Usuario</button>
                <a href="{{ route('users.index') }}" class="btn btn-outline"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>
</div>
<style>
.form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; }
.form-group { margin-bottom: 1rem; display: flex; flex-direction: column; }
.form-group.full-width { grid-column: 1 / -1; }
.text-danger { font-size: 0.75rem; color: var(--danger-color); margin-top: 0.25rem; }
</style>
@endsection
