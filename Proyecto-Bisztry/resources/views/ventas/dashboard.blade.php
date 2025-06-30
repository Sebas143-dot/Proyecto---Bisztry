@extends('layouts.app')

@section('title', 'Panel de Ventas')
@section('page-title', 'Panel de Ventas')
@section('page-description', 'Gestiona clientes y pedidos desde esta sección.')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h3>Resumen de Ventas</h3>
            <p>Accede a los pedidos recientes y registra nuevos clientes.</p>
        </div>
        <div class="card-actions">
            <a href="{{ route('clientes.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Nuevo Cliente
            </a>
            <a href="{{ route('pedidos.create') }}" class="btn btn-success">
                <i class="fas fa-box-open"></i> Nuevo Pedido
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Pedido</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Total</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $pedido)
                    <tr>
                        <td>{{ $pedido->pedi_codigo }}</td>
                        <td>{{ $pedido->cliente->nombre_completo }}</td>
                        <td>{{ $pedido->pedi_fecha }}</td>
                        <td>{{ $pedido->estado->estp_nombre }}</td>
                        <td>${{ number_format($pedido->pedi_total, 2) }}</td>
                        <td class="text-right">
                            <a href="{{ route('pedidos.show', $pedido) }}" class="btn-icon info" title="Ver Pedido">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .btn-icon { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50%; color: white; }
    .btn-icon.info { background-color: #3b82f6; }
</style>
@endsection
