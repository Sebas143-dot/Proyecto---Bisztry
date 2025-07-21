@extends('layouts.app')

@section('title', 'Nuevo Producto')
@section('page-title', 'Crear Nuevo Producto')
@section('page-description', 'Define la información básica y la categoría de tu nuevo producto.')

@section('content')
{{-- MODIFICADO: Se añade un :class dinámico que reaccionará a los cambios --}}
<div class="create-layout" x-data="{ infoCardVisible: true }" :class="{ 'sidebar-hidden': !infoCardVisible }">

    {{-- COLUMNA IZQUIERDA: FORMULARIO PRINCIPAL --}}
    <div class="form-column">
        <div class="card">
            <div class="card-header">
                <div class="card-header-content">
                    <h3><i class="fas fa-box-open text-primary mr-3"></i>Información del Producto</h3>
                    <p class="text-secondary">Los campos marcados con * son obligatorios.</p>
                </div>
                {{-- NUEVO: Botón para mostrar/ocultar la columna de consejos --}}
                <button type="button" @click="infoCardVisible = !infoCardVisible" class="toggle-info-btn">
                    <span x-show="infoCardVisible" class="flex items-center"><i class="fas fa-eye-slash mr-2"></i> Ocultar Consejos</span>
                    <span x-show="!infoCardVisible" class="flex items-center"><i class="fas fa-eye mr-2"></i> Mostrar Consejos</span>
                </button>
            </div>
            <div class="card-body">
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <div class="alert-icon-wrapper"><i class="fas fa-exclamation-triangle"></i></div>
                        <div class="alert-content">
                            <h4 class="alert-title">¡Ups! Revisa tu información</h4>
                            <ul class="error-list">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('productos.store') }}" method="POST">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="prod_nombre">Nombre del Producto *</label>
                            <input type="text" id="prod_nombre" name="prod_nombre" class="form-control" value="{{ old('prod_nombre') }}" required placeholder="Ej: Camiseta Oversize Clásica">
                        </div>
                        <div class="form-group">
                            <label for="cate_id">Categoría *</label>
                            <select id="cate_id" name="cate_id" class="form-control" required>
                                <option value="" disabled {{ old('cate_id') ? '' : 'selected' }}>-- Seleccione una categoría --</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->cate_id }}" {{ old('cate_id') == $categoria->cate_id ? 'selected' : '' }}>
                                        {{ $categoria->cate_detalle }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="prod_estado">Estado Inicial *</label>
                            <select id="prod_estado" name="prod_estado" class="form-control" required>
                                <option value="1" {{ old('prod_estado', '1') == '1' ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ old('prod_estado') == '0' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('productos.index') }}" class="btn btn-outline">
                            <i class="fas fa-times mr-2"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i> Guardar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- COLUMNA DERECHA: TARJETA DE INFORMACIÓN --}}
    <div class="info-column" x-show="infoCardVisible" x-transition.opacity.duration.300ms>
        <div class="info-card-sticky">
            <div class="info-card">
                <div class="info-card-icon-wrapper"><i class="fas fa-tags"></i></div>
                <h4 class="info-card-title">Primeros Pasos</h4>
                <p class="info-card-text">
                    Estás creando la base de un nuevo artículo. Una vez guardado, podrás añadir variantes como tallas, colores y precios específicos.
                </p>
                <div class="info-card-highlight">
                    <strong>Siguiente paso:</strong> Añadir variantes y gestionar el stock.
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* === Paleta y Variables Base === */
:root {
    --primary-color: #3b82f6; --primary-dark: #2563eb;
    --danger-color: #ef4444; --danger-bg: #fee2e2; --danger-border: #fca5a5;
    --info-color: #8b5cf6; --info-bg: #f5f3ff;
    --bg-main: #f9fafb; --bg-card: #ffffff;
    --border-color: #e5e7eb; --text-primary: #1f2937;
    --text-secondary: #6b7280; --text-on-primary: #ffffff;
    --radius-lg: 0.75rem;
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --transition-fast: all 0.2s ease-in-out;
    --transition-smooth: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); /* Transición más suave */
}

/* === Layout de 2 Columnas Dinámico === */
.create-layout {
    display: grid;
    gap: 2rem;
    max-width: 80rem; margin: 0 auto;
    /* Por defecto, dos columnas */
    grid-template-columns: minmax(0, 2fr) minmax(0, 1fr);
    transition: var(--transition-smooth);
}
/* NUEVO: Estado cuando la barra lateral está oculta */
.create-layout.sidebar-hidden {
    grid-template-columns: minmax(0, 1fr) 0px;
}
/* Ocultar la columna de información en pantallas pequeñas */
@media (max-width: 1024px) {
    .create-layout { grid-template-columns: 1fr; }
    .info-column { display: none; }
    .toggle-info-btn { display: none !important; } /* Ocultar el botón también */
}

/* === Estilos de Tarjeta y Formulario === */
.form-column { transition: var(--transition-smooth); }
.card { background-color: var(--bg-card); border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid var(--border-color); }
.card-header {
    display: flex; justify-content: space-between; align-items: flex-start;
    padding: 1.5rem; border-bottom: 1px solid var(--border-color);
    position: relative; /* Para el botón */
}
.card-header h3 { font-size: 1.25rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; margin-bottom: 0.25rem; }
.card-header p { margin: 0; color: var(--text-secondary); }
.card-body { padding: 2rem; }
.mr-2 { margin-right: 0.5rem; }
.mr-3 { margin-right: 0.75rem; }
.flex { display: flex; }
.items-center { align-items: center; }

/* NUEVO: Estilo para el botón de mostrar/ocultar */
.toggle-info-btn {
    background-color: var(--bg-main); border: 1px solid var(--border-color);
    color: var(--text-secondary); padding: 0.5rem 1rem;
    border-radius: var(--radius-lg); font-weight: 600; font-size: 0.875rem;
    cursor: pointer; transition: var(--transition-fast);
}
.toggle-info-btn:hover { background-color: var(--border-color); color: var(--text-primary); }

/* Alerta de Errores */
.alert { display: flex; gap: 1rem; padding: 1rem; border-radius: var(--radius-lg); margin-bottom: 2rem; }
.alert-danger { background-color: var(--danger-bg); border: 1px solid var(--danger-border); color: #991b1b; }
.alert-icon-wrapper { font-size: 1.5rem; }
.alert-title { font-weight: 700; color: #b91c1c; margin: 0 0 0.5rem 0; }
.error-list { margin: 0; padding-left: 1.25rem; }
.error-list li { margin-bottom: 0.25rem; }

/* Grid y Grupos de Formulario */
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.75rem; }
.form-group { display: flex; flex-direction: column; }
.form-group.full-width { grid-column: 1 / -1; }
.form-group label { font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem; }
.form-control {
    width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: var(--radius-lg);
    background-color: var(--bg-main); font-size: 1rem; transition: var(--transition-fast);
}
.form-control:focus {
    outline: none; background-color: var(--bg-card); border-color: var(--primary-color);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary-color) 20%, transparent);
}

/* Acciones del Formulario */
.form-actions { display: flex; gap: 1rem; justify-content: flex-end; border-top: 1px solid var(--border-color); padding-top: 2rem; margin-top: 2rem; }
.btn { display: inline-flex; align-items: center; justify-content: center; font-weight: 600; padding: 0.75rem 1.5rem; border-radius: var(--radius-lg); text-decoration: none; transition: var(--transition-fast); cursor: pointer; border: 1px solid transparent; }
.btn-primary { background-color: var(--primary-color); color: var(--text-on-primary); }
.btn-primary:hover { background-color: var(--primary-dark); transform: translateY(-2px); box-shadow: var(--shadow-md); }
.btn-outline { color: var(--text-secondary); background-color: transparent; border-color: var(--border-color); }
.btn-outline:hover { background-color: var(--border-color); color: var(--text-primary); }

/* Columna de Información */
.info-column { overflow: hidden; } /* Evita desbordamiento durante la animación */
.info-column .info-card-sticky { position: sticky; top: 2rem; }
.info-card {
    background-color: var(--info-bg); border-radius: var(--radius-lg);
    border: 1px solid color-mix(in srgb, var(--info-color) 30%, transparent);
    padding: 2rem; text-align: center;
}
.info-card-icon-wrapper { width: 60px; height: 60px; margin: 0 auto 1rem; border-radius: 50%; background-color: var(--bg-card); display: flex; align-items: center; justify-content: center; color: var(--info-color); font-size: 1.75rem; }
.info-card-title { font-weight: 700; font-size: 1.125rem; color: var(--text-primary); margin-bottom: 0.5rem; }
.info-card-text { color: var(--text-secondary); margin-bottom: 1.5rem; line-height: 1.6; }
.info-card-highlight { background-color: color-mix(in srgb, var(--info-color) 15%, transparent); padding: 0.75rem; border-radius: var(--radius-lg); font-size: 0.875rem; }

/* Responsividad para el grid del formulario */
@media (max-width: 768px) {
    .form-grid { grid-template-columns: 1fr; }
}
</style>
@endsection