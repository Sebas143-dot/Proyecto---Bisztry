@extends('layouts.app')

@section('title', 'Nuevo Pedido - Paso 3')
@section('page-title', 'Asistente de Nuevo Pedido')
@section('page-description', 'Confirma los detalles y guarda el pedido.')

@section('content')

{{-- INDICADOR DE PROGRESO VISUAL --}}
<div class="step-indicator">
    <div class="step step-complete">
        <a href="{{ route('pedidos.create.step1') }}" class="step-icon" title="Volver al Paso 1"><i class="fas fa-check"></i></a>
        <div class="step-label">1. Cliente Seleccionado</div>
    </div>
    <div class="step-connector"></div>
    <div class="step step-complete">
        <a href="{{ route('pedidos.create.step2') }}" class="step-icon" title="Volver al Paso 2"><i class="fas fa-check"></i></a>
        <div class="step-label">2. Productos Añadidos</div>
    </div>
    <div class="step-connector"></div>
    <div class="step step-active">
        <div class="step-icon"><i class="fas fa-receipt"></i></div>
        <div class="step-label">3. Finalizar Pedido</div>
    </div>
</div>

<form action="{{ route('pedidos.store') }}" method="POST">
    @csrf
    <div class="pedido-grid">
        {{-- COLUMNA IZQUIERDA: RESUMEN DETALLADO DEL PEDIDO --}}
        <div class="col-span-2">
            <div class="card">
                <div class="card-header"><h3>Resumen del Pedido</h3></div>
                <div class="card-body">
                    <div class="resumen-cliente">
                        <h4><strong>Cliente:</strong> {{ $cliente->clie_nombre }} {{ $cliente->clie_apellido }}</h4>
                        <p class="text-secondary">{{ $cliente->clie_email }}</p>
                    </div>
                    <hr class="my-4">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Cant.</th>
                                    <th class="text-right">P. Unit.</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $subtotal = 0; @endphp
                                @foreach($pedidoSession['carrito'] as $item)
                                @php $subtotal += $item['subtotal']; @endphp
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
        </div>

        {{-- COLUMNA DERECHA: DATOS FINALES Y ACCIONES --}}
        <div class="col-span-1">
            <div class="card sticky-card">
                <div class="card-header"><h3>Detalles Finales</h3></div>
                <div class="card-body">
                    <div class="form-grid-final">
                        <div class="form-group"><label for="pedi_fecha">Fecha del Pedido *</label><input type="date" name="pedi_fecha" value="{{ date('Y-m-d') }}" class="form-control" required></div>
                        <div class="form-group"><label>Estado Inicial *</label><select name="esta_cod" class="form-control" required>@foreach($estados as $estado)<option value="{{ $estado->esta_cod }}">{{ $estado->esta__detalle }}</option>@endforeach</select></div>
                        <div class="form-group"><label>Método de Pago *</label><select name="meto_cod" class="form-control" required>@foreach($metodosPago as $metodo)<option value="{{ $metodo->meto_cod }}">{{ $metodo->medo_detale }}</option>@endforeach</select></div>
                        <div class="form-group"><label>Servicio de Entrega *</label><select name="serv_id" class="form-control" required>@foreach($serviciosEntrega as $servicio)<option value="{{ $servicio->serv_id }}">{{ $servicio->serv_nombre }}</option>@endforeach</select></div>
                        <div class="form-group full-width"><label>Costo de Envío *</label><input type="number" name="pedi_costo_envio" value="5.00" step="0.01" class="form-control" required></div>
                        <div class="form-group full-width"><label>Dirección de Envío (si aplica)</label><textarea name="pedi_direccion" rows="2" class="form-control"></textarea></div>
                    </div>
                    <hr class="my-4">
                    <div class="total-line"><span>Subtotal Productos:</span><strong>${{ number_format($subtotal, 2) }}</strong></div>
                    {{-- El total final (Subtotal + Envío) se calculará en el backend para mayor seguridad --}}
                    
                    <button type="submit" class="btn btn-primary w-full justify-center text-lg p-4 mt-4">
                        <i class="fas fa-check-circle"></i> Confirmar y Guardar Pedido
                    </button>
                    <a href="{{ route('pedidos.create.step2') }}" class="btn btn-outline w-full justify-center mt-2">
                        <i class="fas fa-arrow-left"></i> Volver y Modificar Carrito
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
/* Estilos del indicador de progreso (reutilizados) */
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

/* Estilos específicos de esta página */
.pedido-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; align-items: start; }
.col-span-2 { grid-column: span 2; } .col-span-1 { grid-column: span 1; }
.sticky-card { position: sticky; top: 2rem; }
.resumen-cliente { padding: 1rem; background-color: var(--bg-color); border-radius: var(--radius-md); }
.form-grid-final { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.full-width { grid-column: 1 / -1; }
.my-4 { margin-top: 1.5rem; margin-bottom: 1.5rem; border-color: var(--border-color); }
.mt-4 { margin-top: 1.5rem; } .mt-2 { margin-top: 0.5rem; }
.p-4 { padding: 1rem; } .text-lg { font-size: 1.125rem; }
.w-full { width: 100%; }
.table thead th { text-transform: uppercase; font-size: 0.8rem; }
.table .font-bold { font-weight: 600; }
</style>
@endsection
