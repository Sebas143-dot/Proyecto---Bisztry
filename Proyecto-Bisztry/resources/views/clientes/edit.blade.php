@extends('layouts.app')

@section('title', 'Editar Cliente')
@section('page-title', 'Editar Cliente')
@section('page-description', 'Actualiza la información del cliente: ' . $cliente->clie_nombre)

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Formulario de Edición</h3>
        <p>Los campos con * son obligatorios.</p>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>¡Ups!</strong> Hubo algunos problemas con tu entrada.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('clientes.update', $cliente) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="form-group">
                    <label for="clie_nombre">Nombre *</label>
                    <input type="text" id="clie_nombre" name="clie_nombre" value="{{ old('clie_nombre', $cliente->clie_nombre) }}" required>
                </div>
                <div class="form-group">
                    <label for="clie_apellido">Apellido *</label>
                    <input type="text" id="clie_apellido" name="clie_apellido" value="{{ old('clie_apellido', $cliente->clie_apellido) }}" required>
                </div>
                <div class="form-group">
                    <label for="clie_email">Email *</label>
                    <input type="email" id="clie_email" name="clie_email" value="{{ old('clie_email', $cliente->clie_email) }}" required>
                </div>
                <div class="form-group">
                    <label for="clie_telefono">Teléfono</label>
                    <input type="text" id="clie_telefono" name="clie_telefono" value="{{ old('clie_telefono', $cliente->clie_telefono) }}">
                </div>
                <div class="form-group">
                    <label for="ciud_cod">Ciudad *</label>
                    <select id="ciud_cod" name="ciud_cod" required>
                        <option value="">Seleccione una ciudad</option>
                        @foreach($ciudades as $ciudad)
                            <option value="{{ $ciudad->ciud_cod }}" {{ old('ciud_cod', $cliente->ciud_cod) == $ciudad->ciud_cod ? 'selected' : '' }}>
                                {{ $ciudad->ciud_nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="clie_fecha_nac">Fecha de Nacimiento</label>
                    <input type="date" id="clie_fecha_nac" name="clie_fecha_nac" value="{{ old('clie_fecha_nac', $cliente->clie_fecha_nac) }}">
                </div>
                <div class="form-group full-width">
                    <label for="clie_direccion">Dirección</label>
                    <textarea id="clie_direccion" name="clie_direccion" rows="3">{{ old('clie_direccion', $cliente->clie_direccion) }}</textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Actualizar Cliente</button>
                <a href="{{ route('clientes.index') }}" class="btn btn-outline"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>
</div>
<style>
.form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem; }
.form-group { display: flex; flex-direction: column; }
.form-group.full-width { grid-column: 1 / -1; }
.form-group label { margin-bottom: 0.5rem; font-weight: 600; }
.form-group input, .form-group select, .form-group textarea { padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 1rem; }
.form-actions { display: flex; gap: 1rem; justify-content: flex-end; border-top: 1px solid #e5e7eb; padding-top: 1.5rem; margin-top: 1.5rem; }
</style>
@endsection