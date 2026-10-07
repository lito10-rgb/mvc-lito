<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de envíos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-paper-plane me-2"></i> Historial de envíos de correo</h3>
        <div>
            <a href="{{ route('admin.email-logs.export-campaigns') }}" class="btn btn-outline-success me-2">
                <i class="fas fa-download me-1"></i> Exportar Listas (CSV)
            </a>
            <a href="{{ route('admin.email-logs.export', request()->query()) }}" class="btn btn-success">
                <i class="fas fa-download me-1"></i> Exportar Envíos (CSV)
            </a>
        </div>
    </div>

    <!-- Campañas / Listas guardadas -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong><i class="fas fa-list-alt me-2"></i> Listas de envío guardadas</strong>
        </div>
        <div class="card-body p-0">
            @if($campaigns->isEmpty())
                <div class="text-center py-4 text-muted">No hay listas guardadas. Al enviar, rellena "Nombre de la lista" para guardarla.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha/Hora</th>
                                <th>Nombre de la lista</th>
                                <th>Tipo</th>
                                <th>Negocio</th>
                                <th>Enviados</th>
                                <th>Fallidos</th>
                                <th>Batch ID</th>
                                <th>Creado por</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($campaigns as $c)
                                <tr>
                                    <td>{{ $c->enviado_en->format('d/m/Y H:i:s') }}</td>
                                    <td><strong>{{ $c->nombre }}</strong></td>
                                    <td><span class="badge bg-{{ $c->tipo === 'aviso_password' ? 'warning text-dark' : 'info' }}">{{ ucfirst($c->tipo) }}</span></td>
                                    <td>{{ $c->negocio ?? '-' }}</td>
                                    <td><span class="badge bg-success">{{ $c->total_enviados }}</span></td>
                                    <td><span class="badge bg-{{ $c->total_fallidos > 0 ? 'danger' : 'secondary' }}">{{ $c->total_fallidos }}</span></td>
                                    <td><small>{{ substr($c->batch_id, 0, 8) }}...</small></td>
                                    <td>{{ $c->creador?->name ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $campaigns->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Filtros para envíos individuales -->
    <div class="card mb-4">
        <div class="card-header">Filtros de envíos (detalle por destinatario)</div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Tipo</label>
                    <select name="tipo" class="form-select">
                        <option value="">Todos</option>
                        @foreach($tipos as $t)
                            <option value="{{ $t }}" {{ request('tipo') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos</option>
                        @foreach($estados as $e)
                            <option value="{{ $e }}" {{ request('estado') == $e ? 'selected' : '' }}>{{ ucfirst($e) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Negocio</label>
                    <select name="negocio" class="form-select">
                        <option value="">Todos</option>
                        @foreach($negocios as $n)
                            <option value="{{ $n }}" {{ request('negocio') == $n ? 'selected' : '' }}>{{ $n }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Lote (Batch)</label>
                    <select name="batch_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($batchIds as $b)
                            <option value="{{ $b }}" {{ request('batch_id') == $b ? 'selected' : '' }}>{{ substr($b, 0, 8) }}...</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Email</label>
                    <input type="text" name="email" class="form-control" placeholder="Buscar email" value="{{ request('email') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Desde</label>
                    <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-1"></i> Filtrar</button>
                    <a href="{{ route('admin.email-logs.index') }}" class="btn btn-outline-secondary"><i class="fas fa-times me-1"></i> Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de envíos individuales -->
    <div class="card">
        <div class="card-body p-0">
            @if($logs->isEmpty())
                <div class="text-center py-5 text-muted">No hay envíos registrados.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha/Hora</th>
                                <th>Tipo</th>
                                <th>Estado</th>
                                <th>Negocio</th>
                                <th>Remitente</th>
                                <th>Destinatario</th>
                                <th>Asunto</th>
                                <th>Batch ID</th>
                                <th>Error</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr class="{{ $log->estado === 'fallido' ? 'table-danger' : '' }}">
                                    <td>{{ $log->enviado_en->format('d/m/Y H:i:s') }}</td>
                                    <td><span class="badge bg-{{ $log->tipo === 'aviso_password' ? 'warning text-dark' : 'info' }}">{{ ucfirst($log->tipo) }}</span></td>
                                    <td><span class="badge bg-{{ $log->estado === 'enviado' ? 'success' : 'danger' }}">{{ ucfirst($log->estado) }}</span></td>
                                    <td>{{ $log->negocio ?? '-' }}</td>
                                    <td>{{ $log->from_name ?? '' }} <{{ $log->from_email ?? '' }}></td>
                                    <td>
                                        @if($log->user_id)
                                            <a href="{{ route('admin.usuarios.edit', $log->user_id) }}" target="_blank">
                                                {{ $log->nombre ?? '' }} ({{ $log->email }})
                                            </a>
                                        @else
                                            {{ $log->email }}
                                        @endif
                                    </td>
                                    <td class="text-truncate" style="max-width: 200px;">{{ $log->asunto }}</td>
                                    <td><small>{{ substr($log->batch_id, 0, 8) }}...</small></td>
                                    <td>{{ $log->error ? '<small class="text-danger">' . e($log->error) . '</small>' : '-' }}</td>
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