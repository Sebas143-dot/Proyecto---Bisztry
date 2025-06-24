@extends('layouts.app')

@section('title', 'Nuevo Proveedor')
@section('page-title', 'Crear Nuevo Proveedor')
@section('page-description', 'Registra un nuevo proveedor en el sistema.')

@section('content')
<div class="card">
    <div class="card-header"><h3>Formulario de Proveedor</h3></div>
    <div class="card-body">
        <form action="{{ route('proveedores.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="prov_ruc">RUC *</label>
                    <input type="text" id="prov_ruc" name="prov_ruc" value="{{ old('prov_ruc') }}" required maxlength="14">
                    @error('prov_ruc')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="prov_nombre">Nombre del Proveedor *</label>
                    <input type="text" id="prov_nombre" name="prov_nombre" value="{{ old('prov_nombre') }}" required>
                    @error('prov_nombre')<span class="text-danger">{{ $message }}</span>@enderror
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
                     @error('prov_email')<span class="text-danger">{{ $message }}</span>@enderror
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
.form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; }
.form-group { margin-bottom: 1rem; display: flex; flex-direction: column; }
.form-group.full-width { grid-column: 1 / -1; }
.form-group .text-danger { font-size: 0.75rem; color: var(--danger-color); margin-top: 0.25rem; }
</style>
@endsection