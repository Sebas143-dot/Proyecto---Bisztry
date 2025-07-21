@extends('layouts.app')

@section('title', 'Categorías')
@section('page-title', 'Categorías de Productos')
@section('page-description', 'Gestiona las categorías que organizan tus productos.')

@section('content')
{{-- Se inicializa Alpine.js para gestionar el modal de eliminación --}}
<div x-data="{ modalOpen: false, deleteAction: '' }">
    <div class="category-layout">

        {{-- COLUMNA IZQUIERDA: LISTA DE CATEGORÍAS --}}
        <div class="card list-card">
            <div class="card-header">
                <h3><i class="fas fa-stream text-primary mr-3"></i>Lista de Categorías Existentes</h3>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre de la Categoría</th>
                                <th>Nº de Productos</th>
                                <th class="text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categorias as $categoria)
                            <tr>
                                <td><span class="id-badge">{{ $categoria->cate_id }}</span></td>
                                <td class="font-bold">{{ $categoria->cate_detalle }}</td>
                                <td>
                                    <span class="product-count-badge">
                                        <i class="fas fa-box-open mr-2"></i>{{ $categoria->productos_count }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    {{-- El botón ahora abre el modal en lugar de enviar el formulario directamente --}}
                                    <button type="button" @click="modalOpen = true; deleteAction = '{{ route('categorias.destroy', $categoria) }}'" class="btn-icon danger" title="Eliminar Categoría">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="empty-cell">
                                    <div class="empty-state">
                                        <i class="fas fa-folder-open"></i>
                                        <p>Aún no has creado ninguna categoría.</p>
                                        <span>Usa el formulario de la derecha para empezar.</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA: FORMULARIO PARA NUEVA CATEGORÍA --}}
        <div class="sidebar-form">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-plus-circle text-primary mr-3"></i>Añadir Nueva Categoría</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('categorias.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="cate_detalle">Nombre de la Categoría *</label>
                            <input type="text" id="cate_detalle" name="cate_detalle" class="form-control" required placeholder="Ej: Camisetas">
                             @error('cate_detalle')<span class="text-danger-inline">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary w-full">
                                <i class="fas fa-save mr-2"></i> Guardar Categoría
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- NUEVO: Modal de Confirmación de Eliminación --}}
    <div x-show="modalOpen" class="modal-backdrop" x-cloak>
        <div class="modal-content" @click.away="modalOpen = false" x-show="modalOpen" x-transition>
            <div class="modal-header">
                <h3 class="modal-title">Confirmar Eliminación</h3>
                <button @click="modalOpen = false" class="modal-close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-icon-danger"><i class="fas fa-exclamation-triangle"></i></div>
                <p>¿Estás seguro de que deseas eliminar esta categoría?</p>
                <span class="text-secondary">Esta acción no se puede deshacer.</span>
            </div>
            <div class="modal-footer">
                <button type="button" @click="modalOpen = false" class="btn btn-outline">Cancelar</button>
                <form :action="deleteAction" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Sí, eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* === Paleta y Variables Base === */
:root {
    --primary-color: #3b82f6; --primary-dark: #2563eb;
    --danger-color: #ef4444; --danger-dark: #dc2626; --danger-bg: #fee2e2;
    --bg-main: #f9fafb; --bg-card: #ffffff;
    --border-color: #e5e7eb; --text-primary: #1f2937;
    --text-secondary: #6b7280; --text-on-primary: #ffffff;
    --radius-lg: 0.75rem;
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --transition-fast: all 0.2s ease-in-out;
}
[x-cloak] { display: none !important; }

/* === Layout Principal === */
.category-layout {
    display: grid; grid-template-columns: 1fr;
    gap: 2rem; max-width: 80rem; margin: 0 auto;
}
@media (min-width: 1024px) {
    .category-layout { grid-template-columns: minmax(0, 2fr) minmax(0, 1fr); }
}

/* === Estilos de Tarjeta === */
.card { background-color: var(--bg-card); border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid var(--border-color); }
.card-header { padding: 1.5rem; border-bottom: 1px solid var(--border-color); }
.card-header h3 { font-size: 1.25rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; }
.card.list-card .card-body { padding: 0; }
.card .card-body { padding: 2rem; }
.mr-2 { margin-right: 0.5rem; }
.mr-3 { margin-right: 0.75rem; }
.w-full { width: 100%; }

/* === Tabla Moderna === */
.table-container { overflow-x: auto; }
.table-modern { width: 100%; border-collapse: collapse; }
.table-modern th, .table-modern td { padding: 1.25rem 1.5rem; text-align: left; }
.table-modern thead {
    background-color: var(--bg-main);
    border-bottom: 2px solid var(--border-color);
}
.table-modern th {
    color: var(--text-secondary); font-size: 0.875rem;
    font-weight: 600; text-transform: uppercase;
}
.table-modern tbody tr { border-bottom: 1px solid var(--border-color); }
.table-modern tbody tr:last-child { border-bottom: none; }
.table-modern tbody tr:hover { background-color: color-mix(in srgb, var(--primary-color) 5%, transparent); }
.font-bold { font-weight: 600; }
.text-right { text-align: right; }
.id-badge {
    background-color: var(--border-color); color: var(--text-secondary);
    font-weight: 700; font-size: 0.8rem;
    padding: 0.25rem 0.5rem; border-radius: 999px;
}
.product-count-badge {
    background-color: color-mix(in srgb, var(--primary-color) 15%, transparent);
    color: var(--primary-dark);
    font-weight: 600; padding: 0.4rem 0.8rem; border-radius: var(--radius-lg);
}
.empty-cell { padding: 3rem 1.5rem; }
.empty-state { text-align: center; color: var(--text-secondary); }
.empty-state i { font-size: 3rem; margin-bottom: 1rem; opacity: 0.5; }
.empty-state p { font-weight: 600; font-size: 1.125rem; margin: 0; }

/* === Formulario de la Barra Lateral === */
.sidebar-form .card { position: sticky; top: 2rem; }
.form-group label { font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem; display: block; }
.form-control {
    width: 100%; padding: 0.75rem 1rem;
    border: 1px solid var(--border-color); border-radius: var(--radius-lg);
    background-color: var(--bg-main); font-size: 1rem; transition: var(--transition-fast);
}
.form-control:focus {
    outline: none; background-color: var(--bg-card);
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary-color) 20%, transparent);
}
.text-danger-inline { font-size: 0.8rem; color: var(--danger-color); margin-top: 0.5rem; }
.form-actions { margin-top: 1.5rem; }

/* === Botones === */
.btn { display: inline-flex; align-items: center; justify-content: center; font-weight: 600; padding: 0.75rem 1.5rem; border-radius: var(--radius-lg); text-decoration: none; transition: var(--transition-fast); cursor: pointer; border: 1px solid transparent; }
.btn-primary { background-color: var(--primary-color); color: var(--text-on-primary); }
.btn-primary:hover { background-color: var(--primary-dark); transform: translateY(-2px); box-shadow: var(--shadow-md); }
.btn-outline { color: var(--text-secondary); background-color: transparent; border-color: var(--border-color); }
.btn-outline:hover { background-color: var(--border-color); color: var(--text-primary); }
.btn-danger { background-color: var(--danger-color); color: var(--text-on-primary); }
.btn-danger:hover { background-color: var(--danger-dark); }
.btn-icon {
    background: none; border: none; cursor: pointer;
    width: 40px; height: 40px; border-radius: 50%;
    display: inline-flex; align-items: center; justify-content: center;
    transition: var(--transition-fast);
}
.btn-icon.danger { color: var(--danger-color); }
.btn-icon.danger:hover { background-color: var(--danger-bg); }

/* === Modal de Confirmación === */
.modal-backdrop {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background-color: rgba(17, 24, 39, 0.6);
    display: flex; align-items: center; justify-content: center;
    z-index: 50; padding: 1rem;
}
.modal-content {
    background-color: var(--bg-card); border-radius: var(--radius-lg);
    box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.25);
    width: 100%; max-width: 450px;
}
.modal-header { display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); }
.modal-title { font-size: 1.125rem; font-weight: 600; }
.modal-close-btn { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-secondary); }
.modal-body { padding: 2rem; text-align: center; }
.modal-icon-danger {
    width: 60px; height: 60px; margin: 0 auto 1.5rem;
    border-radius: 50%; background-color: var(--danger-bg);
    color: var(--danger-color); display: flex; align-items: center; justify-content: center;
    font-size: 1.75rem;
}
.modal-body p { font-size: 1.125rem; font-weight: 500; margin: 0 0 0.5rem 0; }
.modal-footer { display: flex; gap: 1rem; justify-content: flex-end; padding: 1.25rem 1.5rem; background-color: var(--bg-main); border-top: 1px solid var(--border-color); border-radius: 0 0 var(--radius-lg) var(--radius-lg); }
</style>
@endsection