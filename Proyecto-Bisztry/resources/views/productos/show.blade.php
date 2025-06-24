@extends('layouts.app')

@section('title', 'Detalle del Producto')
@section('page-title', 'Detalle de Producto')
@section('page-description', $producto->prod_nombre)

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h3>{{ $producto->prod_nombre }}</h3>
            <p>Categoría: {{ $producto->categoria->cate_detalle ?? 'N/A' }}</p>
        </div>
        <div class="card-actions">
            <a href="{{ route('productos.edit', $producto) }}" class="btn btn-outline"><i class="fas fa-edit"></i> Editar Producto Base</a>
            <a href="{{ route('productos.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Volver a la Lista</a>
        </div>
    </div>
</div>

<div class="card" style="margin-top: 2rem;">
    <div class="card-header">
        <h3>Variantes de Inventario (SKUs)</h3>
        <div class="card-actions">
            <a href="{{ route('productos.variantes.create', $producto) }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nueva Variante
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Color</th>
                        <th>Talla</th>
                        <th>Precio Venta</th>
                        <th>Stock Actual</th>
                        <th>Stock Mínimo</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($producto->variantes as $variante)
                    <tr>
                        <td class="font-bold">{{ $variante->sku ?? 'N/A' }}</td>
                        <td>{{ $variante->color->col_detalle ?? 'N/A' }}</td>
                        <td>{{ $variante->talla->tall_detalle ?? 'N/A' }}</td>
                        <td>${{ number_format($variante->var_precio, 2) }}</td>
                        <td>
                            @if($variante->var_stok_actual <= $variante->var_stock_min && $variante->var_stok_actual > 0)
                                <span style="color: #f59e0b; font-weight:bold;">{{ $variante->var_stok_actual }} (Bajo)</span>
                            @elseif($variante->var_stok_actual == 0)
                                <span style="color: #ef4444; font-weight:bold;">{{ $variante->var_stok_actual }} (Agotado)</span>
                            @else
                                {{ $variante->var_stok_actual }}
                            @endif
                        </td>
                        <td>{{ $variante->var_stock_min }}</td>
                        <td class="text-right">
                             <div class="actions-buttons">
                                <a href="{{ route('variantes.edit', $variante) }}" class="btn-icon warning" title="Editar Variante">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('variantes.destroy', $variante) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres eliminar esta variante?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon danger" title="Eliminar Variante"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Este producto aún no tiene variantes de inventario (SKUs).</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<style>
.actions-buttons { display: flex; justify-content: flex-end; gap: 0.5rem; }
.btn-icon { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50%; text-decoration: none; color: white; border: none; cursor: pointer;}
.btn-icon.warning { background-color: #f59e0b; }
.btn-icon.danger { background-color: #ef4444; }
</style>
@endsection