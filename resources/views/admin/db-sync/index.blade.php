<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sincronización BD</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-database me-2"></i> Sincronización de Base de Datos</h3>
        <a href="{{ route('admin.db-sync.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nueva configuración
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        @foreach($configs as $config)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 {{ $config->activo ? '' : 'border-warning' }}">
                    <div class="card-header d-flex justify-content-between align-items-center {{ $config->activo ? 'bg-success text-white' : 'bg-warning text-dark' }}">
                        <strong>{{ $config->nombre }}</strong>
                        <span class="badge bg-{{ $config->activo ? 'light' : 'secondary' }}">
                            {{ $config->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="mb-2"><small class="text-muted">Host:</small> <code>{{ $config->host }}:{{ $config->puerto }}</code></div>
                        <div class="mb-2"><small class="text-muted">BD:</small> <code>{{ $config->database }}</code></div>
                        <div class="mb-2"><small class="text-muted">Usuario:</small> <code>{{ $config->usuario }}</code></div>
                        <div class="mb-2">
                            <small class="text-muted">Última sync:</small>
                            {{ $config->ultima_sincronizacion ? $config->ultima_sincronizacion->format('d/m/Y H:i:s') : 'Nunca' }}
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Estado:</small>
                            <span class="badge bg-{{ $config->ultimo_estado === 'ok' ? 'success' : ($config->ultimo_estado === 'error' ? 'danger' : 'secondary') }}">
                                {{ $config->ultimo_estado ?? 'Pendiente' }}
                            </span>
                        </div>
                        @if($config->ultimo_mensaje)
                            <div class="mb-2 small text-{{ $config->ultimo_estado === 'error' ? 'danger' : 'muted' }}">{{ $config->ultimo_mensaje }}</div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <div class="btn-group w-100" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="testConnection({{ $config->id }})" title="Probar conexión">
                                <i class="fas fa-plug"></i>
                            </button>
                            <button type="button" class="btn btn-success btn-sm" onclick="syncDb({{ $config->id }}, 'bidireccional')" title="Sincronizar bidireccional">
                                <i class="fas fa-sync"></i> Sync
                            </button>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-info btn-sm dropdown-toggle" data-bs-toggle="dropdown" title="Más opciones">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><button class="dropdown-item" onclick="syncDb({{ $config->id }}, 'remote_to_local')"><i class="fas fa-download me-1"></i> Remoto → Local</button></li>
                                    <li><button class="dropdown-item" onclick="syncDb({{ $config->id }}, 'local_to_remote')"><i class="fas fa-upload me-1"></i> Local → Remoto</button></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.db-sync.download-diff', $config->id) }}"><i class="fas fa-file-code me-1"></i> Descargar Diff SQL</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.db-sync.logs', $config->id) }}"><i class="fas fa-history me-1"></i> Ver logs</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.db-sync.edit', $config->id) }}"><i class="fas fa-edit me-1"></i> Editar</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('admin.db-sync.destroy', $config->id) }}" class="d-inline" onsubmit="return confirm('¿Eliminar configuración?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger"><i class="fas fa-trash me-1"></i> Eliminar</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        @if($configs->isEmpty())
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-database fa-3x text-muted mb-3"></i>
                        <h5>No hay configuraciones de sincronización</h5>
                        <p class="text-muted">Crea una configuración para conectar con la BD remota (cafe-peruano.com)</p>
                        <a href="{{ route('admin.db-sync.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Crear primera configuración</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function testConnection(configId) {
    fetch('{{ route("admin.db-sync.test", ":id") }}'.replace(':id', configId), {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
    }).then(r => r.json()).then(d => {
        alert(d.ok ? '✅ Conexión exitosa' : '❌ ' + d.msg);
    });
}

function syncDb(configId, direccion) {
    const btn = event.target.closest('button');
    const original = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Sincronizando...';

    fetch('{{ route("admin.db-sync.sync", ":id") }}'.replace(':id', configId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ direccion })
    }).then(r => r.json()).then(d => {
        btn.disabled = false;
        btn.innerHTML = original;
        if (d.ok) {
            alert('✅ Sincronización completada\nTablas: ' + d.resumen.tablas_procesadas + '\nInsertadas: ' + d.resumen.filas_insertadas + '\nActualizadas: ' + d.resumen.filas_actualizadas + '\nEliminadas: ' + d.resumen.filas_eliminadas);
            location.reload();
        } else {
            alert('❌ Error: ' + d.msg);
        }
    }).catch(e => {
        btn.disabled = false;
        btn.innerHTML = original;
        alert('❌ Error de red: ' + e.message);
    });
}
</script>
</body>
</html>