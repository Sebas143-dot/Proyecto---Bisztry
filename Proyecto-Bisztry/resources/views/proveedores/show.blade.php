@extends('layouts.app')

@section('title', 'Detalle del Proveedor')
@section('page-title', 'Detalle del Proveedor')
@section('page-description', $proveedor->prov_nombre)

@section('content')
<div class="card">
    <div class="card-header">
        <h3>{{ $proveedor->prov_nombre }}</h3>
        <div class="card-actions">
            <a href="{{ route('proveedores.edit', $proveedor) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Editar</a>
            <a href="{{ route('proveedores.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Volver</a>
        </div>
    </div>
    <div class="card-body">
        <ul class="details-list">
            <li><strong>RUC:</strong> {{ $proveedor->prov_ruc }}</li>
            <li><strong>Nombre:</strong> {{ $proveedor->prov_nombre }}</li>
            <li><strong>Contacto:</strong> {{ $proveedor->prov_contacto ?? 'No registrado' }}</li>
            <li><strong>Teléfono:</strong> {{ $proveedor->prov_telefono ?? 'No registrado' }}</li>
            <li><strong>Email:</strong> {{ $proveedor->prov_email ?? 'No registrado' }}</li>
        </ul>
    </div>
</div>

<div class="card" style="margin-top: 2rem;">
    <div class="card-header">
        <h3>Historial de Compras</h3>
    </div>
    <div class="card-body">
         @if($proveedor->compras && $proveedor->compras->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID Compra</th>
                            <th>Nº Factura</th>
                            <th>Fecha</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($proveedor->compras as $compra)
                        <tr>
                            <td>{{ $compra->comp_id }}</td>
                            <td>{{ $compra->comp_factura_num }}</td>
                            <td>{{ \Carbon\Carbon::parse($compra->comp_feccha)->format('d/m/Y') }}</td>
                            <td>${{ number_format($compra->comp_precio_total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
         @else
            <div class="empty-state">
                <i class="fas fa-file-invoice-dollar"></i>
                <h3>Sin Compras</h3>
                <p>Este proveedor aún no tiene compras registradas en el sistema.</p>
            </div>
         @endif
    </div>
</div>

<style>
.details-list { list-style: none; padding: 0; }
.details-list li { padding: 0.75rem 0; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center;}
.details-list li strong { width: 200px; color: #6b7280; }
.details-list li:last-child { border-bottom: none; }
</style>
@endsection