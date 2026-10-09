<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($config) ? 'Editar' : 'Nueva' }} configuración BD Sync</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-database me-2"></i> {{ isset($config) ? 'Editar' : 'Nueva' }} configuración de sincronización</h3>
        <a href="{{ route('admin.db-sync.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Volver</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Errores de validación:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ isset($config) ? route('admin.db-sync.update', $config) : route('admin.db-sync.store') }}" class="card">
        @csrf
        @if(isset($config)) @method('PUT') @endif

        <div class="card-header bg-primary text-white">
            <strong>Datos de conexión remota (cafe-peruano.com)</strong>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $config->nombre ?? '') }}" required placeholder="Ej: cafe-peruano-prod">
                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Host <span class="text-danger">*</span></label>
                    <input type="text" name="host" class="form-control @error('host') is-invalid @enderror" value="{{ old('host', $config->host ?? '') }}" required placeholder="Ej: localhost o IP del servidor">
                    @error('host')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Puerto <span class="text-danger">*</span></label>
                    <input type="number" name="puerto" class="form-control @error('puerto') is-invalid @enderror" value="{{ old('puerto', $config->puerto ?? 3306) }}" required min="1" max="65535">
                    @error('puerto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Base de datos <span class="text-danger">*</span></label>
                    <input type="text" name="database" class="form-control @error('database') is-invalid @enderror" value="{{ old('database', $config->database ?? '') }}" required placeholder="Nombre BD remota">
                    @error('database')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Usuario <span class="text-danger">*</span></label>
                    <input type="text" name="usuario" class="form-control @error('usuario') is-invalid @enderror" value="{{ old('usuario', $config->usuario ?? '') }}" required placeholder="Usuario BD remota">
                    @error('usuario')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Contraseña <span class="text-danger">*</span> @if(isset($config)) <small class="text-muted">(dejar vacío para no cambiar)</small> @endif</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" {{ !isset($config) ? 'required' : '' }} placeholder="{{ isset($config) ? '••••••••' : 'Contraseña BD remota' }}">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @if(isset($config))
                        <div class="form-text">La contraseña se guarda encriptada (Crypt::encryptString).</div>
                    @endif
                </div>
                <div class="col-md-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="activo" id="activo" value="1" {{ old('activo', $config->activo ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="activo">Activo</label>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Tablas a excluir del sync (separadas por coma)</label>
                    <input type="text" name="tablas_excluir" class="form-control @error('tablas_excluir') is-invalid @enderror" value="{{ old('tablas_excluir', is_array($config->tablas_excluir ?? null) ? implode(', ', $config->tablas_excluir) : ($config->tablas_excluir ?? '')) }}" placeholder="migrations, password_resets, cache, sessions, db_sync_logs, db_sync_configs">
                    @error('tablas_excluir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Estas tablas no se comparan ni sincronizan.</div>
                </div>
                <div class="col-12">
                    <label class="form-label">Tablas solo estructura (sin datos, separadas por coma)</label>
                    <input type="text" name="tablas_solo_estructura" class="form-control @error('tablas_solo_estructura') is-invalid @enderror" value="{{ old('tablas_solo_estructura', is_array($config->tablas_solo_estructura ?? null) ? implode(', ', $config->tablas_solo_estructura) : ($config->tablas_solo_estructura ?? '')) }}" placeholder="migrations, permisos, roles (solo compara columnas)">
                    @error('tablas_solo_estructura')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Solo compara columnas/índices, no datos.</div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> {{ isset($config) ? 'Actualizar' : 'Guardar' }}</button>
            <a href="{{ route('admin.db-sync.index') }}" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Cancelar</a>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>