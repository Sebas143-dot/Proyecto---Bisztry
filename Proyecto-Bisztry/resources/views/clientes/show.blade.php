@extends('layouts.app')

@section('title', 'Detalle del Cliente')
@section('page-title', 'Detalle del Cliente')
@section('page-description', 'Información completa del cliente.')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h3>{{ $cliente->clie_nombre }} {{ $cliente->clie_apellido }}</h3>
            <p>ID de Cliente: {{ $cliente->clie_id }}</p>
        </div>
        <div class="card-actions">
            <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Editar</a>
            <a href="{{ route('clientes.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Volver a la Lista</a>
        </div>
    </div>
    <div class="card-body">
        <ul class="details-list">
            <li><strong>Nombre:</strong> {{ $cliente->clie_nombre }}</li>
            <li><strong>Apellido:</strong> {{ $cliente->clie_apellido }}</li>
            <li><strong>Email:</strong> {{ $cliente->clie_email }}</li>
            <li><strong>Teléfono:</strong> {{ $cliente->clie_telefono ?: 'No registrado' }}</li>
            <li><strong>Ciudad:</strong> {{ $cliente->ciudad->ciud_nombre ?? 'No registrada' }}</li>
            <li><strong>Provincia:</strong> {{ $cliente->ciudad->provincia->prov_nomnbre ?? 'No registrada' }}</li>
            <li><strong>Dirección:</strong> {{ $cliente->clie_direccion ?: 'No registrada' }}</li>
            <li><strong>Fecha de Nacimiento:</strong> {{ $cliente->clie_fecha_nac ? \Carbon\Carbon::parse($cliente->clie_fecha_nac)->format('d/m/Y') : 'No registrada' }}</li>
            <li><strong>Fecha de Registro:</strong> {{ $cliente->created_at->format('d/m/Y H:i') }}</li>
        </ul>
    </div>
</div>

<div class="card" style="margin-top: 2rem;">
    <div class="card-header">
        <h3>Historial de Pedidos</h3>
    </div>
    <div class="card-body">
         @if($cliente->pedidos->count() > 0)
            <p>Este cliente tiene {{ $cliente->pedidos->count() }} pedidos registrados.</p>
            {{-- Aquí podrías mostrar una tabla con los pedidos del cliente --}}
         @else
            <p>Este cliente aún no tiene pedidos registrados.</p>
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