@extends('layouts.app')

@section('title', 'Editar Cliente')
@section('page-title', 'Editor de Cliente')
@section('page-description', 'Actualiza la información para: ' . $cliente->clie_nombre . ' ' . $cliente->clie_apellido)

@section('content')
<div class="card max-w-4xl mx-auto">
    <div class="card-header">
        <h3><i class="fas fa-user-edit text-primary mr-3"></i>Formulario de Edición</h3>
        <p class="text-secondary">Los campos marcados con * son obligatorios.</p>
    </div>
    <div class="card-body">
        
        {{-- Bloque de errores mejorado --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="alert-icon-wrapper">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="alert-content">
                    <h4 class="alert-title">¡Ups! Hubo algunos problemas</h4>
                    <ul class="error-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('clientes.update', $cliente) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-grid">
                
                <div class="form-group">
                    <label for="clie_nombre">Nombre *</label>
                    <input type="text" id="clie_nombre" name="clie_nombre" class="form-control" value="{{ old('clie_nombre', $cliente->clie_nombre) }}" required placeholder="Ej: Juan">
                </div>

                <div class="form-group">
                    <label for="clie_apellido">Apellido *</label>
                    <input type="text" id="clie_apellido" name="clie_apellido" class="form-control" value="{{ old('clie_apellido', $cliente->clie_apellido) }}" required placeholder="Ej: Pérez">
                </div>
                
                {{-- Campo de email con ícono --}}
                <div class="form-group">
                    <label for="clie_email">Email *</label>
                    <div class="input-group">
                        <span class="input-group-icon"><i class="fas fa-envelope"></i></span>
                        <input type="email" id="clie_email" name="clie_email" class="form-control" value="{{ old('clie_email', $cliente->clie_email) }}" required placeholder="ejemplo@correo.com">
                    </div>
                </div>

                {{-- Campo de teléfono con ícono --}}
                <div class="form-group">
                    <label for="clie_telefono">Teléfono</label>
                    <div class="input-group">
                         <span class="input-group-icon"><i class="fas fa-phone"></i></span>
                        <input type="tel" id="clie_telefono" name="clie_telefono" class="form-control" value="{{ old('clie_telefono', $cliente->clie_telefono) }}" placeholder="Ej: 0987654321">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="ciud_cod">Ciudad *</label>
                    <select id="ciud_cod" name="ciud_cod" class="form-control" required>
                        <option value="">-- Seleccione una ciudad --</option>
                        @foreach($ciudades as $ciudad)
                            <option value="{{ $ciudad->ciud_cod }}" {{ old('ciud_cod', $cliente->ciud_cod) == $ciudad->ciud_cod ? 'selected' : '' }}>
                                {{ $ciudad->ciud_nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="clie_fecha_nac">Fecha de Nacimiento</label>
                    <input type="date" id="clie_fecha_nac" name="clie_fecha_nac" class="form-control" value="{{ old('clie_fecha_nac', $cliente->clie_fecha_nac) }}">
                </div>
                
                <div class="form-group full-width">
                    <label for="clie_direccion">Dirección</label>
                    <textarea id="clie_direccion" name="clie_direccion" class="form-control" rows="3" placeholder="Ej: Av. Principal 123 y Calle Secundaria">{{ old('clie_direccion', $cliente->clie_direccion) }}</textarea>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('clientes.index') }}" class="btn btn-outline">
                    <i class="fas fa-times mr-2"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i> Actualizar Cliente
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* === Paleta y Variables Base === */
:root {
    --primary-color: #3b82f6; --primary-dark: #2563eb;
    --danger-color: #ef4444; --danger-bg: #fee2e2; --danger-border: #fca5a5;
    --bg-card: #ffffff; --border-color: #e5e7eb;
    --text-primary: #1f2937; --text-secondary: #6b7280;
    --text-on-primary: #ffffff; --radius-lg: 0.75rem;
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --transition-fast: all 0.2s ease-in-out;
}

/* === Layout y Tarjeta === */
.max-w-4xl { max-width: 56rem; }
.mx-auto { margin-left: auto; margin-right: auto; }
.card {
    background-color: var(--bg-card); border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md); border: 1px solid var(--border-color);
}
.card-header { padding: 1.5rem; border-bottom: 1px solid var(--border-color); }
.card-header h3 { font-size: 1.25rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; margin-bottom: 0.25rem; }
.card-header p { margin: 0; color: var(--text-secondary); }
.card-body { padding: 2rem; }
.mr-2 { margin-right: 0.5rem; }
.mr-3 { margin-right: 0.75rem; }

/* === Alerta de Errores Mejorada === */
.alert {
    display: flex; gap: 1rem;
    padding: 1rem; border-radius: var(--radius-lg); margin-bottom: 2rem;
}
.alert-danger {
    background-color: var(--danger-bg);
    border: 1px solid var(--danger-border);
    color: #991b1b;
}
.alert-icon-wrapper { font-size: 1.5rem; }
.alert-title { font-weight: 700; color: #b91c1c; margin: 0 0 0.5rem 0; }
.error-list { margin: 0; padding-left: 1.25rem; }
.error-list li { margin-bottom: 0.25rem; }

/* === Grid y Grupos de Formulario === */
.form-grid {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.75rem;
}
.form-group { display: flex; flex-direction: column; }
.form-group.full-width { grid-column: 1 / -1; }
.form-group label {
    font-size: 0.875rem; font-weight: 500;
    color: var(--text-secondary); margin-bottom: 0.5rem;
}
.form-control {
    width: 100%; padding: 0.75rem 1rem;
    border: 1px solid var(--border-color); border-radius: var(--radius-lg);
    background-color: #f9fafb; font-size: 1rem; transition: var(--transition-fast);
}
.form-control:focus {
    outline: none; background-color: var(--bg-card);
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary-color) 20%, transparent);
}
/* Grupo de input con ícono */
.input-group { display: flex; }
.input-group-icon {
    display: flex; align-items: center; padding: 0 1rem;
    background-color: #e5e7eb; border: 1px solid var(--border-color);
    border-right: none; border-radius: var(--radius-lg) 0 0 var(--radius-lg);
    color: var(--text-secondary);
}
.input-group .form-control { border-radius: 0 var(--radius-lg) var(--radius-lg) 0; }
.input-group .form-control:focus { z-index: 10; position: relative; }

/* === Acciones del Formulario === */
.form-actions {
    display: flex; gap: 1rem; justify-content: flex-end;
    border-top: 1px solid var(--border-color); padding-top: 2rem; margin-top: 2rem;
}
.btn {
    display: inline-flex; align-items: center; justify-content: center;
    font-weight: 600; padding: 0.75rem 1.5rem;
    border-radius: var(--radius-lg); text-decoration: none;
    transition: var(--transition-fast); cursor: pointer; border: 1px solid transparent;
}
.btn-primary { background-color: var(--primary-color); color: var(--text-on-primary); }
.btn-primary:hover { background-color: var(--primary-dark); transform: translateY(-2px); box-shadow: var(--shadow-md); }
.btn-outline { color: var(--text-secondary); background-color: transparent; border-color: var(--border-color); }
.btn-outline:hover { background-color: var(--border-color); color: var(--text-primary); }
</style>
@endsection