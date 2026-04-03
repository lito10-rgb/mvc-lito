@extends('layouts.volt')

@section('content')
<form method="POST"
      action="{{ $user->exists
        ? route('admin.usuarios.update', $user)
        : route('admin.usuarios.store') }}">

@csrf
@if($user->exists) @method('PUT') @endif

<div class="card mb-3">
  <div class="card-header">Datos de acceso</div>
  <div class="card-body row">
    <div class="col-md-6">
      <label>Nombre</label>
      <input class="form-control" name="name"
             value="{{ old('name', $user->name) }}">
    </div>

    <div class="col-md-6">
      <label>Email</label>
      <input class="form-control" name="email"
             value="{{ old('email', $user->email) }}">
    </div>

    <div class="col-md-6 mt-2">
      <label>Contraseña</label>
      <input type="password" class="form-control" name="password">
    </div>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">Perfil</div>
  <div class="card-body row">
    <div class="col-md-4">
      <label>DNI</label>
      <input class="form-control" name="dni"
             value="{{ old('dni', $user->profile->dni ?? '') }}">
    </div>

    <div class="col-md-4">
      <label>Teléfono</label>
      <input class="form-control" name="telefono"
             value="{{ old('telefono', $user->profile->telefono ?? '') }}">
    </div>

    <div class="col-md-4">
      <label>Dirección</label>
      <input class="form-control" name="direccion"
             value="{{ old('direccion', $user->profile->direccion ?? '') }}">
    </div>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">Score</div>
  <div class="card-body row">
    <div class="col-md-6">
      <label>Puntos</label>
      <input class="form-control" name="score"
             value="{{ old('score', $user->score->score ?? 0) }}">
    </div>

    <div class="col-md-6">
      <label>Nivel</label>
      <select name="nivel" class="form-select">
        @foreach(['bronce','plata','oro'] as $nivel)
          <option value="{{ $nivel }}"
            @selected(($user->score->nivel ?? 'bronce') == $nivel)>
            {{ ucfirst($nivel) }}
          </option>
        @endforeach
      </select>
    </div>
  </div>
</div>

<button class="btn btn-success">
  Guardar Usuario
</button>

<a href="{{ route('admin.usuarios.index') }}"
   class="btn btn-secondary ms-2">
  Volver
</a>

</form>
@endsection
