@extends('layouts.app')

@section('title', 'Nuevo Pedido - Paso 1')
@section('page-title', 'Asistente de Nuevo Pedido')
@section('page-description', 'Comencemos por identificar al cliente para esta nueva venta.')

@section('content')

{{-- INDICADOR DE PROGRESO VISUAL MODERNO --}}
<div class="step-indicator-modern">
    <div class="step-item active">
        <div class="step-counter">1</div>
        <div class="step-name">Seleccionar Cliente</div>
    </div>
    <div class="step-connector"></div>
    <div class="step-item">
        <div class="step-counter">2</div>
        <div class="step-name">Añadir Productos</div>
    </div>
    <div class="step-connector"></div>
    <div class="step-item">
        <div class="step-counter">3</div>
        <div class="step-name">Finalizar Pedido</div>
    </div>
</div>

{{-- LAYOUT DE 2 COLUMNAS CON ALPINE.JS --}}
<div class="grid-layout" x-data="{ selectedClientText: '' }">

    {{-- COLUMNA IZQUIERDA: ACCIÓN PRINCIPAL --}}
    <div class="main-column">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-user-check text-primary mr-3"></i>¿Para quién es este pedido?</h3>
            </div>
            <div class="card-body">
                <form id="step1-form" action="{{ route('pedidos.create.step1.post') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="clie_id">Busca por nombre, apellido o email del cliente *</label>
                        <div wire:ignore>
                            <select id="clie_id" name="clie_id" required style="width: 100%;">
                                <option></option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->clie_id }}" data-full-text="{{ $cliente->clie_nombre }} {{ $cliente->clie_apellido }} ({{ $cliente->clie_email }})">
                                        {{ $cliente->clie_nombre }} {{ $cliente->clie_apellido }} ({{ $cliente->clie_email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('clie_id') <span class="text-danger mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg w-full">
                            Siguiente: Añadir Productos <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- COLUMNA DERECHA: AYUDA Y FEEDBACK --}}
    <div class="sidebar-column">
        {{-- Tarjeta de feedback de selección --}}
        <div class="info-card" x-show="selectedClientText" x-transition>
             <div class="info-card-icon-wrapper">
                <i class="fas fa-check-circle"></i>
            </div>
            <h4 class="info-card-title">Cliente Seleccionado</h4>
            <p class="info-card-text" x-text="selectedClientText"></p>
        </div>

        {{-- Tarjeta de llamada a la acción para crear nuevo cliente --}}
        <div class="cta-card">
            <div class="cta-card-icon-wrapper">
                <i class="fas fa-user-plus"></i>
            </div>
            <h4 class="cta-card-title">¿Es un cliente nuevo?</h4>
            <p class="cta-card-text">Si el cliente no aparece en la lista, puedes registrarlo rápidamente y volver a este paso.</p>
            <a href="{{ route('clientes.create', ['redirect_to' => route('pedidos.create.step1')]) }}" class="btn btn-secondary w-full">
                Crear Nuevo Cliente
            </a>
        </div>
    </div>
</div>
@endsection

{{-- ✅ CORRECCIÓN APLICADA AQUÍ --}}
{{-- Se envuelve todo el bloque <style> dentro de un @push('styles') y @endpush --}}
@push('styles')
<style>
:root {
    --primary-color: #3b82f6;
    --primary-dark: #2563eb;
    --success-color: #22c55e;
    --secondary-color: #6b7280;
    --bg-main: #f9fafb;
    --bg-card: #ffffff;
    --bg-subtle: #f3f4f6;
    --border-color: #e5e7eb;
    --text-primary: #1f2937;
    --text-secondary: #6b7280;
    --text-on-primary: #ffffff;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --transition-fast: all 0.2s ease-in-out;
}

/* Indicador de progreso moderno */
.step-indicator-modern { display: flex; width: 100%; max-width: 800px; margin: 0 auto 3rem; }
.step-item { display: flex; align-items: center; gap: 0.75rem; color: var(--text-secondary); }
.step-counter { width: 40px; height: 40px; border-radius: 50%; border: 2px solid var(--border-color); display: flex; align-items: center; justify-content: center; font-weight: 700; transition: var(--transition-fast); }
.step-name { font-weight: 600; font-size: 0.9rem; }
.step-item.active .step-counter { background-color: var(--primary-color); border-color: var(--primary-color); color: var(--text-on-primary); transform: scale(1.1); }
.step-item.active .step-name { color: var(--text-primary); }
.step-connector { flex-grow: 1; height: 2px; background-color: var(--border-color); align-self: center; margin: 0 1rem; }

/* Nuevo layout de rejilla */
.grid-layout { display: grid; grid-template-columns: 1fr; gap: 2rem; }
@media (min-width: 768px) {
    .grid-layout { grid-template-columns: repeat(3, 1fr); }
    .main-column { grid-column: span 2 / span 2; }
    .sidebar-column { grid-column: span 1 / span 1; }
}

/* Estilos de tarjeta refinados */
.card { background-color: var(--bg-card); border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid var(--border-color); overflow: hidden; }
.card-header { padding: 1.5rem; border-bottom: 1px solid var(--border-color); }
.card-header h3 { font-size: 1.25rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; }
.card-body { padding: 2rem; }
.form-group { margin-bottom: 2rem; }
.form-group label { font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem; display: block; }
.form-actions { border-top: 1px solid var(--border-color); padding-top: 2rem; margin-top: 2rem; }
.w-full { width: 100%; }
.mr-3 { margin-right: 0.75rem; }
.ml-2 { margin-left: 0.5rem; }
.mt-1 { margin-top: 0.25rem; }
.text-danger { font-size: 0.8rem; color: #ef4444; }

/* Tarjetas de la barra lateral */
.info-card, .cta-card { background-color: var(--bg-card); border-radius: var(--radius-lg); border: 1px solid var(--border-color); padding: 1.5rem; text-align: center; margin-bottom: 2rem; }
.cta-card { background-color: var(--bg-subtle); }
.info-card-icon-wrapper, .cta-card-icon-wrapper { width: 60px; height: 60px; margin: 0 auto 1rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
.info-card-icon-wrapper { background-color: color-mix(in srgb, var(--success-color) 15%, transparent); color: var(--success-color); font-size: 1.75rem; }
.cta-card-icon-wrapper { background-color: color-mix(in srgb, var(--primary-color) 15%, transparent); color: var(--primary-color); font-size: 1.75rem; }
.info-card-title, .cta-card-title { font-weight: 700; font-size: 1.125rem; color: var(--text-primary); margin-bottom: 0.5rem; }
.info-card-text, .cta-card-text { color: var(--text-secondary); margin-bottom: 1.5rem; line-height: 1.6; }

/* Estilos de botones */
.btn-lg { padding: 1rem 2rem; font-size: 1rem; font-weight: 600; }
.btn { text-decoration: none; display: inline-flex; justify-content: center; align-items: center; }
.btn-primary { background-color: var(--primary-color); color: var(--text-on-primary); transition: var(--transition-fast); }
.btn-primary:hover { background-color: var(--primary-dark); transform: translateY(-2px); }
.btn-secondary { background-color: var(--bg-card); border: 2px solid var(--primary-color); color: var(--primary-color); font-weight: 600; transition: var(--transition-fast); padding: 0.75rem 1.5rem; border-radius: var(--radius-md); }
.btn-secondary:hover { background-color: var(--primary-color); color: var(--text-on-primary); }

/* Estilos personalizados para Select2 */
.select2-container--default .select2-selection--single { height: 50px !important; padding: 10px 16px !important; font-size: 1rem !important; border-radius: var(--radius-md) !important; border: 1px solid var(--border-color) !important; }
.select2-container--default .select2-selection--single .select2-selection__arrow { height: 48px !important; }
.select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 28px !important; }
.select2-dropdown { border-radius: var(--radius-md) !important; border: 1px solid var(--border-color) !important; }
.select2-search__field { border-radius: var(--radius-md) !important; }
</style>
@endpush


@push('scripts')
<script>
$(document).ready(function() {
    const $selectCliente = $('#clie_id').select2({
        placeholder: "-- Escribe para buscar un cliente --",
        allowClear: true
    });

    function updateAlpineState(selectedData) {
        let component = document.querySelector('[x-data]').__x;
        if (component) {
            component.selectedClientText = selectedData ? selectedData.text : '';
        }
    }

    $selectCliente.on('select2:select', function (e) {
        updateAlpineState(e.params.data);
    });

    $selectCliente.on('select2:unselect', function (e) {
        updateAlpineState(null);
    });

    const newClientId = '{{ $newClientId ?? '' }}';
    if (newClientId) {
        $selectCliente.val(newClientId).trigger('change');
        
        const selectedOptionData = $selectCliente.select2('data')[0];
        if (selectedOptionData) {
            updateAlpineState(selectedOptionData);
        }
    }
});
</script>
@endpush