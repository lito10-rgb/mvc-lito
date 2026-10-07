@extends('layouts.volt')

@section('content')
<h1 class="h3">Permisos del rol: {{ $role->nombre }}</h1>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($role->esAdmin())
<div class="alert alert-danger">
    El rol <strong>admin</strong> siempre tiene <strong>todos</strong> los permisos. No es necesario asignarlos.
</div>
@else
<form method="POST" action="{{ route('admin.permisos.rol.guardar', $role) }}">
    @csrf
    @method('PUT')

    <div class="row g-3">
        @foreach($modulos as $modulo => $permisosModulo)
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>{{ ucfirst($modulo) }}</strong>
                    <div class="form-check form-switch">
                        <input class="form-check-input marcar-modulo" type="checkbox" data-modulo="{{ $modulo }}" id="switch-{{ $modulo }}">
                        <label class="form-check-label small" for="switch-{{ $modulo }}">Marcar todo</label>
                    </div>
                </div>
                <div class="card-body py-2">
                    @foreach($permisosModulo as $p)
                    <div class="form-check">
                        <input class="form-check-input permiso-modo" type="checkbox"
                               name="permisos[]" value="{{ $p->clave }}"
                               id="permiso-{{ $p->id }}"
                               @if(in_array($p->clave, $seleccionados)) checked @endif
                               data-modulo="{{ $modulo }}">
                        <label class="form-check-label" for="permiso-{{ $p->id }}">
                            {{ $p->etiqueta }}
                            <code class="small text-muted">{{ $p->clave }}</code>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Guardar permisos</button>
        <a href="{{ route('admin.permisos.index') }}" class="btn btn-secondary">Volver</a>
    </div>
</form>

<script>
document.querySelectorAll('.marcar-modulo').forEach(sw => {
    sw.addEventListener('change', function () {
        const modulo = this.dataset.modulo;
        document.querySelectorAll('.permiso-modo[data-modulo="' + modulo + '"]')
            .forEach(cb => cb.checked = this.checked);
    });
});
</script>
@endif
@endsection