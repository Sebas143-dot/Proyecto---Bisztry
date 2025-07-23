@extends('layouts.app')

@section('title', 'Editar Pedido')
@section('page-title', 'Editor de Pedido')
@section('page-description', 'Modifica los productos, el estado o los detalles del pedido #PED-' . $pedido->pedi_id)

@section('content')
{{-- El div principal ya no necesita escuchar eventos, Alpine lo controlará todo desde adentro --}}
<div x-data="gestorPedidosEditar()">
    <form action="{{ route('pedidos.update', $pedido) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="carrito" :value="JSON.stringify(carrito)">

        <div class="edit-grid">
            <div class="card">
                <div class="card-header">
                    <h3><span class="step-number">1</span> Modificar Productos del Pedido</h3>
                </div>
                <div class="card-body">
                    <div class="add-product-form">
                        <div class="form-group flex-grow">
                            <label for="buscador-variantes">Buscar producto para añadir</label>
                            <div wire:ignore>
                                {{-- MODIFICADO: Añadimos 'x-ref' para que Alpine pueda encontrar este elemento --}}
                                <select id="buscador-variantes" x-ref="buscador" style="width: 100%;"><option></option></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>&nbsp;</label> 
                            <button type="button" @click="confirmarAnadirProducto()" :disabled="!productoSeleccionado" class="btn btn-secondary w-full">
                                <i class="fas fa-plus mr-2"></i> Añadir al Pedido
                            </button>
                        </div>
                    </div>
                    
                    <hr class="separator">

                    <div class="cart-items-container">
                        <template x-if="carrito.length === 0">
                            <div class="empty-cart-message">
                                <i class="fas fa-exclamation-circle fa-3x"></i>
                                <p class="font-bold mt-2">El pedido no puede quedar vacío</p>
                                <p class="text-secondary">Añade al menos un producto para poder guardar.</p>
                            </div>
                        </template>
                        
                        <template x-for="item in carrito" :key="item.var_id">
                            <div class="cart-item">
                                <div class="item-details">
                                    <p class="item-name" x-text="item.nombre"></p>
                                    <p class="item-price">Precio Unitario: <span x-text="`$${item.precio.toFixed(2)}`"></span></p>
                                </div>
                                <div class="item-controls">
                                    <div class="quantity-stepper">
                                        <button type="button" @click="decrementar(item)" class="stepper-btn">&minus;</button>
                                        <input type="number" :max="item.stock_max" min="1" x-model.number="item.cantidad" @input="validarStock(item)" class="quantity-input">
                                        <button type="button" @click="incrementar(item)" class="stepper-btn">&plus;</button>
                                    </div>
                                    <div class="item-subtotal">
                                        <strong x-text="`$${(item.cantidad * item.precio).toFixed(2)}`"></strong>
                                    </div>
                                    <button type="button" @click="quitarDelCarrito(item.var_id)" class="btn-remove" title="Quitar producto">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="sticky-card">
                <div class="card">
                    <div class="card-header">
                        <h3><span class="step-number">2</span> Actualizar Estado y Guardar</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="esta_cod">Estado del Pedido *</label>
                            <select id="esta_cod" name="esta_cod" class="form-control" required>
                                @foreach($estados as $estado)
                                    <option value="{{ $estado->esta_cod }}" @if($pedido->esta_cod == $estado->esta_cod) selected @endif>
                                        {{-- INICIO DE LA CORRECCIÓN --}}
                                        {{-- Se corrigió el nombre de la columna de 'esta__detalle' a 'esta_detalle' --}}
                                        {{ $estado->esta_detalle }}
                                        {{-- FIN DE LA CORRECCIÓN --}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="totals-section">
                             <div class="total-line">
                                 <span>Nuevo Subtotal Productos</span>
                                 <strong class="text-lg" x-text="`$${subtotal.toFixed(2)}`"></strong>
                             </div>
                        </div>

                        <div class="action-buttons">
                            <button type="submit" class="btn btn-primary w-full" :disabled="carrito.length === 0">
                                <i class="fas fa-save mr-2"></i> Guardar Cambios
                            </button>
                            <a href="{{ route('pedidos.show', $pedido) }}" class="btn btn-outline w-full">
                                <i class="fas fa-times mr-2"></i> Cancelar Edición
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
:root { --primary-color: #3b82f6; --primary-dark: #2563eb; --secondary-color: #6b7280; --secondary-dark: #4b5563; --danger-color: #ef4444; --danger-dark: #dc2626; --bg-main: #f9fafb; --bg-card: #ffffff; --bg-subtle: #f3f4f6; --border-color: #e5e7eb; --border-focus: var(--primary-color); --text-primary: #1f2937; --text-secondary: #6b7280; --text-on-primary: #ffffff; --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05); --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1); --radius-md: 0.5rem; --transition-fast: all 0.2s ease-in-out; }
.edit-grid { display: grid; grid-template-columns: 2.5fr 1.5fr; gap: 2rem; align-items: start; }
.card { background-color: var(--bg-card); border-radius: var(--radius-md); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm); }
.card-header { padding: 1rem 1.5rem; background-color: var(--bg-subtle); border-bottom: 1px solid var(--border-color); }
.card-header h3 { font-size: 1.125rem; font-weight: 600; color: var(--text-primary); display: flex; align-items: center; gap: 0.75rem; }
.step-number { background-color: var(--primary-color); color: var(--text-on-primary); border-radius: 50%; width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; }
.card-body { padding: 1.5rem; }
.sticky-card { position: sticky; top: 2rem; }
.separator { border: 0; height: 1px; background-color: var(--border-color); margin: 1.5rem 0; }
.add-product-form { display: flex; gap: 1rem; align-items: flex-end; }
.flex-grow { flex-grow: 1; }
.cart-items-container { display: flex; flex-direction: column; gap: 1rem; min-height: 150px; }
.cart-item { display: flex; justify-content: space-between; align-items: center; background: var(--bg-main); padding: 1rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); transition: var(--transition-fast); }
.cart-item:hover { border-color: color-mix(in srgb, var(--primary-color) 40%, transparent); box-shadow: var(--shadow-sm); }
.item-details { flex-grow: 1; }
.item-name { font-weight: 600; color: var(--text-primary); }
.item-price { font-size: 0.875rem; color: var(--text-secondary); }
.item-controls { display: flex; align-items: center; gap: 1rem; }
.item-subtotal { font-weight: 700; color: var(--text-primary); font-size: 1.1rem; min-width: 80px; text-align: right; }
.empty-cart-message { text-align: center; padding: 2rem; color: var(--text-secondary); border: 2px dashed var(--border-color); border-radius: var(--radius-md); }
.quantity-stepper { display: flex; align-items: center; border: 1px solid var(--border-color); border-radius: var(--radius-md); }
.stepper-btn { background: var(--bg-subtle); border: none; color: var(--text-primary); padding: 0.5rem 0.75rem; cursor: pointer; font-size: 1rem; transition: background-color 0.2s; }
.stepper-btn:hover { background-color: var(--border-color); }
.stepper-btn:first-child { border-right: 1px solid var(--border-color); border-radius: 0.4rem 0 0 0.4rem; }
.stepper-btn:last-child { border-left: 1px solid var(--border-color); border-radius: 0 0.4rem 0.4rem 0; }
.quantity-input { width: 60px; text-align: center; border: none; padding: 0.5rem; font-size: 1rem; font-weight: 500; -moz-appearance: textfield; }
.quantity-input::-webkit-outer-spin-button, .quantity-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
.btn-remove { background: transparent; border: none; color: var(--text-secondary); cursor: pointer; padding: 0.5rem; border-radius: 50%; width: 36px; height: 36px; }
.btn-remove:hover { background-color: var(--danger-color); color: var(--text-on-primary); }
.form-group { display: flex; flex-direction: column; }
.form-group label { font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem; }
.form-control { width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); transition: var(--transition-fast); font-size: 1rem; }
.form-control:focus, .select2-container--default.select2-container--open .select2-selection--single { outline: 2px solid transparent; border-color: var(--border-focus) !important; box-shadow: 0 0 0 2px color-mix(in srgb, var(--border-focus) 25%, transparent); }
.select2-container .select2-selection--single { height: calc(1.5em + 1.5rem + 2px); padding: 0.75rem 1rem; }
.select2-container .select2-selection--single .select2-selection__rendered { padding-left: 0; line-height: 1.5rem; }
.select2-container .select2-selection--single .select2-selection__arrow { top: 50%; transform: translateY(-50%); }
.totals-section { margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px dashed var(--border-color); display: flex; flex-direction: column; gap: 0.75rem; }
.total-line { display: flex; justify-content: space-between; align-items: center; font-size: 1rem; }
.total-line strong { color: var(--text-primary); font-weight: 700; }
.text-lg { font-size: 1.25rem; }
.action-buttons { margin-top: 2rem; display: flex; flex-direction: column; gap: 0.75rem; }
.btn { display: flex; align-items: center; justify-content: center; font-weight: 600; padding: 0.8rem 1rem; border-radius: var(--radius-md); text-decoration: none; transition: var(--transition-fast); font-size: 1rem; cursor: pointer; border: 1px solid transparent; }
.btn:disabled { background-color: var(--border-color) !important; color: var(--text-secondary) !important; cursor: not-allowed; transform: none !important; box-shadow: none !important; }
.btn-primary { background-color: var(--primary-color); color: var(--text-on-primary); }
.btn-primary:not(:disabled):hover { background-color: var(--primary-dark); transform: translateY(-2px); box-shadow: var(--shadow-md); }
.btn-secondary { background-color: var(--secondary-color); color: var(--text-on-primary); }
.btn-secondary:not(:disabled):hover { background-color: var(--secondary-dark); }
.btn-outline { background-color: transparent; color: var(--primary-color); border-color: var(--primary-color); }
.btn-outline:hover { background-color: color-mix(in srgb, var(--primary-color) 10%, transparent); }
.w-full { width: 100%; }
.mr-2 { margin-right: 0.5rem; }
@media (max-width: 1024px) { .edit-grid { grid-template-columns: 1fr; } .item-controls { gap: 0.5rem; flex-wrap: wrap; justify-content: flex-end; } .item-subtotal { min-width: auto; } }
</style>
@endpush

@push('scripts')
<script>
    function gestorPedidosEditar() {
        return {
            // == ESTADO DEL COMPONENTE ==
            carrito: {!! json_encode($pedido->detalles->map(function($detalle) {
                return [
                    'var_id' => $detalle->var_id,
                    'nombre' => $detalle->variante->producto->prod_nombre . ' (' . $detalle->variante->talla->tall_detalle . ', ' . $detalle->variante->color->col_detalle . ')',
                    'cantidad' => $detalle->cantidad,
                    'precio' => (float)$detalle->variante->var_precio,
                    'stock_max' => (int)$detalle->variante->var_stok_actual + (int)$detalle->cantidad
                ];
            })) !!},
            
            productoSeleccionado: null,

            // == INICIALIZACIÓN ==
            init() {
                const component = this;

                $(this.$refs.buscador).select2({
                    placeholder: 'Buscar por nombre o código de producto...',
                    data: {!! json_encode($variantesParaSelect2) !!},
                    allowClear: true
                });

                $(this.$refs.buscador).on('select2:select', function(e) {
                    component.seleccionarProducto(e.params.data.datos_completos);
                });

                $(this.$refs.buscador).on('select2:unselect', function(e) {
                    component.seleccionarProducto(null);
                });
            },

            // == MÉTODOS Y LÓGICA ==
            seleccionarProducto(datos) {
                this.productoSeleccionado = datos;
            },
            confirmarAnadirProducto() {
                if (!this.productoSeleccionado) return;
                this.anadirAlCarrito(this.productoSeleccionado);
                this.productoSeleccionado = null;
                $(this.$refs.buscador).val(null).trigger('change');
            },
            anadirAlCarrito(variante) {
                const itemExistente = this.carrito.find(item => item.var_id === variante.var_id);
                if (itemExistente) {
                    if (itemExistente.cantidad < itemExistente.stock_max) itemExistente.cantidad++;
                } else {
                    this.carrito.push({
                        var_id: variante.var_id, nombre: variante.nombre, cantidad: 1,
                        precio: parseFloat(variante.precio), stock_max: parseInt(variante.stock_max)
                    });
                }
            },
            quitarDelCarrito(varianteId) { this.carrito = this.carrito.filter(item => item.var_id !== varianteId); },
            incrementar(item) { if (item.cantidad < item.stock_max) item.cantidad++; },
            decrementar(item) { if (item.cantidad > 1) item.cantidad--; },
            validarStock(item) {
                if (isNaN(item.cantidad) || item.cantidad < 1) item.cantidad = 1;
                if (item.cantidad > item.stock_max) item.cantidad = item.stock_max;
            },
            get subtotal() {
                if (!this.carrito || this.carrito.length === 0) return 0;
                return this.carrito.reduce((acc, item) => acc + (parseInt(item.cantidad) * parseFloat(item.precio)), 0);
            },
        }
    }
</script>
@endpush
