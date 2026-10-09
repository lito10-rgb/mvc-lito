<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs de sincronización - {{ $config->nombre }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.db-sync.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Volver</a>
            <h3 class="d-inline ms-3"><i class="fas fa-history me-2"></i> Logs: {{ $config->nombre }}</h3>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            @if($logs->isEmpty())
                <div class="text-center py-5 text-muted">No hay logs de sincronización</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha/Hora</th>
                                <th>Dirección</th>
                                <th>Fase</th>
                                <th>Estado</th>
                                <th>Tablas</th>
                                <th>Insertadas</th>
                                <th>Actualizadas</th>
                                <th>Eliminadas</th>
                                <th>Errores</th>
                                <th>Duración</th>
                                <th>Ejecutado por</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr class="{{ $log->estado === 'error' ? 'table-danger' : ($log->estado === 'completado' ? 'table-success' : '') }}">
                                    <td>{{ $log->iniciado_en->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $log->direccion === 'bidireccional' ? 'info' : ($log->direccion === 'remote_to_local' ? 'primary' : 'success') }}">
                                            {{ $log->direccion }}
                                        </span>
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $log->fase }}</span></td>
                                    <td>
                                        <span class="badge bg-{{ $log->estado === 'completado' ? 'success' : ($log->estado === 'error' ? 'danger' : 'warning') }}">
                                            {{ $log->estado }}
                                        </span>
                                    </td>
                                    <td>{{ $log->resumen['tablas_procesadas'] ?? 0 }}</td>
                                    <td><span class="badge bg-success">{{ $log->resumen['filas_insertadas'] ?? 0 }}</span></td>
                                    <td><span class="badge bg-primary">{{ $log->resumen['filas_actualizadas'] ?? 0 }}</span></td>
                                    <td><span class="badge bg-danger">{{ $log->resumen['filas_eliminadas'] ?? 0 }}</span></td>
                                    <td>{{ count($log->resumen['errores'] ?? []) }}</td>
                                    <td>
                                        @if($log->finalizado_en)
                                            {{ $log->iniciado_en->diffInSeconds($log->finalizado_en) }}s
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $log->ejecutor?->name ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>