@extends('layouts.app')

@section('title', 'Nuevo Producto')
@section('page-title', 'Crear Nuevo Producto')
@section('page-description', 'Define la información básica de tu nuevo producto.')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Formulario de Producto</h3>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('productos.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="prod_nombre">Nombre del Producto *</label>
                <input type="text" id="prod_nombre" name="prod_nombre" value="{{ old('prod_nombre') }}" required>
            </div>
            <div class="form-group">
                <label for="cate_id">Categoría *</label>
                <select id="cate_id" name="cate_id" required>
                    <option value="">-- Seleccione una categoría --</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->cate_id }}" {{ old('cate_id') == $categoria->cate_id ? 'selected' : '' }}>
                            {{ $categoria->cate_detalle }}
                        </option>
                    @endforeach
                </select>
            </div>
             <div class="form-group">
                <label for="prod_estado">Estado *</label>
                <select id="prod_estado" name="prod_estado" required>
                    <option value="1" selected>Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Producto</button>
                <a href="{{ route('productos.index') }}" class="btn btn-outline"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>
</div>

<style>
.form-group { margin-bottom: 1.5rem; }
.form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; }
.form-group input, .form-group select { width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 1rem; }
.form-actions { display: flex; gap: 1rem; justify-content: flex-end; border-top: 1px solid #e5e7eb; padding-top: 1.5rem; margin-top: 1.5rem; }
</style>
@endsection