@extends('layouts.app')

@section('title', 'Nuevo Pedido - Confirmación')
@section('page-title', 'Asistente de Nuevo Pedido')
@section('page-description', 'Paso 3: Confirma los detalles y finaliza el pedido.')

@section('content')

{{-- INDICADOR DE PROGRESO VISUAL --}}
<div class="step-indicator">
    <div class="step step-complete">
        <a href="{{ route('pedidos.create.step1') }}" class="step-icon" title="Volver al Paso 1">
            <i class="fas fa-user-check"></i>
        </a>
        <div class="step-label">1. Cliente</div>
    </div>
    <div class="step-connector"></div>
    <div class="step step-complete">
        <a href="{{ route('pedidos.create.step2') }}" class="step-icon" title="Volver al Paso 2">
            <i class="fas fa-shopping-cart"></i>
        </a>
        <div class="step-label">2. Productos</div>
    </div>
    <div class="step-connector"></div>
    <div class="step step-active">
        <div class="step-icon"><i class="fas fa-receipt"></i></div>
        <div class="step-label">3. Confirmación</div>
    </div>
</div>

<form action="{{ route('pedidos.store') }}" method="POST">
    @csrf
    <div class="confirmation-grid">
        {{-- COLUMNA IZQUIERDA: RESUMEN DETALLADO DEL PEDIDO --}}
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-box-open mr-2"></i>Resumen del Pedido</h3>
            </div>
            <div class="card-body">
                <div class="customer-summary">
                    <p class="customer-name">{{ $cliente->clie_nombre }} {{ $cliente->clie_apellido }}</p>
                    <p class="customer-email">{{ $cliente->clie_email }}</p>
                </div>

                <div class="table-container">
                    <table class="table-items">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th class="text-center">Cant.</th>
                                <th class="text-right">P. Unit.</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $subtotalProductos = 0; @endphp
                            @foreach($pedidoSession['carrito'] as $item)
                                @php $subtotalProductos += $item['subtotal']; @endphp
                                <tr>
                                    <td>{{ $item['nombre'] }}</td>
                                    <td class="text-center">{{ $item['cantidad'] }}</td>
                                    <td class="text-right">${{ number_format($item['precio'], 2) }}</td>
                                    <td class="text-right font-bold">${{ number_format($item['subtotal'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA: DATOS FINALES Y ACCIONES --}}
        <div class="sticky-card">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-cogs mr-2"></i>Detalles Finales</h3>
                </div>
                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group full-span">
                            <label for="pedi_fecha">Fecha del Pedido *</label>
                            <input type="date" id="pedi_fecha" name="pedi_fecha" value="{{ date('Y-m-d') }}" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="esta_cod">Estado Inicial *</label>
                            <select id="esta_cod" name="esta_cod" class="form-control" required>
                                @foreach($estados as $estado)
                                    {{-- CORRECCIÓN 1: Se cambió 'esta__detalle' por 'esta_detalle' --}}
                                    <option value="{{ $estado->esta_cod }}">{{ $estado->esta_detalle }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="meto_cod">Método de Pago *</label>
                            <select id="meto_cod" name="meto_cod" class="form-control" required>
                                @foreach($metodosPago as $metodo)
                                     {{-- CORRECCIÓN 2: Se cambió 'medo_detale' por 'meto_detalle' (asumiendo este nombre de columna) --}}
                                    <option value="{{ $metodo->meto_cod }}">{{ $metodo->meto_detalle }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group full-span">
                             <label for="serv_id">Servicio de Entrega *</label>
                             <select id="serv_id" name="serv_id" class="form-control" required>
                                 @foreach($serviciosEntrega as $servicio)
                                 <option value="{{ $servicio->serv_id }}">{{ $servicio->serv_nombre }}</option>
                                 @endforeach
                             </select>
                        </div>
                         <div class="form-group full-span">
                            <label for="pedi_costo_envio">Costo de Envío *</label>
                            <div class="input-with-icon">
                                <span>$</span>
                                <input type="number" id="pedi_costo_envio" name="pedi_costo_envio" value="5.00" step="0.01" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group full-span">
                            <label for="pedi_direccion">Dirección de Envío (Opcional)</label>
                            <textarea id="pedi_direccion" name="pedi_direccion" rows="3" class="form-control" placeholder="Ej: Av. Principal 123, Apto 4B..."></textarea>
                        </div>
                    </div>

                    <div class="totals-section">
                        <div class="total-line">
                            <span>Subtotal Productos</span>
                            <span class="font-bold">${{ number_format($subtotalProductos, 2) }}</span>
                        </div>
                        <div class="total-line text-secondary">
                            <span>Costo de Envío</span>
                            {{-- El total final (Subtotal + Envío) se calcula de forma segura en el backend --}}
                            <span>(Se agregará al confirmar)</span>
                        </div>
                    </div>
                    
                    <div class="action-buttons">
                        <button type="submit" class="btn btn-success w-full">
                            <i class="fas fa-check-circle mr-2"></i> Confirmar y Guardar Pedido
                        </button>
                        <a href="{{ route('pedidos.create.step2') }}" class="btn btn-outline w-full">
                            <i class="fas fa-arrow-left mr-2"></i> Volver y Modificar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
/* === Paleta de Colores Mejorada === */
:root {
    --primary-color: #3b82f6;      /* Blue-500 */
    --primary-dark: #2563eb;       /* Blue-600 */
    --success-color: #22c55e;      /* Green-500 */
    --success-dark: #16a34a;       /* Green-600 */
    --bg-main: #f9fafb;            /* Gray-50 */
    --bg-card: #ffffff;            /* White */
    --bg-subtle: #f3f4f6;           /* Gray-100 */
    --border-color: #e5e7eb;        /* Gray-200 */
    --border-focus: var(--primary-color);
    --text-primary: #1f2937;        /* Gray-800 */
    --text-secondary: #6b7280;      /* Gray-500 */
    --text-on-primary: #ffffff;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --radius-md: 0.5rem;
    --transition-fast: all 0.2s ease-in-out;
}

/* === Indicador de Progreso === */
.step-indicator { display: flex; align-items: center; justify-content: center; width: 100%; margin: 0 auto 3rem; max-width: 800px; }
.step { display: flex; flex-direction: column; align-items: center; text-align: center; width: 120px; }
.step-icon {
    width: 50px; height: 50px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem; transition: var(--transition-fast);
    text-decoration: none; border: 2px solid var(--border-color);
    background-color: var(--bg-card); color: var(--text-secondary);
}
.step-label { font-size: 0.875rem; font-weight: 500; margin-top: 0.75rem; color: var(--text-secondary); }
.step-connector { flex-grow: 1; height: 2px; background-color: var(--border-color); }

.step.step-active .step-icon {
    background-color: var(--primary-color);
    color: var(--text-on-primary);
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px color-mix(in srgb, var(--primary-color) 25%, transparent);
}
.step.step-active .step-label { color: var(--text-primary); font-weight: 600; }

.step.step-complete .step-icon {
    border-color: var(--success-color);
    background: var(--bg-subtle);
    color: var(--success-color);
}
.step.step-complete .step-label { color: var(--text-primary); }
.step.step-complete a.step-icon:hover { background-color: var(--success-color); color: var(--text-on-primary); border-color: var(--success-dark); }


/* === Layout y Tarjetas === */
.confirmation-grid { display: grid; grid-template-columns: 3fr 2fr; gap: 2rem; align-items: start; }
.card {
    background-color: var(--bg-card);
    border-radius: var(--radius-md);
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}
.card-header {
    padding: 1rem 1.5rem;
    background-color: var(--bg-subtle);
    border-bottom: 1px solid var(--border-color);
}
.card-header h3 { font-size: 1.125rem; font-weight: 600; color: var(--text-primary); display: flex; align-items: center; }
.card-body { padding: 1.5rem; }
.sticky-card { position: sticky; top: 2rem; }

/* === Resumen de Cliente y Tabla de Productos === */
.customer-summary {
    padding: 1rem;
    background-color: var(--bg-subtle);
    border-radius: var(--radius-md);
    margin-bottom: 1.5rem;
}
.customer-name { font-weight: 600; font-size: 1.1rem; color: var(--text-primary); }
.customer-email { color: var(--text-secondary); font-size: 0.9rem; }

.table-container { max-height: 500px; overflow-y: auto; border: 1px solid var(--border-color); border-radius: var(--radius-md); }
.table-items { width: 100%; border-collapse: collapse; }
.table-items th, .table-items td { padding: 0.75rem 1rem; text-align: left; border-bottom: 1px solid var(--border-color); }
.table-items thead { position: sticky; top: 0; }
.table-items thead th {
    background-color: var(--bg-subtle);
    color: var(--text-secondary);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.table-items tbody tr:last-child td { border-bottom: none; }
.table-items tbody tr:nth-child(even) { background-color: var(--bg-subtle); }
.table-items .font-bold { font-weight: 700; color: var(--text-primary); }
.text-center { text-align: center; }
.text-right { text-align: right; }

/* === Formularios y Entradas === */
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
.form-group { display: flex; flex-direction: column; }
.full-span { grid-column: 1 / -1; }
.form-group label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-secondary);
    margin-bottom: 0.5rem;
}
.form-control {
    width: 100%;
    padding: 0.65rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    background-color: var(--bg-card);
    transition: var(--transition-fast);
    font-size: 1rem;
}
.form-control:focus {
    outline: 2px solid transparent;
    border-color: var(--border-focus);
    box-shadow: 0 0 0 2px color-mix(in srgb, var(--border-focus) 25%, transparent);
}
.input-with-icon { display: flex; align-items: center; }
.input-with-icon span { padding: 0.65rem 1rem; background: var(--bg-subtle); border: 1px solid var(--border-color); border-right: none; border-radius: var(--radius-md) 0 0 var(--radius-md); color: var(--text-secondary); }
.input-with-icon .form-control { border-radius: 0 var(--radius-md) var(--radius-md) 0; }

/* === Sección de Totales y Botones de Acción === */
.totals-section {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-color);
}
.total-line { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; font-size: 1rem; }
.total-line span { color: var(--text-secondary); }
.total-line .font-bold { color: var(--text-primary); font-size: 1.1rem; }

.action-buttons { margin-top: 2rem; display: flex; flex-direction: column; gap: 0.75rem; }
.btn { display: flex; align-items: center; justify-content: center; font-weight: 600; padding: 0.8rem 1rem; border-radius: var(--radius-md); text-decoration: none; transition: var(--transition-fast); font-size: 1rem; }
.btn-success { background-color: var(--success-color); color: var(--text-on-primary); border: 1px solid transparent; }
.btn-success:hover { background-color: var(--success-dark); transform: translateY(-2px); box-shadow: var(--shadow-md); }
.btn-outline { background-color: transparent; color: var(--primary-color); border: 1px solid var(--primary-color); }
.btn-outline:hover { background-color: color-mix(in srgb, var(--primary-color) 10%, transparent); }
.w-full { width: 100%; }
.mr-2 { margin-right: 0.5rem; }

/* === Responsividad === */
@media (max-width: 1024px) {
    .confirmation-grid {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    .step-indicator { flex-direction: column; gap: 1rem; align-items: start; }
    .step { flex-direction: row; text-align: left; width: 100%; }
    .step-icon { margin-right: 1rem; }
    .step-connector { display: none; }
}

</style>
@endsection
