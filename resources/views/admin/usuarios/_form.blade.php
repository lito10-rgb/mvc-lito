@extends('layouts.volt')

@section('title', $user->exists ? 'Editar Usuario' : 'Crear Usuario')

@section('content')
@push('scripts')
    @vite('resources/js/perfil.js')
@endpush

<form method="POST"
      action="{{ $user->exists
            ? route('admin.usuarios.update', $user)
            : route('admin.usuarios.store') }}"
      enctype="multipart/form-data">

    @csrf
    @if($user->exists)
        @method('PUT')
    @endif
{{-- ===================== DATOS USUARIO ===================== --}}
<div class="card mb-3">
    <div class="card-header"><strong>Datos del Usuario</strong></div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <label>Nombre</label>
                <input name="nombre" class="form-control"
                       value="{{ old('nombre', $user->nombre) }}">
            </div>

            <div class="col-md-4">
                <label>Apellidos</label>
                <input name="apellidos" class="form-control"
                       value="{{ old('apellidos', $user->apellidos) }}">
            </div>

            <div class="col-md-4">
                <label>Email</label>
                <input name="email" class="form-control"
                       value="{{ old('email', $user->email) }}" readonly>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-4">
                <label>Password (opcional)</label>
                <input name="password" type="password" class="form-control">
            </div>

            <div class="col-md-4">
                <label>Modo</label>
                <input name="modo" class="form-control"
                       value="{{ old('modo', $user->modo) }}">
            </div>

            <div class="col-md-4">
                <label>Foto</label>
                <input type="file" name="foto" class="form-control">
            </div>
        </div>
    </div>
</div>

{{-- ===================== PERFIL ===================== --}}
<div class="card mb-3">
    <div class="card-header"><strong>Perfil</strong></div>
    <div class="card-body">

        {{-- Empresa / Cargo --}}
        <div class="row">
            <div class="col-md-4">
                <label>Empresa</label>
                <input name="empresa" class="form-control"
                       value="{{ old('empresa', $user->profile->empresa ?? '') }}">
            </div>

            <div class="col-md-4">
                <label>Cargo</label>
                <input name="cargo" class="form-control"
                       value="{{ old('cargo', $user->profile->cargo ?? '') }}">
            </div>

            <div class="col-md-4">
                <label>Representante Legal</label>
                <input name="representantelegal" class="form-control"
                       value="{{ old('representantelegal', $user->profile->representantelegal ?? '') }}">
            </div>
        </div>

        {{-- Documento --}}
        <div class="row mt-2">
            <div class="col-md-4">
                <label>Tipo Documento</label>
                <select name="tipo_documento" class="form-control">
                    <option value="">-- seleccionar --</option>
                    @foreach(['DNI','RUC','PAS'] as $doc)
                        <option value="{{ $doc }}"
                            @selected(($user->profile->tipo_documento ?? '')==$doc)>
                            {{ $doc }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label>N° Documento</label>
                <input name="num_documento" class="form-control"
                       value="{{ old('num_documento', $user->profile->num_documento ?? '') }}">
            </div>

            <div class="col-md-4">
                <label>Fecha Nacimiento</label>
                <input type="date" name="fechanacimiento" class="form-control"
                       value="{{ old('fechanacimiento', $user->profile->fechanacimiento ?? '') }}">
            </div>
        </div>

        {{-- Contactos --}}
        <div class="row mt-2">
            <div class="col-md-3"><label>Teléfono</label>
                <input name="telefono" class="form-control" value="{{ $user->profile->telefono ?? '' }}">
            </div>
            <div class="col-md-3"><label>Celular</label>
                <input name="celular" class="form-control" value="{{ $user->profile->celular ?? '' }}">
            </div>

            <div class="col-md-3"><label>WhatsApp</label>
                <input name="whatsapp" class="form-control" value="{{ $user->profile->whatsapp ?? '' }}">
            </div>
            <div class="col-md-3"><label>Skype</label>
                <input name="skype" class="form-control" value="{{ $user->profile->skype ?? '' }}">
            </div>
        </div>
        <div class="row mt-2">
    <div class="col-md-3">
        <label>Celular 2</label>
        <input name="celular2" class="form-control"
               value="{{ old('celular2', $user->profile->celular2 ?? '') }}">
    </div>

    <div class="col-md-3">
        <label>Celular 3</label>
        <input name="celular3" class="form-control"
               value="{{ old('celular3', $user->profile->celular3 ?? '') }}">
    </div>

    <div class="col-md-3">
        <label>Celular 4</label>
        <input name="celular4" class="form-control"
               value="{{ old('celular4', $user->profile->celular4 ?? '') }}">
    </div>
</div>
<div class="row mt-2">
    <div class="col-md-4">
        <label>Email 2</label>
        <input name="email2" class="form-control"
               value="{{ old('email2', $user->profile->email2 ?? '') }}">
    </div>

    <div class="col-md-4">
        <label>Email 3</label>
        <input name="email3" class="form-control"
               value="{{ old('email3', $user->profile->email3 ?? '') }}">
    </div>

    <div class="col-md-4">
        <label>Email 4</label>
        <input name="email4" class="form-control"
               value="{{ old('email4', $user->profile->email4 ?? '') }}">
    </div>
</div>

        {{-- Ubicación (DINÁMICO) --}}
       <!--  <div class="row mt-3">
            <div class="col-md-3">
                <label>País</label>
                <select id="pais" name="pais" class="form-control"
                        data-selected="{{ $user->profile->pais ?? '' }}"></select>
            </div>
            <div class="col-md-3">
                <label>Estado</label>
                <select id="estado" name="estado" class="form-control"
                        data-selected="{{ $user->profile->estado ?? '' }}"></select>
            </div>
            <div class="col-md-3">
                <label>Provincia</label>
                <select id="provincia" name="provincia" class="form-control"
                        data-selected="{{ $user->profile->provincia ?? '' }}"></select>
            </div>
            <div class="col-md-3">
                <label>Distrito</label>
                <select id="distrito" name="distrito" class="form-control"
                        data-selected="{{ $user->profile->distrito ?? '' }}"></select>
            </div>
        </div> -->
        <div class="row">
        <div class="col-md-3">
            <label>País</label>
            <select id="pais" name="pais" class="form-control">
                <option value="">Seleccione país</option>
                @foreach($paises as $pais)
                    <option value="{{ $pais->id }}"
                        {{ old('pais', $user->profile->pais ?? '') == $pais->id ? 'selected' : '' }}>
                        {{ $pais->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

    <div class="col-md-3">
        <label>Estado</label>
          <select id="estado" name="estado" class="form-control">
        <option value="">Seleccione estado</option>
        @foreach ($estados as $estado)
            <option value="{{ $estado->id }}"
                {{ old('estado', $user->profile->estado ?? '') == $estado->id ? 'selected' : '' }}>
                {{ $estado->nombre }}
            </option>
        @endforeach
    </select>

    </div>

    <div class="col-md-3">
        <label>Provincia</label>
       <select id="provincia" name="provincia" class="form-control">
    <option value="">Seleccione provincia</option>

    @foreach ($provincias as $provincia)
        <option value="{{ $provincia->id }}"
            {{ old('provincia', $user->profile->provincia ?? '') == $provincia->id ? 'selected' : '' }}>
            {{ $provincia->nombre }}
        </option>
    @endforeach
</select>

    </div>

    <div class="col-md-3">
        <label>Distrito</label>
     <select id="distrito" name="distrito" class="form-control">
    <option value="">Seleccione distrito</option>

    @foreach ($distritos as $distrito)
        <option value="{{ $distrito->id }}"
            {{ old('distrito', $user->profile->distrito ?? '') == $distrito->id ? 'selected' : '' }}>
            {{ $distrito->nombre }}
        </option>
    @endforeach
</select>

</div>

        <div class="row mt-2">
            <div class="col-md-8">
                <label>Dirección</label>
                <input name="direccion" class="form-control"
                       value="{{ $user->profile->direccion ?? '' }}">
            </div>
            <div class="col-md-4">
                <label>Código Postal</label>
                <input name="codigopostal" class="form-control"
                       value="{{ $user->profile->codigopostal ?? '' }}">
            </div>
        </div>

        {{-- Web / Redes --}}
        <div class="row mt-3">
            <div class="col-md-3"><label>Web</label>
                <input name="web" class="form-control" value="{{ $user->profile->web ?? '' }}">
            </div>
            <div class="col-md-3"><label>Web2</label>
                <input name="web2" class="form-control" value="{{ $user->profile->web2 ?? '' }}">
            </div>
            <div class="col-md-3"><label>Facebook</label>
                <input name="facebook" class="form-control" value="{{ $user->profile->facebook ?? '' }}">
            </div>
            <div class="col-md-3"><label>Instagram</label>
                <input name="instagram" class="form-control" value="{{ $user->profile->instagram ?? '' }}">
            </div>
        </div>

        {{-- Detalle --}}
        <div class="mt-3">
            <label>Detalle</label>
            <textarea name="detalle" class="form-control" rows="3">{{ $user->profile->detalle ?? '' }}</textarea>
        </div>
    </div>
</div>

<!-- {{-- ===================== PUNTAJES ===================== --}}
<div class="card mb-3">
    <div class="card-header"><strong>Puntajes</strong></div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <label>Puntuación</label>
                <input name="puntuacion" class="form-control"
                       value="{{ $user->scores->puntuacion ?? 0 }}">
            </div>
            <div class="col-md-3">
                <label>P. Usuario</label>
                <input name="p_usuario" class="form-control"
                       value="{{ $user->scores->p_usuario ?? 0 }}">
            </div>
            <div class="col-md-3">
                <label>P. Precio</label>
                <input name="p_precio" class="form-control"
                       value="{{ $user->scores->p_precio ?? 0 }}">
            </div>
            <div class="col-md-3">
                <label>Condición</label>
                <input name="condicion" class="form-control"
                       value="{{ $user->scores->condicion ?? 1 }}">
            </div>
        </div>
    </div>
</div>
 -->

    {{-- ================= PUNTAJES ================= --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>Puntajes</strong>
        </div>
        <div class="card-body row">

            <div class="col-md-3">
                <label>Puntuación</label>
                <input class="form-control"
                       name="scores[puntuacion]"
                       value="{{ old('scores.puntuacion', $user->scores->puntuacion ?? 0) }}">
            </div>

            <div class="col-md-3">
                <label>P. Usuario</label>
                <input class="form-control"
                       name="scores[puntuacion_usuario]"
                       value="{{ old('scores.puntuacion_usuario', $user->scores->puntuacion_usuario ?? 0) }}">
            </div>

            <div class="col-md-3">
                <label>P. Precio</label>
                <input class="form-control"
                       name="scores[puntuacion_precio]"
                       value="{{ old('scores.puntuacion_precio', $user->scores->puntuacion_precio ?? 0) }}">
            </div>

            <div class="col-md-3">
                <label>Condición</label>
                <input class="form-control"
                       name="scores[condicion]"
                       value="{{ old('scores.condicion', $user->scores->condicion ?? '') }}">
            </div>

        </div>
    </div>
    <div class="form-group mt-3">
    <label><strong>Roles</strong></label>

    <div class="row">
        @foreach($roles as $role)
            <div class="col-md-4">
                <label class="d-block">
                    <input type="checkbox"
                           name="roles[]"
                           value="{{ $role->id }}"
                           {{ $user->roles->contains($role->id) ? 'checked' : '' }}>
                    {{ ucfirst($role->nombre) }}
                </label>
            </div>
        @endforeach
    </div>
</div>

    <div class="form-group mt-3">
    <label><strong>Rubros</strong></label>

    <div class="row">
        @foreach($rubros as $rubro)
            <div class="col-md-4">
                <label>
                    <input type="checkbox"
                           name="rubros[]"
                           value="{{ $rubro->id }}"
                           {{ $user->rubros->contains($rubro->id) ? 'checked' : '' }}>
                    {{ $rubro->nombre }}
                </label>
            </div>
        @endforeach
    </div>
</div>

    {{-- ================= BOTONES ================= --}}
    <div class="mb-4">
        <button class="btn btn-success">
            {{ $user->exists ? 'Actualizar Usuario' : 'Crear Usuario' }}
        </button>

        <a href="{{ route('admin.usuarios.index') }}"
           class="btn btn-secondary ms-2">
            Volver
        </a>
    </div>

</form>

@endsection
