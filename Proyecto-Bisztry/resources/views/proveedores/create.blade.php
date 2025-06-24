@extends('layouts.app')

@section('title', 'Nuevo Proveedor')
@section('page-title', 'Crear Nuevo Proveedor')
@section('page-description', 'Registra un nuevo proveedor en el sistema.')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Formulario de Proveedor</h3>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('proveedores.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="prov_ruc">RUC *</label>
                    <input type="text" id="prov_ruc" name="prov_ruc" value="{{ old('prov_ruc') }}" required maxlength="14" pattern="\d{13,14}" title="El RUC debe tener 13 o 14 dígitos.">
                </div>
                <div class="form-group">
                    <label for="prov_nombre">Nombre del Proveedor *</label>
                    <input type="text" id="prov_nombre" name="prov_nombre" value="{{ old('prov_nombre') }}" required>
                </div>
                <div class="form-group">
                    <label for="prov_contacto">Nombre del Contacto</label>
                    <input type="text" id="prov_contacto" name="prov_contacto" value="{{ old('prov_contacto') }}">
                </div>
                <div class="form-group">
                    <label for="prov_telefono">Teléfono</label>
                    <input type="tel" id="prov_telefono" name="prov_telefono" value="{{ old('prov_telefono') }}" maxlength="10">
                </div>
                <div class="form-group full-width">
                    <label for="prov_email">Email</label>
                    <input type="email" id="prov_email" name="prov_email" value="{{ old('prov_email') }}">
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Proveedor</button>
                <a href="{{ route('proveedores.index') }}" class="btn btn-outline"><i class="fas fa-times"></i> Cancelar</a>
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