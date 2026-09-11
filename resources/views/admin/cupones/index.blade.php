@extends('layouts.volt')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold"><i class="fas fa-ticket me-2 text-success"></i>Cupones de Descuento</h3>
        <a href="{{ route('admin.cupones.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nuevo Cupón
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="text" name="buscar" class="form-control form-control-sm" placeholder="Buscar código..." value="{{ request('buscar') }}">
        </div>
        <div class="col-md-2">
            <select name="estado" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Todos</option>
                <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activos</option>
                <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivos</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter me-1"></i> Filtrar</button>
        </div>
    </form>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Tipo</th>
                        <th>Valor</th>
                        <th>Mín. Compra</th>
                        <th>Usos</th>
                        <th>Negocio</th>
                        <th>Vigencia</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cupones as $cupon)
                    <tr>
                        <td><strong class="text-success">{{ $cupon->codigo }}</strong></td>
                        <td>
                            <span class="badge bg-{{ $cupon->tipo === 'porcentaje' ? 'primary' : 'info' }}">
                                {{ $cupon->tipo === 'porcentaje' ? '%' : 'S/' }}
                            </span>
                            {{ $cupon->tipo === 'porcentaje' ? 'Porcentaje' : 'Monto fijo' }}
                        </td>
                        <td>
                            <strong>
                                {{ $cupon->tipo === 'porcentaje' ? $cupon->valor . '%' : 'S/ ' . number_format($cupon->valor, 2) }}
                            </strong>
                        </td>
                        <td>S/ {{ number_format($cupon->min_compra, 2) }}</td>
                        <td>
                            {{ $cupon->usos_actuales }}
                            @if($cupon->max_usos)
                                / {{ $cupon->max_usos }}
                            @else
                                / &infin;
                            @endif
                        </td>
                        <td>
                            @if($cupon->negocio)
                                <span class="badge bg-info">{{ $cupon->negocio->nombre }}</span>
                            @else
                                <span class="text-muted">Todos</span>
                            @endif
                        </td>
                        <td>
                            <small>
                                @if($cupon->fecha_inicio)
                                    {{ $cupon->fecha_inicio->format('d/m/Y') }}
                                @else
                                    Sin inicio
                                @endif
                                <br>
                                @if($cupon->fecha_fin)
                                    {{ $cupon->fecha_fin->format('d/m/Y') }}
                                @else
                                    Sin fin
                                @endif
                            </small>
                        </td>
                        <td>
                            <form action="{{ route('admin.cupones.toggle', $cupon) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-{{ $cupon->activo ? 'success' : 'secondary' }} border-0">
                                    <i class="fas fa-{{ $cupon->activo ? 'toggle-on' : 'toggle-off' }}"></i>
                                    {{ $cupon->activo ? 'Activo' : 'Inactivo' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('admin.cupones.edit', $cupon) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#enviarCuponModal"
                                    data-cupon-id="{{ $cupon->id }}"
                                    data-cupon-codigo="{{ $cupon->codigo }}"
                                    data-cupon-descuento="{{ $cupon->tipo === 'porcentaje' ? $cupon->valor . '%' : 'S/ ' . number_format($cupon->valor, 2) }}"
                                    title="Enviar a clientes">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                            <form action="{{ route('admin.cupones.destroy', $cupon) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este cupón?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="fas fa-ticket fa-2x mb-2 d-block"></i>
                            No hay cupones registrados
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $cupones->links() }}
    </div>
</div>

@if(session('wa_enlaces'))
<div class="card border-0 shadow-sm ms-3 me-3 mb-3">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <span><i class="fab fa-whatsapp me-2 text-success"></i> Enlaces de WhatsApp generados</span>
        <button type="button" class="btn-close btn-close-white" onclick="this.closest('.card').remove()"></button>
    </div>
    <div class="card-body">
        <div class="row g-2">
            @foreach(session('wa_enlaces') as $e)
            <div class="col-md-6 col-lg-4">
                <a href="{{ $e['enlace'] }}" target="_blank" class="btn btn-success w-100 text-start d-flex align-items-center justify-content-between">
                    <span><i class="fab fa-whatsapp me-2"></i>{{ $e['nombre'] }}</span>
                    <small>{{ $e['numero'] }}</small>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Modal Enviar Cupón -->
<div class="modal fade" id="enviarCuponModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form action="" method="POST" id="formEnviarCupon">
                @csrf
                <input type="hidden" name="todos" id="chkTodosHidden" value="0">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title"><i class="fas fa-paper-plane me-2"></i>Enviar Cupón</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info py-2 small" id="cuponResumen"></div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Canal de envío</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="canal" value="correo" id="canalCorreo" checked>
                                    <label class="form-check-label" for="canalCorreo"><i class="fas fa-envelope me-1"></i> Correo</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="canal" value="whatsapp" id="canalWhatsapp">
                                    <label class="form-check-label" for="canalWhatsapp"><i class="fab fa-whatsapp me-1"></i> WhatsApp</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Recomendar producto (opcional)</label>
                            <select name="producto_id" class="form-select">
                                <option value="">-- Sin producto --</option>
                                @foreach($productos as $p)
                                    <option value="{{ $p->id }}">{{ $p->titulo }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <hr class="my-1">
                            <div class="d-flex align-items-center gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="chkTodos">
                                    <label class="form-check-label" for="chkTodos"><strong>Enviar a todos los clientes</strong></label>
                                </div>
                                <div class="flex-grow-1">
                                    <select id="rubroFiltro" class="form-select form-select-sm">
                                        <option value="">-- Filtrar por rubro --</option>
                                        @foreach($rubros as $rubro)
                                            <option value="{{ $rubro->id }}">{{ $rubro->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Buscar clientes</label>
                            <div class="input-group">
                                <input type="text" id="buscarCliente" class="form-control" placeholder="Nombre, apellido o email..." autocomplete="off">
                                <button type="button" id="btnBuscarCliente" class="btn btn-outline-primary"><i class="fas fa-search"></i></button>
                            </div>
                            <div class="form-text small" id="contadorSeleccion"></div>
                        </div>
                        <div class="col-12">
                            <div id="listaClientes" class="border rounded p-2" style="max-height: 220px; overflow-y: auto;">
                                <div class="text-muted text-center py-3 small">Busca un cliente para seleccionarlo</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Mensaje personalizado (opcional)</label>
                            <textarea name="mensaje" id="mensajePersonalizado" class="form-control" rows="3"
                                      placeholder="Déjalo vacío para usar el mensaje automático con el cupón y el producto recomendado."></textarea>
                            <div class="form-text small" id="vistaPreviaWhatsapp"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i> Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    let clientesSeleccionados = {};

    document.addEventListener('DOMContentLoaded', function() {
        const modalEl = document.getElementById('enviarCuponModal');

        // Al abrir el modal, setear cupón y acción del form
        modalEl.addEventListener('show.bs.modal', function(e) {
            const btn = e.relatedTarget;
            const url = @json(url('admin/cupones')) + '/' + btn.dataset.cuponId + '/enviar';
            document.getElementById('formEnviarCupon').setAttribute('action', url);
            document.getElementById('cuponResumen').innerHTML =
                'Enviando código <strong>' + btn.dataset.cuponCodigo + '</strong> - ' +
                '<strong>' + btn.dataset.cuponDescuento + '</strong> de descuento.';
            // reset
            clientesSeleccionados = {};
            document.getElementById('chkTodos').checked = false;
            document.getElementById('chkTodosHidden').value = 0;
            document.getElementById('rubroFiltro').value = '';
            document.getElementById('buscarCliente').value = '';
            document.getElementById('mensajePersonalizado').value = '';
            document.getElementById('listaClientes').innerHTML =
                '<div class="text-muted text-center py-3 small">Busca un cliente para seleccionarlo</div>';
            actualizarContador();
        });

        // Cambio de canal o mensaje: refrescar vista previa WhatsApp
        document.querySelectorAll('input[name="canal"]').forEach(r =>
            r.addEventListener('change', actualizarVistaPrevia)
        );
        document.getElementById('mensajePersonalizado').addEventListener('input', actualizarVistaPrevia);
        document.querySelector('select[name="producto_id"]')?.addEventListener('change', actualizarVistaPrevia);

        // "Enviar a todos" deshabilita la selección individual
        document.getElementById('chkTodos').addEventListener('change', function() {
            document.getElementById('chkTodosHidden').value = this.checked ? 1 : 0;
            const sel = document.getElementById('listaClientes');
            sel.classList.toggle('opacity-50', this.checked);
            Array.from(sel.querySelectorAll('input[type="checkbox"]')).forEach(cb => cb.disabled = this.checked);
        });

        // Búsqueda de clientes
        function buscar() {
            const q = document.getElementById('buscarCliente').value.trim();
            const rubroId = document.getElementById('rubroFiltro').value;
            const url = @json(route('admin.cupones.clientes')) + '?q=' + encodeURIComponent(q) + (rubroId ? '&rubro_id=' + rubroId : '');
            fetch(url)
                .then(r => r.json())
                .then(lista => {
                    const box = document.getElementById('listaClientes');
                    if (!lista.length) {
                        box.innerHTML = '<div class="text-muted text-center py-3 small">Sin resultados</div>';
                        return;
                    }
                    box.innerHTML = lista.map(cli => {
                        const checked = clientesSeleccionados[cli.id] ? 'checked' : '';
                        return `<label class="d-flex align-items-center gap-2 p-1 border-bottom small ${clientesSeleccionados[cli.id] ? 'bg-success-subtle' : ''}">
                            <input type="checkbox" name="usuarios[]" value="${cli.id}" ${checked} class="form-check-input m-0">
                            <span class="flex-grow-1">${cli.nombre}${cli.email ? ` <small class="text-muted">(${cli.email})</small>` : ''}</span>
                            ${cli.celular ? `<small class="text-muted"><i class="fab fa-whatsapp"></i> ${cli.celular}</small>` : ''}
                        </label>`;
                    }).join('');
                    box.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                        cb.disabled = document.getElementById('chkTodos').checked;
                        cb.addEventListener('change', function() {
                            if (this.checked) clientesSeleccionados[this.value] = true;
                            else delete clientesSeleccionados[this.value];
                            actualizarContador();
                            this.closest('label').classList.toggle('bg-success-subtle', this.checked);
                        });
                    });
                });
        }

        document.getElementById('btnBuscarCliente').addEventListener('click', buscar);
        document.getElementById('buscarCliente').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') { e.preventDefault(); buscar(); }
        });
        document.getElementById('rubroFiltro').addEventListener('change', buscar);

        // Validar que se haya elegido destinatario antes de enviar
        document.getElementById('formEnviarCupon').addEventListener('submit', function(e) {
            if (!document.getElementById('chkTodos').checked && !Object.keys(clientesSeleccionados).length) {
                e.preventDefault();
                e.stopPropagation();
                alert('Selecciona al menos un cliente o marca "Enviar a todos".');
            }
        });
    });

    function actualizarContador() {
        const n = Object.keys(clientesSeleccionados).length;
        document.getElementById('contadorSeleccion').textContent =
            n ? n + ' cliente(s) seleccionado(s)' : 'Ningún cliente seleccionado';
        actualizarVistaPrevia();
    }

    function actualizarVistaPrevia() {
        const canal = document.querySelector('input[name="canal"]:checked');
        const texto = document.getElementById('mensajePersonalizado').value.trim();
        const wa = document.getElementById('vistaPreviaWhatsapp');
        if (!canal || canal.value !== 'whatsapp') { wa.textContent = ''; return; }
        wa.textContent = texto
            ? 'Vista previa: ' + texto
            : 'Vista previa: se usará el mensaje automático con el cupón y el producto recomendado.';
    }
</script>
@endsection

@endsection
