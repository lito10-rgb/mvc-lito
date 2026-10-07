@extends('layouts.volt')

@section('content')
<h1 class="h3">Permisos del Panel</h1>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card mb-4">
    <div class="card-body">
        <p class="mb-1">Los permisos se asignan por <strong>rol</strong> (los usuarios heredan los permisos de sus roles)
            y también se puede otorgar <strong>permisos extra por usuario</strong>.</p>
        <p class="mb-0 text-muted">El rol <span class="badge bg-danger">admin</span> siempre tiene acceso total automáticamente.</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header fw-bold">Permisos por Rol</div>
            <div class="table-responsive">
                <table class="table table-striped table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Rol</th>
                            <th class="text-center">Permisos asignados</th>
                            <th class="text-end">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                        <tr>
                            <td>
                                {{ $role->nombre }}
                                @if($role->esAdmin())
                                    <span class="badge bg-danger ms-1">Acceso total</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($role->esAdmin())
                                    Todos ({{ $modulos->flatten()->count() }})
                                @else
                                    {{ $role->permisos->count() }}
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.permisos.rol.editar', $role) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header fw-bold">Catálogo de Permisos ({{ $modulos->flatten()->count() }})</div>
            <div class="card-body" style="max-height:70vh;overflow-y:auto;">
                @foreach($modulos as $modulo => $permisos)
                <div class="mb-3">
                    <h6 class="text-uppercase text-primary mb-1">{{ $modulo }}</h6>
                    @foreach($permisos as $p)
                    <div class="small text-muted">
                        <code>{{ $p->clave }}</code> — {{ $p->etiqueta }}
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header fw-bold">Permisos extra por Usuario</div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.permisos.usuario.editar', ['user' => 0]) }}" onsubmit="return false;">
            <div class="input-group">
                <input type="text" class="form-control" id="permisos-user-search" placeholder="Nombre o email del usuario..." autocomplete="off">
                <button class="btn btn-primary" type="button" id="permisos-user-go">Buscar / Seleccionar</button>
            </div>
        </form>
        <div id="permisos-user-list" class="mt-3"></div>

        <script>
        const userSearchInput = document.getElementById('permisos-user-search');
        const userList = document.getElementById('permisos-user-list');

        let timer = null;
        userSearchInput.addEventListener('input', function () {
            clearTimeout(timer);
            const q = this.value.trim();
            if (q.length < 2) { userList.innerHTML = ''; return; }
            timer = setTimeout(() => buscarUsuarios(q), 350);
        });

        function buscarUsuarios(q) {
            fetch("{{ url('/admin/permisos/buscar-usuarios') }}?q=" + encodeURIComponent(q))
                .then(r => r.json())
                .then(data => {
                    if (!data.length) { userList.innerHTML = '<div class="text-muted">Sin resultados.</div>'; return; }
                    userList.innerHTML = data.map(u =>
                        `<a href="/admin/permisos/usuario/${u.id}/editar" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>${u.nombre} <small class="text-muted">${u.email || ''}</small></span>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </a>`
                    ).join('');
                });
        }

        document.getElementById('permisos-user-go').addEventListener('click', function () {
            const q = userSearchInput.value.trim();
            if (q.length >= 2) buscarUsuarios(q);
        });
        </script>
    </div>
</div>
@endsection