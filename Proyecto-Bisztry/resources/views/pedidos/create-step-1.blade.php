@extends('layouts.app')

@section('title', 'Nuevo Pedido - Paso 1')
@section('page-title', 'Asistente de Nuevo Pedido')
@section('page-description', 'Sigue los pasos para registrar una nueva venta.')

@section('content')

{{-- INDICADOR DE PROGRESO VISUAL --}}
<div class="step-indicator">
    <div class="step step-active"><div class="step-icon"><i class="fas fa-user-check"></i></div><div class="step-label">1. Seleccionar Cliente</div></div>
    <div class="step-connector"></div>
    <div class="step"><div class="step-icon"><i class="fas fa-shopping-cart"></i></div><div class="step-label">2. Añadir Productos</div></div>
    <div class="step-connector"></div>
    <div class="step"><div class="step-icon"><i class="fas fa-receipt"></i></div><div class="step-label">3. Finalizar Pedido</div></div>
</div>

{{-- FORMULARIO MEJORADO Y CENTRADO --}}
<div class="card max-w-3xl mx-auto mt-6">
    <div class="card-header"><h3><i class="fas fa-user-check text-primary mr-2"></i>Paso 1: ¿Para quién es el pedido?</h3></div>
    <div class="card-body">
        <form action="{{ route('pedidos.create.step1.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="clie_id">Buscar y seleccionar cliente *</label>
                <div wire:ignore>
                    <select id="clie_id" name="clie_id" required style="width: 100%;">
                        <option></option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->clie_id }}">{{ $cliente->clie_nombre }} {{ $cliente->clie_apellido }} ({{ $cliente->clie_email }})</option>
                        @endforeach
                    </select>
                </div>
                @error('clie_id') <span class="text-danger mt-1">{{ $message }}</span> @enderror
            </div>
            
            {{-- ======================================================= --}}
            {{--         INICIO DE LA MEJORA DEL BOTÓN                   --}}
            {{-- ======================================================= --}}
            <div class="nuevo-cliente-banner">
                <p>¿El cliente no está en la lista?</p>
                <a href="{{ route('clientes.create', ['redirect_to' => route('pedidos.create.step1')]) }}" class="btn btn-secondary">
                    <i class="fas fa-user-plus"></i> Crear Nuevo Cliente
                </a>
            </div>
            {{-- ======================================================= --}}
            {{--              FIN DE LA MEJORA                           --}}
            {{-- ======================================================= --}}
            
            <div class="form-actions mt-6">
                <button type="submit" class="btn btn-primary btn-lg">Siguiente: Añadir Productos <i class="fas fa-arrow-right ml-2"></i></button>
            </div>
        </form>
    </div>
</div>

<style>
/* Estilos del Indicador de Pasos y Generales */
.step-indicator { display: flex; align-items: center; justify-content: center; width: 100%; margin-bottom: 2rem; }
.step { display: flex; flex-direction: column; align-items: center; text-align: center; width: 150px; }
.step-icon { width: 50px; height: 50px; border-radius: 50%; background-color: var(--border-color); color: var(--text-secondary); display: flex; align-items: center; justify-content: center; font-size: 1.25rem; transition: var(--transition-fast); }
.step-label { font-size: 0.8rem; font-weight: 500; margin-top: 0.5rem; color: var(--text-secondary); }
.step-connector { flex-grow: 1; height: 2px; background-color: var(--border-color); }
.step.step-active .step-icon { background-color: var(--primary-color); color: var(--text-on-primary); }
.step.step-active .step-label { color: var(--text-primary); font-weight: 600; }
.max-w-3xl { max-width: 48rem; } .mx-auto { margin-left: auto; margin-right: auto; }
.mt-6 { margin-top: 1.5rem; } .mb-4 { margin-bottom: 1rem; } .mr-2 { margin-right: 0.5rem; }
.text-primary { color: var(--primary-color); } .text-danger { font-size: 0.75rem; color: var(--danger-color); }
.btn-lg { padding: 0.8rem 1.75rem; font-size: 1rem; }
.form-actions { justify-content: flex-end; border-top: none; }

/* --- NUEVOS ESTILOS PARA EL BANNER Y BOTÓN --- */
.nuevo-cliente-banner {
    margin-top: 1.5rem;
    padding: 1rem 1.5rem;
    background-color: var(--bg-color);
    border: 1px dashed var(--border-color);
    border-radius: var(--radius-lg);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.nuevo-cliente-banner p {
    margin: 0;
    font-weight: 500;
    color: var(--text-secondary);
}
.btn-secondary {
    background-color: var(--surface-color);
    color: var(--primary-color);
    border-color: var(--primary-color);
    font-weight: 600;
}
.btn-secondary:hover {
    background-color: var(--primary-color);
    color: var(--text-on-primary);
}
</style>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#clie_id').select2({
            placeholder: "-- Escribe para buscar un cliente --",
            allowClear: true
        });
        const newClientId = '{{ $newClientId ?? '' }}';
        if (newClientId) {
            $('#clie_id').val(newClientId).trigger('change');
        }
    });
</script>
@endpush
