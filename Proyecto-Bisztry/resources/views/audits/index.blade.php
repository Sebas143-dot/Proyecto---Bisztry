@extends('layouts.app')

@section('page-title', 'Registros de Auditoría')
@section('page-description', 'Historial detallado de los cambios y acciones realizadas en el sistema.')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="header-content">
            <h3><i class="fas fa-history text-primary mr-3"></i>Historial de Cambios del Sistema</h3>
            <p class="text-secondary">Filtra los registros para encontrar acciones específicas.</p>
        </div>
        <div class="filter-panel">
            <a href="{{ route('audits.index') }}" class="filter-btn {{ !request('event') ? 'active' : '' }}">Todos</a>
            <a href="{{ route('audits.index', ['event' => 'created']) }}" class="filter-btn {{ request('event') == 'created' ? 'active' : '' }}">Creados</a>
            <a href="{{ route('audits.index', ['event' => 'updated']) }}" class="filter-btn {{ request('event') == 'updated' ? 'active' : '' }}">Actualizados</a>
            <a href="{{ route('audits.index', ['event' => 'deleted']) }}" class="filter-btn {{ request('event') == 'deleted' ? 'active' : '' }}">Eliminados</a>
        </div>
    </div>
    <div class="card-body">
        @if ($audits->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon"><i class="fas fa-search-minus"></i></div>
                <h3 class="empty-state-title">No se encontraron registros</h3>
                <p class="empty-state-text">No hay registros que coincidan con el filtro seleccionado. Prueba con otra opción.</p>
                <a href="{{ route('audits.index') }}" class="btn btn-primary mt-3">Limpiar Filtros</a>
            </div>
        @else
            @php
                function translateValue($field, $value) {
                    if (is_null($value) || $value === '') return '<em>Vacío</em>';
                    if ($field === 'prod_estado' || $field === 'clie_estado') {
                        return $value == 1 ? 'Activado' : 'Desactivado';
                    }
                    if (is_bool($value)) { return $value ? 'Sí' : 'No'; }
                    return e($value);
                }
                $fieldTranslations = [
                    'clie_nombre' => 'Nombre', 'clie_apellido' => 'Apellido', 'clie_email' => 'Email',
                    'clie_telefono' => 'Teléfono', 'clie_direccion' => 'Dirección', 'ciud_cod' => 'Ciudad',
                    'clie_fecha_nac' => 'Fecha de Nacimiento', 'prod_nombre' => 'Nombre del Producto',
                    'cate_id' => 'Categoría', 'prod_estado' => 'Estado', 'prov_ruc' => 'RUC',
                    'prov_nombre' => 'Razón Social', 'prov_contacto' => 'Contacto', 'prov_telefono' => 'Teléfono del Proveedor',
                    'prov_email' => 'Email del Proveedor', 'esta_cod' => 'Estado del Pedido', 'pedi_costo_envio' => 'Costo de Envío',
                    'name' => 'Nombre de Usuario', 'email' => 'Email de Usuario',
                ];
            @endphp

            <div class="table-container">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th style="width: 20%;" class="text-center">Usuario</th>
                            <th style="width: 55%;">Acción y Detalle del Cambio</th>
                            <th style="width: 25%;" class="text-center">Fecha y Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($audits as $audit)
                            <tr class="audit-row">
                                <td class="user-cell text-center">
                                    <div class="user-info">
                                        <div class="user-avatar" title="{{ $audit->user->name ?? 'Sistema' }}">{{ strtoupper(substr($audit->user->name ?? 'S', 0, 1)) }}</div>
                                        <span>{{ $audit->user->name ?? 'Sistema' }}</span>
                                    </div>
                                </td>
                                <td class="details-cell">
                                    <div class="action-header">
                                        <span class="badge event-{{ $audit->event }}">
                                            @switch($audit->event)
                                                @case('created') <i class="fas fa-plus-circle mr-2"></i> Creado @break
                                                @case('updated') <i class="fas fa-pencil-alt mr-2"></i> Actualizado @break
                                                @case('deleted') <i class="fas fa-trash-alt mr-2"></i> Eliminado @break
                                                @default <i class="fas fa-info-circle mr-2"></i> {{ ucfirst($audit->event) }} @break
                                            @endswitch
                                        </span>
                                        <div class="model-details">
                                            <strong>{{ e(class_basename($audit->auditable_type)) }}:</strong> 
                                            @php
                                                $humanIdentifier = $audit->auditable->clie_nombre ?? $audit->auditable->prod_nombre ?? $audit->old_values['clie_nombre'] ?? $audit->old_values['prod_nombre'] ?? class_basename($audit->auditable_type) . ' #' . $audit->auditable_id;
                                            @endphp
                                            <span>{{ e($humanIdentifier) }}</span>
                                        </div>
                                    </div>
                                    @php
                                        $changes = [];
                                        $oldData = $audit->old_values;
                                        $newData = $audit->new_values;
                                        $allKeys = array_unique(array_merge(array_keys($oldData), array_keys($newData)));
                                        $excludedKeys = ['id', 'created_at', 'updated_at', 'password', 'remember_token'];
                                        foreach ($allKeys as $key) {
                                            if (in_array($key, $excludedKeys)) continue;
                                            $old = array_key_exists($key, $oldData) ? $oldData[$key] : null;
                                            $new = array_key_exists($key, $newData) ? $newData[$key] : null;
                                            if ((string)$old !== (string)$new || $audit->event === 'created') {
                                                $changes[$key] = ['old' => $old, 'new' => $new];
                                            }
                                        }
                                    @endphp
                                    @if(!empty($changes))
                                        <table class="changes-subtable">
                                            <tbody>
                                                @foreach($changes as $field => $values)
                                                <tr>
                                                    <td class="field-name">{{ $fieldTranslations[$field] ?? ucfirst(str_replace('_', ' ', $field)) }}</td>
                                                    @if($audit->event !== 'created')
                                                        <td class="value-old">{!! translateValue($field, $values['old']) !!}</td>
                                                        <td class="arrow-cell">→</td>
                                                    @endif
                                                    <td class="value-new">{!! translateValue($field, $values['new']) !!}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </td>
                                <td class="time-cell text-center">
                                    <div class="time-info">
                                        <strong>{{ $audit->created_at->format('d/m/Y') }}</strong>
                                        <small>{{ $audit->created_at->format('H:i:s A') }}</small>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="pagination-container">
                {{ $audits->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<style>
/* === Paleta y Variables Base === */
:root {
    --primary-color: #3b82f6; --primary-dark: #2563eb;
    --bg-main: #f9fafb; --bg-card: #ffffff;
    --border-color: #e5e7eb; --text-primary: #1f2937; --text-secondary: #6b7280;
    --radius-lg: 0.75rem; --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
}
.card { background-color: var(--bg-card); border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid var(--border-color); }
.card-header {
    padding: 1.5rem; border-bottom: 1px solid var(--border-color);
    display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;
}
.card-header h3 { font-size: 1.25rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; margin: 0; }
.card-header p { margin: 0; color: var(--text-secondary); }
.card-body { padding: 0; }
.mr-2 { margin-right: 0.5rem; }
.mr-3 { margin-right: 0.75rem; }
.mt-3 { margin-top: 1rem; }
.filter-panel { display: flex; gap: 0.5rem; background-color: var(--bg-main); padding: 0.5rem; border-radius: var(--radius-lg); }
.filter-btn {
    padding: 0.5rem 1rem; border-radius: 0.5rem;
    text-decoration: none; font-weight: 600; font-size: 0.875rem;
    color: var(--text-secondary); border: 1px solid transparent;
    transition: all 0.2s ease-in-out;
}
.filter-btn:hover { color: var(--text-primary); background-color: #e5e7eb; }
.filter-btn.active {
    background-color: var(--bg-card); color: var(--primary-color);
    border-color: var(--border-color); box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
}
.table-container { overflow-x: auto; }
.table-modern { width: 100%; border-collapse: collapse; }
.table-modern th {
    padding: 1rem 1.5rem; text-align: left; background-color: var(--bg-main);
    color: var(--text-secondary); font-size: 0.8rem; font-weight: 600; text-transform: uppercase;
    border-bottom: 2px solid var(--border-color);
}
.audit-row td { border-bottom: 1px solid var(--border-color); vertical-align: top; padding: 1.5rem; }
.audit-row:last-child td { border-bottom: none; }
.user-cell, .time-cell { vertical-align: middle !important; }
.text-center { text-align: center; }
.user-info { display: inline-flex; align-items: center; gap: 1rem; }
.user-avatar { width: 40px; height: 40px; border-radius: 50%; background-color: var(--border-color); color: var(--text-secondary); display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0; }
.time-info { display: inline-flex; flex-direction: column; }
.time-info small { color: var(--text-secondary); }
.details-cell .action-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem; flex-wrap: wrap; }
.model-details { font-size: 0.9rem; color: var(--text-secondary); background-color: var(--bg-main); padding: 0.25rem 0.75rem; border-radius: 0.5rem; border: 1px solid var(--border-color); display: inline-block; }
.model-details strong { color: var(--text-primary); }
.badge { display: inline-flex; align-items: center; padding: 0.4rem 0.8rem; border-radius: 9999px; font-weight: 600; font-size: 0.8rem; }
.event-created { background-color: #dcfce7; color: #166534; }
.event-updated { background-color: #dbeafe; color: #1e40af; }
.event-deleted { background-color: #fee2e2; color: #991b1b; }
.no-changes-text { color: var(--text-secondary); font-style: italic; padding: 0.5rem; text-align: left; }
.changes-subtable { width: 100%; border: 1px solid var(--border-color); border-radius: var(--radius-lg); overflow: hidden; background-color: var(--bg-main); font-size: 0.9rem; }
.changes-subtable td { padding: 0.5rem 0.75rem; border-bottom: 1px solid var(--border-color); text-align: left; }
.changes-subtable tr:last-child td { border-bottom: none; }
.field-name { font-weight: 600; color: var(--text-secondary); width: 25%; }
.arrow-cell { text-align: center; color: var(--text-secondary); }
.value-old { color: #b91c1c; text-decoration: line-through; }
.value-new { color: #166534; font-weight: 600; }
.value-old em, .value-new em { color: var(--text-secondary); }
.pagination-container { padding: 1.5rem; border-top: 1px solid var(--border-color); }
.pagination { justify-content: center; margin: 0; }
.page-item.active .page-link { background-color: var(--primary-color); border-color: var(--primary-color); }
.page-link { color: var(--primary-color); } .page-link:hover { color: var(--primary-dark); }
.empty-state { padding: 4rem 2rem; text-align: center; color: var(--text-secondary); }
.empty-state-icon { font-size: 3rem; margin-bottom: 1rem; opacity: 0.5; }
.empty-state-title { font-size: 1.5rem; font-weight: 700; color: var(--text-primary); }
.btn { text-decoration: none; display: inline-flex; justify-content: center; align-items: center; font-weight: 600; padding: 0.75rem 1.5rem; border-radius: var(--radius-lg); transition: all 0.2s ease-in-out; cursor: pointer; border: 1px solid transparent; }
.btn-primary { background-color: var(--primary-color); color: #ffffff; }
.btn-primary:hover { background-color: var(--primary-dark); transform: translateY(-2px); box-shadow: var(--shadow-md); }
</style>
@endsection