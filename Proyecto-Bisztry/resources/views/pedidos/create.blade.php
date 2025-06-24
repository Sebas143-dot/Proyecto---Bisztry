@extends('layouts.app')

@section('title', 'Nuevo Pedido')
@section('page-title', 'Registrar Nuevo Pedido')
@section('page-description', 'Completa todos los campos para registrar una nueva venta.')

@section('content')

<div x-data="gestorPedidos()">
    <form id="pedido-form" action="{{ route('pedidos.store') }}" method="POST">
        @csrf
        <input type="hidden" name="carrito" :value="JSON.stringify(carrito)">

        <div class="pedido-grid">
            <div class="col-span-2">
                {{-- ... (La tarjeta de Datos del Pedido y la del Buscador de Productos no cambian) ... --}}
                <div class="card"><div class="card-header"><h3>1. Datos del Pedido</h3></div>
                    <div class="card-body">
                        <div class="form-grid">
                            <div class="form-group"><label for="clie_id">Cliente *</label><select id="clie_id" name="clie_id" x-model="cliente_id" required><option value="">-- Seleccionar Cliente --</option>@foreach($clientes as $cliente)<option value="{{ $cliente->clie_id }}">{{ $cliente->clie_nombre }} {{ $cliente->clie_apellido }} ({{ $cliente->clie_email }})</option>@endforeach</select></div>
                            <div class="form-group"><label for="pedi_fecha">Fecha del Pedido *</label><input type="date" id="pedi_fecha" name="pedi_fecha" x-model="fecha" required></div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><h3>2. Añadir Productos al Carrito</h3></div>
                    <div class="card-body">
                        <div class="form-group" wire:ignore>
                            <label for="buscador-variantes">Buscar producto por nombre, SKU, talla o color</label>
                            <select id="buscador-variantes" style="width: 100%;"><option></option></select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-1">
                <div class="card sticky-card">
                    <div class="card-header"><h3>3. Carrito y Finalización</h3></div>
                    <div class="card-body">
                        <div class="carrito-items">
                            <template x-if="carrito.length === 0"><p class="empty-carrito"><i class="fas fa-shopping-cart"></i><br>Añade productos desde el buscador.</p></template>
                            
                            {{-- INICIO DE LA MEJORA EN EL CARRITO --}}
                            <template x-for="item in carrito" :key="item.var_id">
                                <div class="carrito-item-mejorado">
                                    <div class="item-info-principal">
                                        <strong x-text="item.nombre"></strong>
                                        <button type="button" @click="quitarDelCarrito(item.var_id)" class="btn-icon danger-sm" title="Quitar item"><i class="fas fa-times"></i></button>
                                    </div>
                                    <div class="item-calculo">
                                        <input type="number" min="1" :max="item.stock_max" x-model.number="item.cantidad" @input="validarStock(item)">
                                        <span>x</span>
                                        <span class="precio-unitario" x-text="`$${item.precio.toFixed(2)}`"></span>
                                        <span>=</span>
                                        {{-- Este es el nuevo campo de subtotal por línea --}}
                                        <strong class="subtotal-linea" x-text="`$${(item.cantidad * item.precio).toFixed(2)}`"></strong>
                                    </div>
                                </div>
                            </template>
                             {{-- FIN DE LA MEJORA EN EL CARRITO --}}
                        </div>
                        <hr>
                        <div class="resumen-pedido">
                            <div class="form-grid-resumen">
                                <div class="form-group"><label>Método de Pago *</label><select name="meto_cod" x-model="metodo_pago_id" required>@foreach($metodosPago as $metodo)<option value="{{ $metodo->meto_cod }}">{{ $metodo->medo_detale }}</option>@endforeach</select></div>
                                <div class="form-group"><label>Servicio de Entrega *</label><select name="serv_id" x-model="servicio_entrega_id" required>@foreach($serviciosEntrega as $servicio)<option value="{{ $servicio->serv_id }}">{{ $servicio->serv_nombre }} - ${{ number_format($servicio->serv_costo, 2) }}</option>@endforeach</select></div>
                            </div>
                            <div class="form-group"><label>Costo de Envío *</label><input type="number" name="pedi_costo_envio" x-model.number="costoEnvio" step="0.01" required></div>
                            <div class="total-line"><span>Subtotal Productos:</span><span x-text="`$${subtotal.toFixed(2)}`"></span></div>
                            <div class="total-line grand-total"><strong>Total Pedido:</strong><strong x-text="`$${totalGeneral.toFixed(2)}`"></strong></div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" :disabled="isFormInvalid">
                            <i class="fas fa-check"></i> <span x-text="textoBoton"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Estilos Mejorados y específicos para esta página --}}
<style>
.pedido-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; align-items: start; }
.col-span-2, .col-span-1 { display: flex; flex-direction: column; gap: 1.5rem; }
.sticky-card { position: sticky; top: 2rem; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
.carrito-items { display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 1rem; min-height: 100px; }
.empty-carrito { text-align: center; color: var(--text-secondary); padding: 2rem 0; font-size: 0.875rem; }
.empty-carrito i { font-size: 2rem; display: block; margin-bottom: 0.5rem; }

/* Nuevos estilos para el item del carrito */
.carrito-item-mejorado { background: var(--bg-color); padding: 0.75rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); }
.item-info-principal { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem; }
.item-info-principal strong { font-weight: 600; font-size: 0.9rem; flex-grow: 1; padding-right: 0.5rem; }
.btn-icon.danger-sm { background-color: #fee2e2; color: #991b1b; border:none; width:24px; height:24px; border-radius: 50%; cursor:pointer; display: flex; align-items: center; justify-content: center; transition: var(--transition-fast); flex-shrink: 0; }
.btn-icon.danger-sm:hover { background-color: #fecaca; }
.item-calculo { display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; }
.item-calculo input { width: 60px; text-align: center; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: var(--radius-md);}
.item-calculo .precio-unitario { color: var(--text-secondary); }
.item-calculo .subtotal-linea { margin-left: auto; font-weight: 700; font-size: 1rem; }

.resumen-pedido { margin-top: 1rem; margin-bottom: 1.5rem; }
.form-grid-resumen { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; }
.total-line { display: flex; justify-content: space-between; padding: 0.5rem 0; font-weight: 500; }
.grand-total { font-size: 1.25rem; border-top: 2px solid var(--text-primary); margin-top: 0.5rem; padding-top: 0.5rem; }
.btn-block { width: 100%; justify-content: center; padding: 1rem; font-size: 1rem; transition: var(--transition-fast); }
.btn:disabled { background-color: #d1d5db; border-color: #d1d5db; color: #6b7280; cursor: not-allowed; opacity: 1; }
.select2-container .select2-selection--single { height: calc(1.5em + 1.5rem + 2px); }
.select2-container--default .select2-selection--single .select2-selection__rendered { line-height: calc(1.5em + 1.5rem); }
.select2-container--default .select2-selection--single .select2-selection__arrow { height: calc(1.5em + 1.5rem); }
</style>
@endsection

@push('scripts')
<script>
    // La parte de inicialización de Select2 no cambia
    document.addEventListener('DOMContentLoaded', function () {
        const variantesParaSelect2 = {!! json_encode($variantesParaSelect2) !!};
        $('#buscador-variantes').select2({
            placeholder: 'Escribe para buscar un producto...',
            data: variantesParaSelect2, allowClear: true
        });
        $('#buscador-variantes').on('select2:select', function (e) {
            var data = e.params.data.datos_completos;
            document.querySelector('[x-data]').__x.anadirAlCarrito(data);
            $(this).val(null).trigger('change');
        });
        $('#clie_id').select2({ placeholder: '-- Seleccionar Cliente --' }); // Hacemos que el select de cliente también sea profesional
        $('#clie_id').on('change', function(e) { document.querySelector('[x-data]').__x.cliente_id = $(this).val(); });
    });

    function gestorPedidos() {
        return {
            // --- DATOS: Las variables que controlan la página ---
            carrito: [], cliente_id: '',
            fecha: new Date().toISOString().slice(0, 10),
            costoEnvio: 0.00,
            estado_id: '{{ $estados->first()->esta_cod ?? '' }}',
            metodo_pago_id: '{{ $metodosPago->first()->meto_cod ?? '' }}',
            servicio_entrega_id: '{{ $serviciosEntrega->first()->serv_id ?? '' }}',

            // --- LÓGICA DEL CARRITO ---
            anadirAlCarrito(variante) { /* ...código sin cambios... */ },
            validarStock(item) { /* ...código sin cambios... */ },
            quitarDelCarrito(varianteId) { this.carrito = this.carrito.filter(item => item.var_id !== varianteId); },
            
            // --- CÁLCULOS DINÁMICOS: La suma que pediste ---
            get subtotal() {
                // La función reduce() suma los subtotales de cada línea del carrito.
                // Se asegura de tratar cantidad y precio como números para evitar errores.
                return this.carrito.reduce((acumulador, item) => acumulador + (parseInt(item.cantidad) * parseFloat(item.precio)), 0);
            },
            get totalGeneral() {
                return this.subtotal + parseFloat(this.costoEnvio);
            },

            // --- LÓGICA DE VALIDACIÓN DEL BOTÓN ---
            get isFormInvalid() {
                // El botón estará desactivado si alguna de estas condiciones es verdadera
                return this.carrito.length === 0 || !this.cliente_id || !this.fecha || !this.estado_id || !this.metodo_pago_id || !this.servicio_entrega_id;
            },
            get textoBoton() {
                return this.isFormInvalid ? 'Completa los campos requeridos' : 'Finalizar y Guardar Pedido';
            },

            // --- PREPARACIÓN PARA ENVIAR EL FORMULARIO ---
            prepararEnvio() {
                // Esta función no es estrictamente necesaria si el input oculto está bien configurado,
                // pero es una buena práctica para asegurar que los datos son correctos antes del envío.
                const hiddenInput = document.querySelector('input[name="carrito"]');
                hiddenInput.value = JSON.stringify(this.carrito.map(item => ({
                    var_id: item.var_id, cantidad: item.cantidad, precio: item.precio
                })));
            }
        }
    }
</script>
@endpush