@extends('layouts.app')

@section('title', 'Detalle del Pedido')
@section('page-title', 'Detalle del Pedido #PED-' . $pedido->pedi_id)
@section('page-description', 'Revisa la información completa de la orden.')

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

    {{-- COLUMNA DERECHA: INFORMACIÓN DEL CLIENTE Y DEL PEDIDO --}}
    <div class="col-span-1">
        <div class="card sticky-card">
            <div class="card-header"><h3>Información General</h3></div>
            <div class="card-body">
                <ul class="details-list">
                    <li><strong>Cliente:</strong><span>{{ $pedido->cliente->clie_nombre }} {{ $pedido->cliente->clie_apellido }}</span></li>
                    <li><strong>Email:</strong><span>{{ $pedido->cliente->clie_email }}</span></li>
                    <li><strong>Fecha Pedido:</strong><span>{{ \Carbon\Carbon::parse($pedido->pedi_fecha)->format('d/m/Y') }}</span></li>
                    <li><strong>Estado Actual:</strong><span><span class="badge info">{{ $pedido->estado->esta__detalle }}</span></span></li>
                    <li><strong>Método de Pago:</strong><span>{{ $pedido->metodoPago->medo_detale }}</span></li>
                    <li><strong>Serv. de Entrega:</strong><span>{{ $pedido->servicioEntrega->serv_nombre }}</span></li>
                    <li><strong>Dirección:</strong><span>{{ $pedido->pedi_direccion ?: 'No especificada' }}</span></li>
                </ul>
                <div class="form-actions mt-4">
                    <a href="{{ route('pedidos.edit', $pedido) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Cambiar Estado</a>
                    <a href="{{ route('pedidos.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Volver</a>
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
.details-list { list-style: none; }
.details-list li { display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid var(--border-color); }
.details-list li strong { font-weight: 600; color: var(--text-secondary); }
.details-list li:last-child { border-bottom: none; }
</style>
@endsection
