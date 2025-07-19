{{-- resources/views/audits/index.blade.php --}}
@extends('layouts.app') {{-- Asume que tienes un layout principal llamado 'app' --}}

@section('content')
<div class="container">
    <h1 class="mb-4">Registros de Auditoría</h1>

    @if ($audits->isEmpty())
        <p>No hay registros de auditoría disponibles.</p>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Modelo Afectado</th>
                        <th>ID del Modelo</th>
                        <th>Evento</th>
                        <th>Valores Antiguos</th>
                        <th>Valores Nuevos</th>
                        <th>URL</th>
                        <th>IP</th>
                        <th>Fecha y Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($audits as $audit)
                    <tr>
                        <td>{{ $audit->id }}</td>
                        <td>
                            {{-- Si necesitas mostrar el nombre del usuario, deberás unir la tabla 'users' --}}
                            {{-- Por ahora, solo mostramos el user_id. En un paso posterior te guiaré para unirte al usuario --}}
                            {{ $audit->user_id ?? 'Sistema/Invitado' }}
                        </td>
                        <td>{{ $audit->auditable_type }}</td>
                        <td>{{ $audit->auditable_id }}</td>
                        <td>{{ $audit->event }}</td>
                        <td>
                            @if ($audit->old_values)
                                <pre style="max-height: 150px; overflow-y: auto;">{{ json_encode(json_decode($audit->old_values), JSON_PRETTY_PRINT) }}</pre>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if ($audit->new_values)
                                <pre style="max-height: 150px; overflow-y: auto;">{{ json_encode(json_decode($audit->new_values), JSON_PRETTY_PRINT) }}</pre>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $audit->url ?? 'N/A' }}</td>
                        <td>{{ $audit->ip_address ?? 'N/A' }}</td>
                        <td>{{ $audit->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $audits->links() }} {{-- Esto mostrará los enlaces de paginación --}}
        </div>
    @endif
</div>
@endsection