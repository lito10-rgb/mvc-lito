@extends('layouts.volt')

@section('title', $user->exists ? 'Editar Usuario' : 'Crear Usuario')

@section('content')

<form method="POST"
      action="{{ $user->exists
            ? route('admin.usuarios.update', $user)
            : route('admin.usuarios.store') }}"
      enctype="multipart/form-data">

    @csrf
    @if($user->exists)
        @method('PUT')
    @endif

    {{-- ================= DATOS USUARIO ================= --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>Datos del Usuario</strong>
        </div>
        <div class="card-body row">

            <div class="col-md-4">
                <label>Nombre</label>
                <input class="form-control"
                       name="nombre"
                       value="{{ old('nombre', $user->nombre) }}">
            </div>

            <div class="col-md-4">
                <label>Apellidos</label>
                <input class="form-control"
                       name="apellidos"
                       value="{{ old('apellidos', $user->apellidos) }}">
            </div>

            <div class="col-md-4">
                <label>Email</label>
                <input type="email"
                       class="form-control"
                       name="email"
                       value="{{ old('email', $user->email) }}">
            </div>

            <div class="col-md-4 mt-3">
                <label>Password</label>
                <input type="password"
                       class="form-control"
                       name="password">
            </div>

            <div class="col-md-4 mt-3">
                <label>Modo</label>
                <input class="form-control"
                       name="modo"
                       value="{{ old('modo', $user->modo) }}">
            </div>

            <div class="col-md-4 mt-3">
                <label>Foto</label>
                <input type="file"
                       class="form-control"
                       name="foto">
                @if($user->foto)
                    <img src="{{ asset('storage/'.$user->foto) }}"
                         class="img-thumbnail mt-2"
                         width="80">
                @endif
            </div>

        </div>
    </div>

    {{-- ================= PERFIL ================= --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>Perfil</strong>
        </div>
        <div class="card-body row">

            <div class="col-md-4">
                <label>Empresa</label>
                <input class="form-control"
                       name="profile[empresa]"
                       value="{{ old('profile.empresa', $user->profile->empresa ?? '') }}">
            </div>

            <div class="col-md-4">
                <label>Tipo Documento</label>
                <input class="form-control"
                       name="profile[tipo_documento]"
                       value="{{ old('profile.tipo_documento', $user->profile->tipo_documento ?? '') }}">
            </div>

            <div class="col-md-4">
                <label>N° Documento</label>
                <input class="form-control"
                       name="profile[num_documento]"
                       value="{{ old('profile.num_documento', $user->profile->num_documento ?? '') }}">
            </div>

            <div class="col-md-3 mt-3">
                <label>Teléfono</label>
                <input class="form-control"
                       name="profile[telefono]"
                       value="{{ old('profile.telefono', $user->profile->telefono ?? '') }}">
            </div>

            <div class="col-md-3 mt-3">
                <label>Celular</label>
                <input class="form-control"
                       name="profile[celular]"
                       value="{{ old('profile.celular', $user->profile->celular ?? '') }}">
            </div>

            <div class="col-md-3 mt-3">
                <label>WhatsApp</label>
                <input class="form-control"
                       name="profile[whatsapp]"
                       value="{{ old('profile.whatsapp', $user->profile->whatsapp ?? '') }}">
            </div>

            <div class="col-md-3 mt-3">
                <label>Dirección</label>
                <input class="form-control"
                       name="profile[direccion]"
                       value="{{ old('profile.direccion', $user->profile->direccion ?? '') }}">
            </div>

        </div>
    </div>

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
