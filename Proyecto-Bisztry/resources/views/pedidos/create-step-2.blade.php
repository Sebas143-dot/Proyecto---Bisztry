@extends('layouts.app')

@section('title', 'Nuevo Pedido - Paso 2')
@section('page-title', 'Asistente de Nuevo Pedido')
@section('page-description', 'Añade productos al carrito de compra.')

@section('content')

{{-- INDICADOR DE PROGRESO VISUAL --}}
<div class="step-indicator">
    <div class="step step-complete">
        <a href="{{ route('pedidos.create.step1') }}" class="step-icon" title="Volver al Paso 1"><i class="fas fa-check"></i></a>
        <div class="step-label">1. Cliente Seleccionado</div>
    </div>
    <div class="step-connector"></div>
    <div class="step step-active">
        <div class="step-icon"><i class="fas fa-shopping-cart"></i></div>
        <div class="step-label">2. Añadir Productos</div>
    </div>
    <div class="step-connector"></div>
    <div class="step">
        <div class="step-icon"><i class="fas fa-receipt"></i></div>
        <div class="step-label">3. Finalizar Pedido</div>
    </div>
</div>

{{-- GRID PRINCIPAL: CATÁLOGO A LA IZQUIERDA, CARRITO A LA DERECHA --}}
<div class="pedido-grid">
    <div class="col-span-2">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @forelse($variantes as $variante)
            <div class="card product-card">
                <div class="card-body">
                    <h4 class="product-title">{{ $variante->producto->prod_nombre }}</h4>
                    <p class="product-details">{{ $variante->talla->tall_detalle }}, {{ $variante->color->col_detalle }}</p>
                    <p class="product-stock">Stock: {{ $variante->var_stok_actual }}</p>
                    <p class="product-price">${{ number_format($variante->var_precio, 2) }}</p>
                    
                    <form action="{{ route('pedidos.cart.add') }}" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="variante_id" value="{{ $variante->var_id }}">
                        <div class="flex items-center gap-2">
                            <input type="number" name="cantidad" value="1" min="1" max="{{ $variante->var_stok_actual }}" class="form-input-sm">
                            <button type="submit" class="btn btn-primary btn-sm flex-grow" title="Añadir al carrito">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-span-full">
                <div class="card"><div class="card-body empty-state"><i class="fas fa-box-open"></i><h3>¡Sin Inventario!</h3><p>No hay productos con stock disponible para añadir a un pedido.</p></div></div>
            </div>
            @endforelse
        </div>
    </div>

    <div class="col-span-1">
        @include('pedidos.partials.carrito')
    </div>
</div>

{{-- Estilos para esta página --}}
<style>
.step-indicator { display: flex; align-items: center; justify-content: center; width: 100%; margin-bottom: 2rem; }
.step { display: flex; flex-direction: column; align-items: center; text-align: center; width: 150px; }
.step-icon {
    width: 50px; height: 50px; border-radius: 50%;
    background-color: var(--border-color); color: var(--text-secondary);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem; transition: var(--transition-fast); text-decoration: none;
}
.step-label { font-size: 0.8rem; font-weight: 500; margin-top: 0.5rem; color: var(--text-secondary); }
.step-connector { flex-grow: 1; height: 2px; background-color: var(--border-color); }
.step.step-active .step-icon { background-color: var(--primary-color); color: var(--text-on-primary); }
.step.step-active .step-label { color: var(--text-primary); font-weight: 600; }
.step.step-complete .step-icon { border: 2px solid var(--primary-color); background: none; color: var(--primary-color); }
.step.step-complete .step-label { color: var(--text-primary); }

.pedido-grid { display: grid; grid-template-columns: 2.5fr 1fr; gap: 1.5rem; align-items: start; }
.col-span-2 { grid-column: span 2; }
.col-span-1 { grid-column: span 1; }
.col-span-full { grid-column: 1 / -1; }
.product-card { text-align: center; transition: var(--transition-fast); }
.product-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-md); }
.product-title { font-weight: 600; font-size: 1rem; }
.product-details { color: var(--text-secondary); font-size: 0.85rem; }
.product-stock { font-size: 0.8rem; background: var(--bg-color); padding: 0.2rem 0.5rem; border-radius: 4px; display:inline-block; margin-top: 0.5rem;}
.product-price { font-size: 1.25rem; font-weight: 700; margin-top: 0.5rem; color: var(--primary-color); }
.form-input-sm { width: 70px; text-align: center; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); }
.btn-sm { padding: 0.5rem 1rem; font-size: 0.8rem; }
</style>
@endsection
