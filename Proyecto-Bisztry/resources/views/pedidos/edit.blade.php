@extends('layouts.app')

@section('title', 'Editar Pedido')
@section('page-title', 'Editar Pedido #PED-' . $pedido->pedi_id)
@section('page-description', 'Modifica los productos o el estado de este pedido.')

@section('content')
<div x-data="gestorPedidosEditar()">
    <form action="{{ route('pedidos.update', $pedido) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="carrito" :value="JSON.stringify(carrito)">

        <div class="pedido-grid">
            {{-- Columna Izquierda: Carrito y Buscador --}}
            <div class="col-span-2">
                <div class="card">
                    <div class="card-header"><h3>1. Editar Carrito de Compra</h3></div>
                    <div class="card-body">
                        <div class="form-group mb-4" wire:ignore>
                            <label for="buscador-variantes">Añadir más productos</label>
                            <select id="buscador-variantes" style="width: 100%;"><option></option></select>
                        </div>
                        <hr>
                        <div class="carrito-items mt-4">
                            <template x-if="carrito.length === 0"><p class="empty-carrito"><i class="fas fa-exclamation-triangle"></i><br>El carrito no puede estar vacío.</p></template>
                            <template x-for="item in carrito" :key="item.var_id">
                                <div class="carrito-item-mejorado">
                                    <div class="item-info-principal"><strong x-text="item.nombre"></strong><button type="button" @click="quitarDelCarrito(item.var_id)" class="btn-icon danger-sm"><i class="fas fa-times"></i></button></div>
                                    <div class="item-calculo">
                                        <input type="number" min="1" :max="item.stock_max" x-model.number="item.cantidad" @input="validarStock(item)"><span>x</span>
                                        <span class="precio-unitario" x-text="`$${item.precio.toFixed(2)}`"></span><span>=</span>
                                        <strong class="subtotal-linea" x-text="`$${(item.cantidad * item.precio).toFixed(2)}`"></strong>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Columna Derecha: Resumen y Guardado --}}
            <div class="col-span-1">
                <div class="card sticky-card">
                    <div class="card-header"><h3>2. Actualizar y Finalizar</h3></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="esta_cod">Cambiar estado a: *</label>
                            <select id="esta_cod" name="esta_cod" required>
                                @foreach($estados as $estado)
                                    <option value="{{ $estado->esta_cod }}" {{ $pedido->esta_cod == $estado->esta_cod ? 'selected' : '' }}>{{ $estado->esta__detalle }}</option>
                                @endforeach
                            </select>
                        </div>
                        <hr class="my-4">
                        <div class="total-line"><span>Nuevo Subtotal:</span><strong x-text="`$${subtotal.toFixed(2)}`"></strong></div>
                        <div class="form-actions mt-4">
                            <a href="{{ route('pedidos.show', $pedido) }}" class="btn btn-outline"><i class="fas fa-times"></i> Cancelar</a>
                            <button type="submit" class="btn btn-primary" :disabled="carrito.length === 0"><i class="fas fa-save"></i> Guardar Cambios</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
{{-- Estilos reutilizados --}}
<style> .pedido-grid{display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;align-items:start}.col-span-2{grid-column:span 2}.col-span-1{grid-column:span 1}.sticky-card{position:sticky;top:2rem}.carrito-items{display:flex;flex-direction:column;gap:.75rem;margin-bottom:1rem;min-height:100px}.empty-carrito{text-align:center;color:var(--text-secondary);padding:2rem 0}.carrito-item-mejorado{background:var(--bg-color);padding:.75rem;border-radius:var(--radius-md);border:1px solid var(--border-color)}.item-info-principal{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:.5rem}.item-info-principal strong{font-weight:600;font-size:.9rem}.item-calculo{display:flex;align-items:center;gap:.5rem;font-size:.9rem}.item-calculo input{width:60px;text-align:center;padding:.5rem;border:1px solid var(--border-color);border-radius:var(--radius-md)}.total-line{display:flex;justify-content:space-between;font-weight:600;font-size:1.1rem}.my-4{margin-top:1rem;margin-bottom:1rem}</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const variantesParaSelect2 = {!! json_encode($variantesParaSelect2) !!};
        $('#buscador-variantes').select2({ placeholder: 'Buscar para añadir más...', data: variantesParaSelect2, allowClear: true });
        $('#buscador-variantes').on('select2:select', e => {
            document.querySelector('[x-data]').__x.anadirAlCarrito(e.params.data.datos_completos);
            $(e.currentTarget).val(null).trigger('change');
        });
    });
    function gestorPedidosEditar() {
        return {
            carrito: {!! json_encode($pedido->detalles->map(function($detalle) {
                return [
                    'var_id' => $detalle->var_id,
                    'nombre' => $detalle->variante->producto->prod_nombre . ' (' . $detalle->variante->talla->tall_detalle . ', ' . $detalle->variante->color->col_detalle . ')',
                    'cantidad' => $detalle->cantidad,
                    'precio' => (float)$detalle->variante->var_precio,
                    'stock_max' => (int)$detalle->variante->var_stok_actual + (int)$detalle->cantidad
                ];
            })) !!},
            cantidadOriginal(varianteId) {
                const itemOriginal = {!! json_encode($pedido->detalles->keyBy('var_id')) !!};
                return itemOriginal[varianteId] ? parseInt(itemOriginal[varianteId].cantidad) : 0;
            },
            anadirAlCarrito(variante) {
                const itemExistente = this.carrito.find(item => item.var_id === variante.var_id);
                if(itemExistente) {
                    if(itemExistente.cantidad < itemExistente.stock_max) itemExistente.cantidad++;
                } else {
                    this.carrito.push({
                        var_id: variante.var_id, nombre: variante.nombre, cantidad: 1,
                        precio: parseFloat(variante.precio), stock_max: parseInt(variante.stock_max)
                    });
                }
            },
            validarStock(item) {
                if (isNaN(item.cantidad) || item.cantidad < 1) item.cantidad = 1;
                if (item.cantidad > item.stock_max) item.cantidad = item.stock_max;
            },
            quitarDelCarrito(varianteId) { this.carrito = this.carrito.filter(item => item.var_id !== varianteId); },
            get subtotal() { return this.carrito.reduce((acc, item) => acc + (parseInt(item.cantidad) * parseFloat(item.precio)), 0); },
        }
    }
</script>
@endpush
