{{-- resources/views/audits/index.blade.php --}}
@extends('layouts.app')

{{-- Aquí definimos los títulos para la cabecera de la página, usando las secciones de tu app.blade.php --}}
@section('page-title', 'Registros de Auditoría')
@section('page-description', 'Historial detallado de los cambios y acciones realizadas en el sistema.')

@section('content')
<div class="container-fluid">
    {{-- El h1 aquí debajo podría duplicar el título de la cabecera si tu layout ya lo muestra.
         Si tu diseño lo necesita, mantenlo; si no, puedes comentarlo o eliminarlo. --}}
    {{-- <h1 class="mb-4 text-center">Registros de Auditoría</h1> --}}

    @if ($audits->isEmpty())
        <div class="alert alert-info text-center" role="alert">
            No hay registros de auditoría disponibles.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered">
                <thead class="bg-dark text-white">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Modelo Afectado</th>
                        <th scope="col">ID del Modelo</th>
                        <th scope="col">Evento</th>
                        <th scope="col" style="min-width: 300px;">Detalles del Cambio</th>
                        <th scope="col">Fecha y Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($audits as $audit)
                    <tr>
                        <td>{{ $audit->id }}</td>
                        <td>
                            {{-- Muestra el nombre del usuario si está disponible, de lo contrario 'Sistema/Invitado' --}}
                            {{ $audit->user->name ?? 'Sistema/Invitado' }}
                        </td>
                        <td>
                            {{-- Muestra solo el nombre del modelo (ej. Cliente en lugar de App\Models\Cliente) --}}
                            <strong>{{ class_basename($audit->auditable_type) }}</strong>

                            {{-- Intenta mostrar un identificador del modelo afectado (nombre, título, email, etc.) --}}
                            @if ($audit->auditable)
                                @php
                                    $auditableIdentifier = '';
                                    $modelType = class_basename($audit->auditable_type);

                                    // Lógica para identificar el nombre según el tipo de modelo
                                    // ADAPTA ESTAS CONDICIONES A LOS NOMBRES REALES DE LAS COLUMNAS EN TUS MODELOS
                                    if ($modelType === 'Cliente') {
                                        if (isset($audit->auditable->clie_nombre)) { // <--- ADAPTA ESTO para Cliente
                                            $auditableIdentifier = $audit->auditable->clie_nombre;
                                        } elseif (isset($audit->auditable->razon_social)) {
                                            $auditableIdentifier = $audit->auditable->razon_social;
                                        } elseif (isset($audit->auditable->name)) {
                                            $auditableIdentifier = $audit->auditable->name;
                                        }
                                    } elseif ($modelType === 'Producto') {
                                        if (isset($audit->auditable->prod_nombre)) { // <--- ADAPTA ESTO para Producto
                                            $auditableIdentifier = $audit->auditable->prod_nombre;
                                        } elseif (isset($audit->auditable->name)) {
                                            $auditableIdentifier = $audit->auditable->name;
                                        } elseif (isset($audit->auditable->title)) {
                                            $auditableIdentifier = $audit->auditable->title;
                                        }
                                    } elseif ($modelType === 'User') {
                                        if (isset($audit->auditable->name)) {
                                            $auditableIdentifier = $audit->auditable->name;
                                        } elseif (isset($audit->auditable->email)) {
                                            $auditableIdentifier = $audit->auditable->email;
                                        }
                                    }
                                    // Puedes añadir más 'elseif' para otros modelos que audites

                                    // Fallback al ID si no se encontró un identificador textual o si el identificador está vacío
                                    if (empty($auditableIdentifier) && isset($audit->auditable->id)) {
                                        $auditableIdentifier = 'ID: ' . $audit->auditable->id;
                                    }
                                @endphp
                                @if ($auditableIdentifier)
                                    <br><small>({{ $auditableIdentifier }})</small>
                                @endif
                            @else
                                <br><small>(Modelo eliminado o no disponible)</small>
                            @endif
                        </td>
                        <td>{{ $audit->auditable_id }}</td>
                        <td>
                            {{-- Traduce el evento para que sea más legible --}}
                            @switch($audit->event)
                                @case('created')
                                    <span class="badge bg-success">Creación</span>
                                    @break
                                @case('updated')
                                    <span class="badge bg-primary">Actualización</span>
                                    @break
                                @case('deleted')
                                    <span class="badge bg-danger">Eliminación</span>
                                    @break
                                @case('restored')
                                    <span class="badge bg-info">Restauración</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">{{ ucfirst($audit->event) }}</span>
                            @endswitch
                        </td>
                        <td>
                            {{-- Lógica para mostrar los cambios de forma legible según el evento --}}
                            @php
                                $oldValues = $audit->old_values ?? [];
                                $newValues = $audit->new_values ?? [];
                                $displayChanges = [];

                                $excludedKeys = ['id', 'created_at', 'updated_at', 'password', 'remember_token'];

                                switch ($audit->event) {
                                    case 'created':
                                        echo '<p><strong>Datos del registro creado:</strong></p><ul class="list-unstyled mb-0">';
                                        foreach ($newValues as $key => $value) {
                                            if (in_array($key, $excludedKeys)) continue;
                                            if (is_array($value) || is_object($value)) $value = json_encode($value);
                                            if (is_bool($value)) $value = $value ? 'Sí' : 'No';
                                            $displayChanges[] = [
                                                'field' => ucfirst(str_replace('_', ' ', $key)),
                                                'new' => (string)$value,
                                            ];
                                            echo '<li><strong>' . ucfirst(str_replace('_', ' ', $key)) . ':</strong> <span class="text-success">' . (string)$value . '</span></li>';
                                        }
                                        echo '</ul>';
                                        break;

                                    case 'updated':
                                        echo '<p><strong>Campos modificados:</strong></p><ul class="list-unstyled mb-0">';
                                        $allKeys = array_unique(array_merge(array_keys($oldValues), array_keys($newValues)));
                                        foreach ($allKeys as $key) {
                                            if (in_array($key, $excludedKeys)) continue;

                                            $oldValue = array_key_exists($key, $oldValues) ? $oldValues[$key] : '<Vacio>';
                                            $newValue = array_key_exists($key, $newValues) ? $newValues[$key] : '<Vacio>';

                                            if (is_array($oldValue) || is_object($oldValue)) $oldValue = json_encode($oldValue);
                                            if (is_array($newValue) || is_object($newValue)) $newValue = json_encode($newValue);

                                            if (is_bool($oldValue)) $oldValue = $oldValue ? 'Sí' : 'No';
                                            if (is_bool($newValue)) $newValue = $newValue ? 'Sí' : 'No';

                                            if ((string)$oldValue !== (string)$newValue) {
                                                $displayChanges[] = [
                                                    'field' => ucfirst(str_replace('_', ' ', $key)),
                                                    'old' => (string)$oldValue,
                                                    'new' => (string)$newValue
                                                ];
                                                echo '<li><strong>' . ucfirst(str_replace('_', ' ', $key)) . ':</strong> <span class="text-danger">De: "' . (string)$oldValue . '"</span> <span class="text-success">A: "' . (string)$newValue . '"</span></li>';
                                            }
                                        }
                                        echo '</ul>';
                                        break;

                                    case 'deleted':
                                        echo '<p><strong>Datos del registro eliminado:</strong></p><ul class="list-unstyled mb-0">';
                                        foreach ($oldValues as $key => $value) {
                                            if (in_array($key, $excludedKeys)) continue;
                                            if (is_array($value) || is_object($value)) $value = json_encode($value);
                                            if (is_bool($value)) $value = $value ? 'Sí' : 'No';
                                            $displayChanges[] = [
                                                'field' => ucfirst(str_replace('_', ' ', $key)),
                                                'old' => (string)$value,
                                            ];
                                            echo '<li><strong>' . ucfirst(str_replace('_', ' ', $key)) . ':</strong> <span class="text-danger">' . (string)$value . '</span></li>';
                                        }
                                        echo '</ul>';
                                        break;

                                    default:
                                        echo '<p>No hay cambios detallados para este evento o evento no categorizado.</p>';
                                        break;
                                }

                                if (empty($displayChanges) && !in_array($audit->event, ['created', 'updated', 'deleted'])) {
                                    echo 'No hay cambios de valores detallados para mostrar.';
                                } elseif (empty($displayChanges) && in_array($audit->event, ['created', 'updated', 'deleted'])) {
                                    echo 'No se encontraron campos modificados relevantes.';
                                }
                            @endphp
                        </td>
                        <td>{{ $audit->created_at->format('d-m-Y H:i:s') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $audits->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection