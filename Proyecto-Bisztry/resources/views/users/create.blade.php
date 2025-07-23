@extends('layouts.app')

@section('title', 'Nuevo Usuario')
@section('page-title', 'Crear Nuevo Usuario')
@section('page-description', 'Completa los datos para crear una nueva cuenta.')

@section('content')
<div class="card">
    <div class="card-header"><h3>Formulario de Usuario</h3></div>
    <div class="card-body">

        @if ($errors->any())
            <div class="alert alert-danger mb-4" role="alert">
                <h4 class="alert-title">Hay algunos problemas con tu formulario:</h4>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Nombre Completo *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="password">Contraseña *</label>
                    <input type="password" id="password" name="password" required>
                    @error('password')<span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar Contraseña *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                </div>

                <div class="form-group full-width">
                    <label for="roles">Asignar Roles *</label>
                    <select id="roles" name="roles[]" multiple required>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ (is_array(old('roles')) && in_array($role->name, old('roles'))) ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('roles')<span class="text-danger">{{ $message }}</span>@enderror
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
.alert.alert-danger { background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; padding: 1rem; border-radius: 0.25rem; }
.alert-title { font-weight: bold; }
.form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; }
.form-group { margin-bottom: 1rem; display: flex; flex-direction: column; }
.form-group.full-width { grid-column: 1 / -1; }
.text-danger { font-size: 0.75rem; color: var(--danger-color); margin-top: 0.25rem; }
</style>
@endsection