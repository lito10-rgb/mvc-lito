@extends('layouts.volt')
@section('content')

<form method="GET" action="{{ route('admin.usuarios.index') }}" class="mb-3">
    <div class="row g-2 align-items-end">

        <!-- Nombre -->
        <div class="col-md-2">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control"
                   value="{{ request('nombre') }}">
        </div>

        <!-- Empresa -->
        <div class="col-md-2">
            <label class="form-label">Empresa</label>
            <input type="text" name="empresa" class="form-control"
                   value="{{ request('empresa') }}">
        </div>

        <!-- RUC -->
        <div class="col-md-2">
            <label class="form-label">RUC/DNI</label>
            <input type="text" name="ruc" class="form-control"
       placeholder="DNI o RUC"
       value="{{ request('ruc') }}">
        </div>

        <!-- Rol -->
        <div class="col-md-2">
            <label class="form-label">Rol</label>
            <select name="role_id" class="form-control">
                <option value="">— Todos —</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}"
                        {{ request('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Rubro -->
        <div class="col-md-2">
            <label class="form-label">Rubro</label>
            <select name="rubro_id" class="form-control">
                <option value="">— Todos —</option>
                @foreach($rubros as $rubro)
                    <option value="{{ $rubro->id }}"
                        {{ request('rubro_id') == $rubro->id ? 'selected' : '' }}>
                        {{ $rubro->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Fecha desde -->
        <div class="col-md-2">
            <label class="form-label">Desde</label>
            <input type="date" name="fecha_desde" class="form-control"
                   value="{{ request('fecha_desde') }}">
        </div>

        <!-- Fecha hasta -->
        <div class="col-md-2">
            <label class="form-label">Hasta</label>
            <input type="date" name="fecha_hasta" class="form-control"
                   value="{{ request('fecha_hasta') }}">
        </div>

        <!-- Negocio (origen) -->
        <div class="col-md-2">
            <label class="form-label">Negocio</label>
            <select name="negocio" class="form-control">
                <option value="">— Todos —</option>
                @foreach($dominios as $dom)
                    <option value="{{ $dom }}"
                        {{ request('negocio') == $dom ? 'selected' : '' }}>
                        {{ $dom }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Orden por -->
        <div class="col-md-2">
            <label class="form-label">Ordenar</label>
            <select name="orden" class="form-control">
                <option value="">— Normal —</option>
                <option value="score"
                    {{ request('orden') == 'score' ? 'selected' : '' }}>
                    ⭐ Mejor cliente (score desc)
                </option>
                <option value="ultimos_inscritos"
                    {{ request('orden') == 'ultimos_inscritos' ? 'selected' : '' }}>
                    🆕 Últimos inscritos
                </option>
                <option value="ultimos_inscritos_asc"
                    {{ request('orden') == 'ultimos_inscritos_asc' ? 'selected' : '' }}>
                    🆔 Primeros inscritos
                </option>
                <option value="nombre_asc"
                    {{ request('orden') == 'nombre_asc' ? 'selected' : '' }}>
                    🔼 Nombre A-Z
                </option>
                <option value="nombre_desc"
                    {{ request('orden') == 'nombre_desc' ? 'selected' : '' }}>
                    🔽 Nombre Z-A
                </option>
                <option value="email_asc"
                    {{ request('orden') == 'email_asc' ? 'selected' : '' }}>
                    🔼 Email A-Z
                </option>
                <option value="email_desc"
                    {{ request('orden') == 'email_desc' ? 'selected' : '' }}>
                    🔽 Email Z-A
                </option>
            </select>
        </div>

        <!-- BOTONES -->
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-50">
                🔍 Buscar
            </button>

            <a href="{{ route('admin.usuarios.index') }}"
               class="btn btn-outline-secondary w-50">
                🧹 Limpiar
            </a>
        </div>

    </div>
</form>

<form method="POST" action="{{ route('admin.usuarios.negocio.bulk') }}" id="bulk-form">
    @csrf
    @method('PUT')

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Usuarios</h5>
    <div>
        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">
          Nuevo Usuario
        </a>
    </div>
  </div>

  <div class="card-body">
    {{-- Bulk actions bar --}}
    <div class="row g-2 mb-3 align-items-end" id="bulk-bar">
        <div class="col-md-2">
            <label class="form-label small">Seleccionados: <span id="selected-count">0</span></label>
            <button type="button" class="btn btn-sm btn-outline-primary d-block" onclick="toggleAll()">✔ Seleccionar / Deseleccionar</button>
        </div>
        <div class="col-md-3">
            <label class="form-label small">Cambiar Negocio a:</label>
            <select name="negocio" class="form-control form-control-sm">
                <option value="">— Sin negocio —</option>
                @foreach($dominios as $dom)
                    <option value="{{ $dom }}">{{ $dom }}</option>
                @endforeach
                <option value="__custom__">→ Otro...</option>
            </select>
        </div>
        <div class="col-md-2" id="custom-negocio-wrap" style="display:none">
            <label class="form-label small">Nuevo negocio:</label>
            <input type="text" name="negocio_custom" class="form-control form-control-sm" placeholder="ej: otro-dominio.com">
        </div>
        <div class="col-md-4 d-flex gap-1 flex-wrap">
            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('¿Cambiar negocio a los usuarios seleccionados?')">
                <i class="fas fa-sync me-1"></i> Cambiar
            </button>
            <button type="button" class="btn btn-sm btn-info" onclick="abrirCorreoMasivo()">
                <i class="fas fa-envelope me-1"></i> Enviar Correo
            </button>
            <button type="button" class="btn btn-sm btn-warning" onclick="abrirAvisoPassword()">
                <i class="fas fa-key me-1"></i> Aviso Contraseña
            </button>
            <button type="button" class="btn btn-sm btn-danger" onclick="bulkDelete()">
                <i class="fas fa-trash me-1"></i> Eliminar
            </button>
        </div>
    </div>

    <table class="table table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th><input type="checkbox" id="check-all" onclick="toggleCheckbox(this)"></th>
          <th>ID</th>
          <th>Nombre</th>
          <th>Email</th>
          <th>Web</th>
          <th>Negocio</th>
          <th>DNI/RUC</th>
          <th>Nivel</th>
          <th>Score</th>
          <th>Acciones</th>
        </tr>
      </thead>

    <tbody>
@foreach($users as $user)
    @php
        $rolColors = [
            'cliente'              => '#B20000',
            'proveedor-cliente'    => '#806700',
            'cliente-vendedor'     => '#FF0420',
            'vendedor'             => '#FF0890',
            'cotizante'            => '#FFFF26',
            'proveedor'            => '#80FF00',
            'admin'                => '#DDDDDD',
            'prospecto'            => '#BFFFFF', // sin rol
        ];

        $roles = $user->roles->pluck('nombre')->sort()->values();


if ($roles->isEmpty()) {
    $bgColor = '#FFFFFF'; // sin asignar nada todavía
} else {
    $rolKey  = $roles->implode('-');
    $bgColor = $rolColors[$rolKey] ?? '#FFFFFF';
}
@endphp

<tr>
    <td style="background-color: {{ $bgColor }}">
        <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="user-check" onchange="updateSelected()">
    </td>
    <td style="background-color: {{ $bgColor }}">{{ $user->id }}</td>
    <td style="background-color: {{ $bgColor }}">{{ $user->nombre }} {{ $user->apellidos }}</td>
    <td style="background-color: {{ $bgColor }};">
                        <strong>{{ $user->email }}</strong><br>
                        <small>
                            Tel: {{ $user->profile?->telefono ?? '—' }} | 
                            Páis: {{ isset($paises[$user->profile?->pais]) ? $paises[$user->profile->pais] : '—' }}
                        </small>
                    </td>
    <td class="text-center" style="background-color: {{ $bgColor }};">
    @if(!empty($user->profile?->web))
        <a href="{{ Str::startsWith($user->profile->web, 'http') 
                    ? $user->profile->web 
                    : 'https://' . $user->profile->web }}"
           target="_blank"
           class="btn btn-sm btn-info"
           title="Web">
            🌐 Web
        </a>

    @elseif(!empty($user->profile?->web2))
        <a href="{{ Str::startsWith($user->profile->web2, 'http') 
                    ? $user->profile->web2 
                    : 'https://' . $user->profile->web2 }}"
           target="_blank"
           class="btn btn-sm btn-secondary"
           title="Web secundaria">
            🌐 Web
        </a>

    @elseif(!empty($user->profile?->facebook))
        <a href="{{ Str::startsWith($user->profile->facebook, 'http') 
                    ? $user->profile->facebook 
                    : 'https://facebook.com/' . $user->profile->facebook }}"
           target="_blank"
           class="btn btn-sm btn-primary"
           title="Facebook">
            📘 FB
        </a>

    @elseif(!empty($user->profile?->instagram))
        <a href="{{ Str::startsWith($user->profile->instagram, 'http') 
                    ? $user->profile->instagram 
                    : 'https://instagram.com/' . $user->profile->instagram }}"
           target="_blank"
           class="btn btn-sm btn-danger"
           title="Instagram">
            📸 IG
        </a>

    @else
        —
    @endif
</td>

    <td style="background-color: {{ $bgColor }}">
        @if($user->negocio)
            <span class="badge bg-secondary">{{ $user->negocio }}</span>
        @else
            —
        @endif
    </td>

    <td style="background-color: {{ $bgColor }}">{{ $user->profile->num_documento ?? '-' }}</td>
    <td style="background-color: {{ $bgColor }}">
        <span class="badge bg-info">{{ $user->scores->nivel ?? 'bronce' }}</span>
    </td>
    <td style="background-color: {{ $bgColor }};">
    {{ optional($user->scores)->puntuacion ?? 0 }}
</td>

    <td style="background-color: {{ $bgColor }};">
    @php
        $cotizarQuery = array_filter([
            'cliente'    => trim(($user->nombre ?? '') . ' ' . ($user->apellidos ?? '')),
            'correo'     => $user->email ?? null,
            'telefono'   => $user->profile?->telefono ?? null,
            'cliente_id' => $user->id,
        ], fn($v) => !is_null($v) && $v !== '');
    @endphp
    <a href="{{ route('admin.cotizaciones.create', $cotizarQuery) }}"
       class="btn btn-sm btn-success"
       title="Cotizar este cliente"
       target="_blank">
        💲 Cotizar
    </a>

    <a href="{{ route('admin.usuarios.edit', $user) }}"
       class="btn btn-sm btn-warning"
       title="Editar">
        ✏️
    </a>

    @php
        $userClaves = $user->permisosEfectivos()->pluck('clave')->all();
        $userExtras = $user->permisos->pluck('clave')->all();
        $userEsAdmin = $user->esAdmin();
    @endphp
    <button type="button"
            class="btn btn-sm btn-dark btn-permisos"
            title="Ver / editar permisos"
            data-user-id="{{ $user->id }}"
            data-user-nombre="{{ $user->nombre }} {{ $user->apellidos }}"
            data-user-email="{{ $user->email }}"
            data-user-esadmin="{{ $userEsAdmin ? '1' : '0' }}"
            data-user-claves="{{ json_encode($userClaves) }}"
            data-user-extras="{{ json_encode($userExtras) }}"
            data-puede-editar="{{ auth()->user()->puede('permisos.gestionar') ? '1' : '0' }}">
        🔑 Permisos
    </button>

    <button type="button"
            class="btn btn-sm btn-danger"
            title="Eliminar"
            onclick="eliminarUno({{ $user->id }})">
        🗑️
    </button>
</td>

</tr>

@endforeach
</tbody>

    </table>

    {{ $users->links() }}
  </div>
</div>

</form>{{-- end bulk-form --}}

<form method="POST" action="{{ route('admin.usuarios.destroy.bulk') }}" id="bulk-delete-form">
    @csrf
    @method('DELETE')
    <div class="d-none" id="bulk-delete-inputs"></div>
</form>

{{-- ============ MODAL PERMISOS ============ --}}
<div class="modal fade" id="modalPermisos" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <form method="POST" action="" id="permisos-form" class="modal-content">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title">🔑 Permisos del usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <strong id="permisos-user-nombre"></strong>
          <div class="text-muted small" id="permisos-user-email"></div>
        </div>

        <div id="permisos-admin-msg" class="alert alert-danger d-none">
          Este usuario es <strong>admin</strong> y tiene <strong>acceso total</strong> automáticamente. No requiere permisos adicionales.
        </div>

        <div id="permisos-rol-msg" class="alert alert-info d-none">
          Una parte de estos permisos viene de sus <strong>roles</strong> (no editable aquí). Los que tenga marcados en verde son permisos extra asignados manualmente.
        </div>

        <div class="row g-2" id="permisos-listado">
          @foreach($modulos as $modulo => $permisosModulo)
            <div class="col-md-6">
              <div class="card border-primary h-100">
                <div class="card-header py-1">
                  <strong class="small text-primary">{{ ucfirst($modulo) }}</strong>
                  <label class="small float-end mb-0">
                    <input type="checkbox" class="marcar-modulo-perm" data-modulo="{{ $modulo }}"> todo
                  </label>
                </div>
                <div class="card-body py-1">
                  @foreach($permisosModulo as $p)
                    <div class="form-check">
                      <input type="checkbox"
                             class="form-check-input perm-checkbox"
                             name="permisos[]"
                             value="{{ $p->clave }}"
                             id="perm-modal-{{ $p->id }}"
                             data-modulo="{{ $modulo }}">
                      <label class="form-check-label small" for="perm-modal-{{ $p->id }}">
                        {{ $p->etiqueta }}
                      </label>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" id="permisos-save-btn"><i class="fas fa-save me-1"></i> Guardar permisos extra</button>
      </div>
    </form>
  </div>
</div>

@section('scripts')
<style>
.perm-extra { background: #d1e7dd; border-radius: 4px; padding: 2px 6px; }
.perm-extra .form-check-label { color: #0f5132; font-weight: 500; }
</style>
<script>
function toggleCheckbox(master) {
    document.querySelectorAll('.user-check').forEach(c => c.checked = master.checked);
    updateSelected();
}

function updateSelected() {
    const n = document.querySelectorAll('.user-check:checked').length;
    document.getElementById('selected-count').textContent = n;
}

function toggleAll() {
    const master = document.getElementById('check-all');
    master.checked = !master.checked;
    toggleCheckbox(master);
}

function bulkDelete() {
    const checked = document.querySelectorAll('.user-check:checked');
    if (checked.length === 0) {
        alert('No has seleccionado ningún usuario.');
        return;
    }
    if (!confirm('¿Eliminar ' + checked.length + ' usuario(s) seleccionado(s)? Esta acción no se puede deshacer.')) {
        return;
    }
    const container = document.getElementById('bulk-delete-inputs');
    container.innerHTML = '';
    checked.forEach(c => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'user_ids[]';
        input.value = c.value;
        container.appendChild(input);
    });
    document.getElementById('bulk-delete-form').submit();
}

function eliminarUno(id) {
    if (!confirm('�Eliminar usuario?')) {
        return;
    }
    const container = document.getElementById('bulk-delete-inputs');
    container.innerHTML = '';
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'user_ids[]';
    input.value = id;
    container.appendChild(input);
    document.getElementById('bulk-delete-form').submit();
}

document.querySelector('[name="negocio"]')?.addEventListener('change', function() {
    document.getElementById('custom-negocio-wrap').style.display = this.value === '__custom__' ? 'block' : 'none';
});

/* ===== Modal Permisos ===== */
const permModal = document.getElementById('modalPermisos');
const permForm = document.getElementById('permisos-form');
const permCheckboxes = () => document.querySelectorAll('#permisos-listado .perm-checkbox');

function abrirModalPermisos(btn) {
    const id = btn.dataset.userId;
    const nombre = btn.dataset.userNombre;
    const email = btn.dataset.userEmail;
    const esAdmin = btn.dataset.userEsadmin === '1';
    const claves = JSON.parse(btn.dataset.userClaves || '[]');
    const extras = JSON.parse(btn.dataset.userExtras || '[]');
    const puedeEditar = btn.dataset.puedeEditar === '1';

    document.getElementById('permisos-user-nombre').textContent = nombre;
    document.getElementById('permisos-user-email').textContent = email;
    permForm.action = '{{ route('admin.permisos.usuario.guardar', ['user' => '__ID__']) }}'.replace('__ID__', id);

    const adminMsg = document.getElementById('permisos-admin-msg');
    const rolMsg = document.getElementById('permisos-rol-msg');

    if (esAdmin) {
        adminMsg.classList.remove('d-none');
        rolMsg.classList.add('d-none');
        document.getElementById('permisos-save-btn').style.display = 'none';
        permCheckboxes().forEach(cb => {
            cb.checked = true;
            cb.disabled = true;
            cb.closest('.form-check').classList.add('text-muted');
        });
    } else {
        adminMsg.classList.add('d-none');
        document.getElementById('permisos-save-btn').style.display = puedeEditar ? '' : 'none';
        rolMsg.classList.toggle('d-none', !puedeEditar);
        const clavesSet = new Set(claves);
        const extrasSet = new Set(extras);
        permCheckboxes().forEach(cb => {
            cb.disabled = !puedeEditar;
            const label = cb.closest('.form-check');
            label.querySelector('.perm-origen')?.remove();
            const isDirect = extrasSet.has(cb.value);
            const isFromRole = clavesSet.has(cb.value) && !isDirect;
            cb.checked = isDirect;
            if (isFromRole) {
                const span = document.createElement('span');
                span.className = 'perm-origen badge bg-info ms-1';
                span.textContent = 'por rol';
                label.append(span);
            }
            label.classList.toggle('perm-extra', isDirect);
        });
    }

    bootstrap.Modal.getOrCreateInstance(permModal).show();
}

document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-permisos');
    if (btn) abrirModalPermisos(btn);
});

document.querySelectorAll('.marcar-modulo-perm').forEach(sw => {
    sw.addEventListener('change', function () {
        const modulo = this.dataset.modulo;
        document.querySelectorAll('#permisos-listado .perm-checkbox[data-modulo="' + modulo + '"]')
            .forEach(cb => { if (!cb.disabled) cb.checked = this.checked; });
    });
});
function abrirCorreoMasivo(){const checked=document.querySelectorAll('.user-check:checked');if(checked.length===0){alert('No has seleccionado ning�n usuario.');return;}const ids=Array.from(checked).map(c=>c.value).join(',');const url='http://localhost/mvc-lito/public/admin/usuarios/bulk-correo/modal?ids='+ids;window.open(url,'correoMasivo','width=900,height=750,scrollbars=yes');}
function abrirAvisoPassword(){const url='http://localhost/mvc-lito/public/admin/usuarios/cambio-password/modal';window.open(url,'avisoPassword','width=700,height=500,scrollbars=yes');}
</script>
@endsection
@endsection




