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

        <!-- Orden por puntuación -->
        <div class="col-md-2">
            <label class="form-label">Ordenar</label>
            <select name="orden" class="form-control">
                <option value="">— Normal —</option>
                <option value="score"
                    {{ request('orden') == 'score' ? 'selected' : '' }}>
                    ⭐ Mejor cliente
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
        <div class="col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('¿Cambiar negocio a los usuarios seleccionados?')">
                ✏️ Cambiar
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
    <td style="background-color: {{ $bgColor }}">{{ $user->email }}</td>
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
    <a href="{{ route('admin.usuarios.edit', $user) }}"
       class="btn btn-sm btn-warning"
       title="Editar">
        ✏️
    </a>

    <form action="{{ route('admin.usuarios.destroy', $user) }}"
          method="POST"
          class="d-inline">
        @csrf
        @method('DELETE')
        <button class="btn btn-sm btn-danger"
                title="Eliminar"
                onclick="return confirm('¿Eliminar usuario?')">
            🗑️
        </button>
    </form>
</td>

</tr>

@endforeach
</tbody>

    </table>

    {{ $users->links() }}
  </div>
</div>

</form>{{-- end bulk-form --}}

@section('scripts')
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

document.querySelector('[name="negocio"]')?.addEventListener('change', function() {
    document.getElementById('custom-negocio-wrap').style.display = this.value === '__custom__' ? 'block' : 'none';
});
</script>
@endsection
@endsection
