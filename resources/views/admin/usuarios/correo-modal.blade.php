<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar correo a usuarios seleccionados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/css/all.min.css" rel="stylesheet">
    <style>
        body { padding: 20px; background: #f8f9fa; }
        .destinatarios { max-height: 150px; overflow-y: auto; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <h5 class="mb-3"><i class="fas fa-envelope me-2"></i> Enviar correo a usuarios seleccionados</h5>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.usuarios.bulk-correo') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_ids" value="{{ implode(',', $ids) }}">

            <div class="card mb-3">
                <div class="card-header">
                    <strong>Destinatarios ({{ $usuarios->count() }})</strong>
                </div>
                <div class="card-body">
                    <div class="destinatarios">
                        @foreach($usuarios as $u)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="user_ids[]" value="{{ $u->id }}" id="dest_{{ $u->id }}" checked>
                                <label class="form-check-label" for="dest_{{ $u->id }}">
                                    {{ $u->nombre }} {{ $u->apellidos }} - {{ $u->email }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <strong>Remitente</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Negocio</label>
                            <select id="negocioSelect" class="form-select" name="negocio_id">
                                <option value="">-- Seleccionar negocio --</option>
                                @foreach($negocios as $n)
                                    <option value="{{ $n->id }}" data-nombre="{{ $n->nombre }}" data-emails="{{ implode(',', $n->emails) }}">{{ $n->nombre }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Al elegir negocio se autocompletan el nombre y el correo del remitente.</small>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label">Nombre del remitente</label>
                                <input type="text" class="form-control" name="from_name" id="fromNameInput">
                            </div>
                            <label class="form-label">Correo remitente</label>
                            <select class="form-select" name="from_email" id="fromEmailSelect">
                                <option value="">-- Seleccionar correo --</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <strong>Cargar desde plantilla</strong>
                </div>
                <div class="card-body">
                    <select class="form-select" id="plantillaSelect">
                        <option value="">-- Seleccionar plantilla --</option>
                        @foreach($plantillas as $p)
                            <option value="{{ $p->id }}" data-asunto="{{ $p->asunto }}" data-contenido="{{ $p->contenido }}">{{ $p->nombre }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Al seleccionar una plantilla se rellenarán el asunto y el contenido.</small>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <strong>Mensaje</strong>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="asunto" class="form-label">Asunto</label>
                        <input type="text" name="asunto" id="asunto" class="form-control @error('asunto') is-invalid @enderror" required>
                        @error('asunto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="contenido" class="form-label">Contenido</label>
                        <textarea name="contenido" id="contenido" rows="10" class="form-control @error('contenido') is-invalid @enderror" required></textarea>
                        @error('contenido')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Variables disponibles: {cliente}, {nombre}, {apellidos}, {correo}, {telefono}, {empresa}</small>
                    </div>
                    <div class="mb-3">
                        <label for="adjuntos" class="form-label">Adjuntos (múltiples)</label>
                        <input type="file" name="adjuntos[]" id="adjuntos" class="form-control" multiple>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <strong>Guardar como lista de envío (opcional)</strong>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="campaign_name" class="form-label">Nombre de la lista</label>
                        <input type="text" name="campaign_name" id="campaign_name" class="form-control" placeholder="Ej: Newsletter Octubre 2026, Aviso contraseña, Promoción Black Friday...">
                        <small class="text-muted">Si se rellena, se guardará un registro con este nombre, la fecha y los totales (enviados/fallidos) para consultarlo luego en "Historial de Envíos".</small>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" onclick="window.close()">
                    <i class="fas fa-times me-1"></i> Cerrar
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-1"></i> Enviar
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('plantillaSelect').addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            if (option.value) {
                document.getElementById('asunto').value = option.dataset.asunto || '';
                document.getElementById('contenido').value = option.dataset.contenido || '';
            }
        });

        function rellenarNegocio() {
            const select = document.getElementById('negocioSelect');
            const option = select.options[select.selectedIndex];
            const correo = document.getElementById('fromEmailSelect');
            correo.innerHTML = '<option value="">-- Seleccionar correo --</option>';
            if (!option.value) return;
            document.getElementById('fromNameInput').value = option.dataset.nombre || '';
            (option.dataset.emails || '').split(',').forEach(function(email) {
                email = email.trim();
                if (!email) return;
                const opt = document.createElement('option');
                opt.value = email;
                opt.textContent = email;
                correo.appendChild(opt);
            });
            if (correo.options.length > 1) {
                correo.selectedIndex = 1;
            }
        }

        document.getElementById('negocioSelect').addEventListener('change', rellenarNegocio);
        (function() {
            const select = document.getElementById('negocioSelect');
            if (select.options.length > 1) {
                select.selectedIndex = 1;
                rellenarNegocio();
            }
        })();
    </script>
</body>
</html>
