<div class="modal fade" id="clienteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Seleccionar Cliente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-bold">Lista de clientes</span>
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#nuevoClienteModal">
                        <i class="fas fa-plus me-1"></i> Nuevo Cliente
                    </button>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-md-4">
                        <input type="text" id="buscarCliente" class="form-control" placeholder="Buscar por nombre o email...">
                    </div>
                    <div class="col-md-2">
                        <select id="filtroRol" class="form-select">
                            <option value="">Todos los roles</option>
                            @foreach($roles as $rol)
                                <option value="{{ $rol->nombre }}">{{ $rol->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="filtroRubro" class="form-select">
                            <option value="">Todos los rubros</option>
                            @foreach($rubros as $rubro)
                                <option value="{{ $rubro->nombre }}">{{ $rubro->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="filtroPuntuacion" class="form-select">
                            <option value="">Cualquier puntuación</option>
                            <option value="0-2">0 - 2</option>
                            <option value="3-5">3 - 5</option>
                            <option value="6-8">6 - 8</option>
                            <option value="9-10">9 - 10</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <span class="badge bg-secondary fs-6 p-2" id="clienteCount">{{ count($usuarios) }} clientes</span>
                    </div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-md-3">
                        <input type="date" id="filtroFechaDesde" class="form-control" placeholder="Fecha desde">
                    </div>
                    <div class="col-md-3">
                        <input type="date" id="filtroFechaHasta" class="form-control" placeholder="Fecha hasta">
                    </div>
                    <div class="col-md-3">
                        <input type="text" id="filtroEmpresa" class="form-control" placeholder="Empresa...">
                    </div>
                    <div class="col-md-3">
                        <select id="filtroDocumento" class="form-select">
                            <option value="">Tipo Doc.</option>
                            <option value="RUC">RUC</option>
                            <option value="DNI">DNI</option>
                            <option value="CE">CE</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive" style="max-height:350px;overflow-y:auto;">
                    <table class="table table-hover table-sm mb-0" id="tablaClientes">
                        <thead class="table-dark sticky-top">
                            <tr>
                                <th style="width:45px;">ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Rubros</th>
                                <th style="width:70px;">Punt.</th>
                                <th>Empresa</th>
                                <th>Doc.</th>
                                <th style="width:70px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios as $u)
                            <tr data-id="{{ $u->id }}"
                                data-nombre="{{ $u->nombre }}"
                                data-apellidos="{{ $u->apellidos }}"
                                data-email="{{ $u->email }}"
                                data-fecha="{{ $u->fecha ? \Carbon\Carbon::parse($u->fecha)->format('Y-m-d') : '' }}"
                                data-roles="{{ $u->roles->pluck('nombre')->join(',') }}"
                                data-rubros="{{ $u->rubros->pluck('nombre')->join(',') }}"
                                data-puntuacion="{{ $u->scores->puntuacion ?? 0 }}"
                                data-empresa="{{ $u->profile->empresa ?? '' }}"
                                data-documento="{{ $u->profile->tipo_documento ?? '' }}">
                                <td>{{ $u->id }}</td>
                                <td>{{ $u->nombre }} {{ $u->apellidos }}</td>
                                <td>{{ $u->email }}</td>
                                <td>
                                    @foreach($u->roles as $rol)
                                        <span class="badge bg-info me-1">{{ $rol->nombre }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($u->rubros as $rubro)
                                        <span class="badge bg-secondary me-1">{{ $rubro->nombre }}</span>
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    @php $punt = $u->scores->puntuacion ?? 0; @endphp
                                    <span class="badge bg-{{ $punt >= 7 ? 'success' : ($punt >= 4 ? 'warning' : 'danger') }}">
                                        {{ $punt }}
                                    </span>
                                </td>
                                <td>{{ $u->profile->empresa ?? '—' }}</td>
                                <td>{{ $u->profile->tipo_documento ?? '—' }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm seleccionar-cliente">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal para crear nuevo cliente --}}
<div class="modal fade" id="nuevoClienteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Nuevo Cliente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formNuevoCliente">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Apellidos</label>
                        <input type="text" name="apellidos" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control">
                    </div>
                    <div id="nuevoClienteError" class="alert alert-danger d-none"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success" id="btnGuardarCliente">
                        <i class="fas fa-save me-1"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    const buscar = document.getElementById('buscarCliente');
    const filtroRol = document.getElementById('filtroRol');
    const filtroRubro = document.getElementById('filtroRubro');
    const filtroPunt = document.getElementById('filtroPuntuacion');
    const filtroFechaDsd = document.getElementById('filtroFechaDesde');
    const filtroFechaHst = document.getElementById('filtroFechaHasta');
    const filtroEmpresa = document.getElementById('filtroEmpresa');
    const filtroDoc = document.getElementById('filtroDocumento');
    const tabla = document.getElementById('tablaClientes');
    const rows = tabla.querySelectorAll('tbody tr');
    const contador = document.getElementById('clienteCount');

    function filtrar() {
        const texto = buscar.value.toLowerCase();
        const rolSel = filtroRol.value.toLowerCase();
        const rubroSel = filtroRubro.value.toLowerCase();
        const puntSel = filtroPunt.value;
        const fechaDsd = filtroFechaDsd.value;
        const fechaHst = filtroFechaHst.value;
        const empresaTxt = filtroEmpresa.value.toLowerCase();
        const docSel = filtroDoc.value;

        let visibles = 0;

        rows.forEach(function(row) {
            const nombreCompleto = (row.dataset.nombre + ' ' + row.dataset.apellidos).toLowerCase();

            // texto
            if (texto && !nombreCompleto.includes(texto) && !row.dataset.email.toLowerCase().includes(texto)) {
                row.style.display = 'none'; return;
            }

            // rol
            if (rolSel) {
                const roles = row.dataset.roles.toLowerCase();
                if (!roles.split(',').some(function(r) { return r.trim() === rolSel; })) {
                    row.style.display = 'none'; return;
                }
            }

            // rubro
            if (rubroSel) {
                const rubros = row.dataset.rubros.toLowerCase();
                if (!rubros.split(',').some(function(r) { return r.trim() === rubroSel; })) {
                    row.style.display = 'none'; return;
                }
            }

            // puntuacion
            if (puntSel) {
                const punt = parseInt(row.dataset.puntuacion) || 0;
                const parts = puntSel.split('-');
                const min = parseInt(parts[0]);
                const max = parseInt(parts[1]);
                if (punt < min || punt > max) {
                    row.style.display = 'none'; return;
                }
            }

            // fecha desde
            if (fechaDsd) {
                const fecha = row.dataset.fecha;
                if (!fecha || fecha < fechaDsd) {
                    row.style.display = 'none'; return;
                }
            }

            // fecha hasta
            if (fechaHst) {
                const fecha = row.dataset.fecha;
                if (!fecha || fecha > fechaHst) {
                    row.style.display = 'none'; return;
                }
            }

            // empresa
            if (empresaTxt) {
                const emp = (row.dataset.empresa || '').toLowerCase();
                if (!emp.includes(empresaTxt)) {
                    row.style.display = 'none'; return;
                }
            }

            // tipo documento
            if (docSel) {
                const doc = row.dataset.documento;
                if (doc !== docSel) {
                    row.style.display = 'none'; return;
                }
            }

            row.style.display = '';
            visibles++;
        });

        contador.textContent = visibles + ' clientes';
    }

    // eventos
    buscar.addEventListener('input', filtrar);
    filtroRol.addEventListener('change', filtrar);
    filtroRubro.addEventListener('change', filtrar);
    filtroPunt.addEventListener('change', filtrar);
    filtroFechaDsd.addEventListener('change', filtrar);
    filtroFechaHst.addEventListener('change', filtrar);
    filtroEmpresa.addEventListener('input', filtrar);
    filtroDoc.addEventListener('change', filtrar);

    function asignar(row) {
        const nombre = row.dataset.nombre || '';
        const apellidos = row.dataset.apellidos || '';
        document.querySelector('[name="cliente"]').value = (nombre + ' ' + apellidos).trim();
        const correoInput = document.querySelector('[name="correo"]');
        if (correoInput) correoInput.value = row.dataset.email || '';
        const idInput = document.querySelector('[name="cliente_id"]');
        if (idInput) idInput.value = row.dataset.id || '';
    }

    document.querySelectorAll('.seleccionar-cliente').forEach(function(btn) {
        btn.addEventListener('click', function() {
            asignar(this.closest('tr'));
        });
    });

    document.getElementById('clienteModal').addEventListener('click', function(e) {
        const btn = e.target.closest('.seleccionar-cliente');
        if (btn) asignar(btn.closest('tr'));
    });

    // --- Nuevo cliente ---
    const formNuevo = document.getElementById('formNuevoCliente');
    const btnGuardar = document.getElementById('btnGuardarCliente');
    const errorDiv = document.getElementById('nuevoClienteError');

    formNuevo.addEventListener('submit', async function(e) {
        e.preventDefault();
        btnGuardar.disabled = true;
        btnGuardar.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Guardando...';
        errorDiv.classList.add('d-none');

        try {
            const res = await fetch('{{ route("admin.cotizaciones.crearCliente") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    nombre: formNuevo.nombre.value,
                    apellidos: formNuevo.apellidos.value,
                    email: formNuevo.email.value,
                    telefono: formNuevo.telefono.value,
                }),
            });

            const data = await res.json();

            if (!res.ok) {
                throw new Error(data.message || 'Error al crear cliente');
            }

            // seleccionar el nuevo cliente
            document.querySelector('[name="cliente"]').value = (data.user.nombre + ' ' + data.user.apellidos).trim();
            const correoInput = document.querySelector('[name="correo"]');
            if (correoInput) correoInput.value = data.user.email || '';
            const idInput = document.querySelector('[name="cliente_id"]');
            if (idInput) idInput.value = data.user.id || '';
            const telefonoInput = document.querySelector('[name="telefono"]');
            if (telefonoInput) telefonoInput.value = formNuevo.telefono.value;

            // cerrar ambos modales
            bootstrap.Modal.getInstance(document.getElementById('nuevoClienteModal')).hide();
            bootstrap.Modal.getInstance(document.getElementById('clienteModal')).hide();

            formNuevo.reset();
        } catch (err) {
            errorDiv.textContent = err.message;
            errorDiv.classList.remove('d-none');
        } finally {
            btnGuardar.disabled = false;
            btnGuardar.innerHTML = '<i class="fas fa-save me-1"></i> Guardar';
        }
    });
})();
</script>
@endpush
