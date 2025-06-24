@extends('layouts.app')

@section('title', 'Editar Proveedor')
@section('page-title', 'Editar Proveedor')
@section('page-description', 'Actualiza la información de: ' . $proveedor->prov_nombre)

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Formulario de Edición</h3>
    </div>
    <div class="card-body">
        {{-- El formulario apunta a la ruta de actualización, pasando el RUC del proveedor --}}
        <form action="{{ route('proveedores.update', $proveedor->prov_ruc) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Usamos un grid para un layout más limpio y adaptable --}}
            <div class="form-grid">

                <!-- Campo RUC (No editable) -->
                <div class="form-group">
                    <label for="prov_ruc">RUC del Proveedor (No editable)</label>
                    <input type="text" id="prov_ruc" name="prov_ruc" class="form-control-plaintext"
                           value="{{ $proveedor->prov_ruc }}" readonly>
                </div>

                <!-- Campo Nombre -->
                <div class="form-group">
                    <label for="prov_nombre">Nombre del Proveedor <span class="required">*</span></label>
                    <input type="text" id="prov_nombre" name="prov_nombre"
                           class="form-control @error('prov_nombre') is-invalid @enderror"
                           value="{{ old('prov_nombre', $proveedor->prov_nombre) }}" required>
                    @error('prov_nombre')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Campo Contacto -->
                <div class="form-group">
                    <label for="prov_contacto">Nombre del Contacto</label>
                    <input type="text" id="prov_contacto" name="prov_contacto"
                           class="form-control @error('prov_contacto') is-invalid @enderror"
                           value="{{ old('prov_contacto', $proveedor->prov_contacto) }}">
                    @error('prov_contacto')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Campo Teléfono -->
                <div class="form-group">
                    <label for="prov_telefono">Teléfono</label>
                    <input type="tel" id="prov_telefono" name="prov_telefono"
                           class="form-control @error('prov_telefono') is-invalid @enderror"
                           value="{{ old('prov_telefono', $proveedor->prov_telefono) }}" maxlength="10">
                    @error('prov_telefono')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Campo Email -->
                <div class="form-group full-width">
                    <label for="prov_email">Correo Electrónico</label>
                    <input type="email" id="prov_email" name="prov_email"
                           class="form-control @error('prov_email') is-invalid @enderror"
                           value="{{ old('prov_email', $proveedor->prov_email) }}">
                    @error('prov_email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Botones de acción del formulario --}}
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar Proveedor
                </button>
                <a href="{{ route('proveedores.index') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Cancelar y Volver
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Estilos para mejorar la apariencia del formulario. Puedes moverlos a tu archivo CSS principal. --}}
<style>
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #374151;
    }

    .form-control, .form-control-plaintext {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        outline: none;
    }
    
    .form-control-plaintext {
        background-color: #f3f4f6;
        cursor: not-allowed;
        border-color: transparent;
    }

    .is-invalid {
        border-color: #ef4444;
    }

    .invalid-feedback {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: block;
    }
    
    .required {
        color: #ef4444;
        margin-left: 2px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e5e7eb;
    }
</style>
@endsection
