@extends('layouts.app')

@section('title', 'Productos')
@section('page-title', 'Inventario de Productos')
@section('page-description', 'Gestiona los productos base de tu inventario.')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h3>Todos los Productos</h3>
            <p>Se encontraron {{ $productos->total() }} productos.</p>
        </div>
        
        {{-- Los botones de acción en el header solo se muestran si el usuario tiene el permiso --}}
        @can('gestionar-productos')
        <div class="card-actions">
            <a href="{{ route('categorias.index') }}" class="btn btn-outline">
                <i class="fas fa-tags"></i> Gestionar Categorías
            </a>
            <a href="{{ route('productos.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Producto
            </a>
        </div>
        @endcan
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Producto</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th>Creado en</th>
                        
                        {{-- La columna de "Acciones" solo se muestra si el usuario tiene permiso --}}
                        @can('gestionar-productos')
                            <th class="text-right">Acciones</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $producto)
                    <tr>
                        <td class="font-bold">{{ $producto->prod_cod }}</td>
                        <td>{{ $producto->prod_nombre }}</td>
                        <td>{{ $producto->categoria->cate_detalle ?? 'Sin categoría' }}</td>
                        <td>
                            @if($producto->prod_estado)
                                <span class="badge success">Activo</span>
                            @else
                                <span class="badge danger">Inactivo</span>
                            @endif
                        </td>
                        <td>{{ $producto->created_at->format('d/m/Y') }}</td>
                        
                        {{-- Los botones de acción solo se muestran si el usuario tiene permiso --}}
                        @can('gestionar-productos')
                        <td class="text-right">
                            <div class="actions-buttons">
                                <a href="{{ route('productos.show', $producto) }}" class="btn-icon info" title="Ver Variantes y Detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('productos.edit', $producto) }}" class="btn-icon warning" title="Editar Producto">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('productos.destroy', $producto) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este producto? Solo se puede si no tiene variantes.');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon danger" title="Eliminar Producto">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endcan
                    </tr>
                    @empty
                    <tr>
                        {{-- Ajustamos el colspan para que la tabla no se descuadre --}}
                        <td colspan="@can('gestionar-productos') 6 @else 5 @endcan" class="text-center">Aún no has creado ningún producto.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-container">
            {{ $productos->links() }}
        </div>
    </div>
</div>
<style>
.actions-buttons { display: flex; justify-content: flex-end; gap: 0.5rem; }
.btn-icon { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50%; text-decoration: none; color: white; border: none; cursor: pointer;}
.btn-icon.info { background-color: #3b82f6; }
.btn-icon.warning { background-color: #f59e0b; }
.btn-icon.danger { background-color: #ef4444; }
.pagination-container { margin-top: 1.5rem; }
</style>
@endsection
