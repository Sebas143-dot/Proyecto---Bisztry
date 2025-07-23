@extends('layouts.app')

@section('title', 'Detalle del Pedido')
@section('page-title', 'Detalle del Pedido #PED-' . $pedido->pedi_id)
@section('page-description', 'Revisa la información completa de la orden y actualiza su estado.')

@section('content')
<div class="pedido-grid">
    {{-- COLUMNA IZQUIERDA: RESUMEN DE PRODUCTOS Y TOTALES --}}
    <div class="col-span-2">
        <div class="card">
            <div class="card-header"><h3>Artículos del Pedido</h3></div>
            <div class="card-body">
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
                            @foreach($pedido->detalles as $detalle)
                            <tr>
                                <td>
                                    <strong>{{ $detalle->variante->producto->prod_nombre }}</strong>
                                    <span class="block text-secondary text-small">{{ $detalle->variante->talla->tall_detalle }}, {{ $detalle->variante->color->col_detalle }}</span>
                                </td>
                                <td class="text-center">{{ $detalle->cantidad }}</td>
                                <td class="text-right">${{ number_format($detalle->variante->var_precio, 2) }}</td>
                                <td class="text-right font-bold">${{ number_format($detalle->cantidad * $detalle->variante->var_precio, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right">Subtotal Productos:</td>
                                <td class="text-right font-bold">${{ number_format($pedido->pedi_total, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right">Costo de Envío:</td>
                                <td class="text-right font-bold">${{ number_format($pedido->pedi_costo_envio, 2) }}</td>
                            </tr>
                            <tr class="grand-total-row">
                                <td colspan="3" class="text-right"><strong>Total del Pedido:</strong></td>
                                <td class="text-right"><strong>${{ number_format($pedido->pedi_total + $pedido->pedi_costo_envio, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- COLUMNA DERECHA: INFORMACIÓN Y FORMULARIO DE ACTUALIZACIÓN DE ESTADO --}}
    <div class="col-span-1">
        <div class="card sticky-card">
            <div class="card-header"><h3>Información y Acciones</h3></div>
            <div class="card-body">
                {{-- SECCIÓN DE INFORMACIÓN GENERAL --}}
                <ul class="details-list">
                    <li><strong>Cliente:</strong><span>{{ $pedido->cliente->clie_nombre }} {{ $pedido->cliente->clie_apellido }}</span></li>
                    <li><strong>Email:</strong><span>{{ $pedido->cliente->clie_email }}</span></li>
                    <li><strong>Fecha Pedido:</strong><span>{{ \Carbon\Carbon::parse($pedido->pedi_fecha)->format('d/m/Y') }}</span></li>
                    {{-- CORRECCIÓN 1: Se corrigió el nombre de la columna 'esta__detalle' a 'esta_detalle' --}}
                    <li><strong>Estado Actual:</strong><span><span class="badge info">{{ $pedido->estado->esta_detalle }}</span></span></li>
                    {{-- CORRECCIÓN 2: Se corrigió el nombre de la columna 'medo_detale' a 'meto_detalle' (asumiendo) --}}
                    <li><strong>Método de Pago:</strong><span>{{ $pedido->metodoPago->meto_detalle }}</span></li>
                    <li><strong>Serv. de Entrega:</strong><span>{{ $pedido->servicioEntrega->serv_nombre }}</span></li>
                    <li><strong>Dirección:</strong><span>{{ $pedido->pedi_direccion ?: 'No especificada' }}</span></li>
                </ul>

                <hr class="my-4">

                {{-- SECCIÓN PARA ACTUALIZAR ESTADO --}}
                <form action="{{ route('pedidos.updateStatus', $pedido) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="esta_cod" class="font-bold">Actualizar Estado del Pedido</label>
                        <select id="esta_cod" name="esta_cod" class="form-control mt-2" required>
                            @foreach($estados as $estado)
                                <option value="{{ $estado->esta_cod }}" @if($pedido->esta_cod == $estado->esta_cod) selected @endif>
                                    {{ $estado->esta_detalle }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-full mt-3">
                        <i class="fas fa-save mr-2"></i> Guardar Nuevo Estado
                    </button>
                </form>

                <hr class="my-4">
                
                {{-- SECCIÓN DE OTRAS ACCIONES --}}
                <div class="form-actions">
                    <a href="{{ route('pedidos.edit', $pedido) }}" class="btn btn-secondary w-full"><i class="fas fa-edit"></i> Editar Productos</a>
                    <a href="{{ route('pedidos.index') }}" class="btn btn-outline w-full mt-2"><i class="fas fa-arrow-left"></i> Volver al Listado</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.pedido-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; align-items: start; }
.col-span-2 { grid-column: span 2; }
.col-span-1 { grid-column: span 1; }
.sticky-card { position: sticky; top: 2rem; }
.table tfoot tr:last-child { border-top: 2px solid var(--text-primary); }
.table tfoot strong { font-size: 1.1em; }
.details-list { list-style: none; padding: 0; margin: 0; }
.details-list li { display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid var(--border-color); }
.details-list li strong { font-weight: 600; color: var(--text-secondary); margin-right: 1rem; }
.details-list li span { text-align: right; }
.details-list li:last-child { border-bottom: none; }
.form-actions { display: flex; flex-direction: column; gap: 0.5rem; }
.w-full { width: 100%; }
.mt-2 { margin-top: 0.5rem; }
.mt-3 { margin-top: 0.75rem; }
.mt-4 { margin-top: 1rem; }
.my-4 { margin-top: 1.5rem; margin-bottom: 1.5rem; }
.mr-2 { margin-right: 0.5rem; }
</style>
@endsection
