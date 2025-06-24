@extends('layouts.app')
@section('title', 'Categorías')
@section('page-title', 'Categorías de Productos')
@section('page-description', 'Gestiona las categorías de tus productos.')

@section('content')
<div class="productos-layout">
    <div class="card">
        <div class="card-header">
            <h3>Lista de Categorías</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Detalle</th>
                        <th>Nº Productos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categorias as $categoria)
                    <tr>
                        <td>{{ $categoria->cate_id }}</td>
                        <td>{{ $categoria->cate_detalle }}</td>
                        <td>{{ $categoria->productos_count }}</td>
                        <td>
                            <form action="{{ route('categorias.destroy', $categoria) }}" method="POST" onsubmit="return confirm('¿Estás seguro?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center">No hay categorías.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Nueva Categoría</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('categorias.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="cate_detalle">Nombre de la Categoría *</label>
                    <input type="text" id="cate_detalle" name="cate_detalle" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Guardar Categoría</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.productos-layout { display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; }
.btn-icon.danger { background-color: #fee2e2; color: #991b1b; border:none; width:32px; height:32px; border-radius: 50%; cursor:pointer; }
</style>
@endsection