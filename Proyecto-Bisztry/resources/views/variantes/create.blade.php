@extends('layouts.app')

@section('title', 'Nueva Variante')
@section('page-title', 'Añadir Nueva Variante')
@section('page-description', 'Para el producto: ' . $producto->prod_nombre)

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Formulario de Variante</h3>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('productos.variantes.store', $producto) }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="sku">SKU (Código de Barras, opcional)</label>
                    <input type="text" id="sku" name="sku" value="{{ old('sku') }}">
                </div>
                <div class="form-group">
                    <label for="var_precio">Precio de Venta *</label>
                    <input type="number" step="0.01" id="var_precio" name="var_precio" value="{{ old('var_precio') }}" required>
                </div>
                <div class="form-group">
                    <label for="color_id">Color *</label>
                    <select id="color_id" name="color_id" required>
                        @foreach($colores as $color)
                            <option value="{{ $color->color_id }}">{{ $color->col_detalle }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="talla_cod">Talla *</label>
                    <select id="talla_cod" name="talla_cod" required>
                        @foreach($tallas as $talla)
                            <option value="{{ $talla->talla_cod }}">{{ $talla->tall_detalle }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="var_stok_actual">Stock Inicial *</label>
                    <input type="number" id="var_stok_actual" name="var_stok_actual" value="{{ old('var_stok_actual', 0) }}" required>
                </div>
                <div class="form-group">
                    <label for="var_stock_min">Stock Mínimo *</label>
                    <input type="number" id="var_stock_min" name="var_stock_min" value="{{ old('var_stock_min', 0) }}" required>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Variante</button>
                <a href="{{ route('productos.show', $producto) }}" class="btn btn-outline"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>
</div>
<style>
.form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem; }
.form-group { display: flex; flex-direction: column; }
.form-group label { margin-bottom: 0.5rem; font-weight: 600; }
.form-group input, .form-group select { padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 1rem; }
.form-actions { display: flex; gap: 1rem; justify-content: flex-end; border-top: 1px solid #e5e7eb; padding-top: 1.5rem; margin-top: 1.5rem; }
</style>
@endsection