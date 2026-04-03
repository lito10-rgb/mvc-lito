@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <h2 class="mb-4">Mi Perfil</h2>

    @if(session('ok'))
        <div class="alert alert-success">
            {{ session('ok') }}
        </div>
    @endif

    <form method="POST" action="{{ route('perfil.update') }}">
        @csrf

        <!-- ===============================
             DATOS BÁSICOS (TABLA USERS)
             =============================== -->
        <div class="card mb-4">
            <div class="card-header">Datos Básicos</div>
            <div class="card-body row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" value="{{ $user->nombre }}" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Apellidos</label>
                    <input type="text" name="apellidos" value="{{ $user->apellidos }}" class="form-control">
                </div>

            </div>
        </div>

        <!-- ===============================
             PERFIL (TABLA user_profiles)
             =============================== -->
        <div class="card mb-4">
            <div class="card-header">Información de Contacto</div>
            <div class="card-body row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" value="{{ $profile->telefono }}" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" value="{{ $profile->direccion }}" class="form-control">
                </div>

            </div>
        </div>

        <!-- ===============================
             PUNTAJE (TABLA user_scores)
             =============================== -->
        <div class="card mb-4">
            <div class="card-header">Puntaje del Usuario</div>
            <div class="card-body row">

                <div class="col-md-4 mb-3">
                    <label class="form-label">Puntos</label>
                    <input type="number" name="puntos" value="{{ $score->puntos }}" class="form-control">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Nivel</label>
                    <input type="number" name="nivel" value="{{ $score->nivel }}" class="form-control">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Experiencia</label>
                    <input type="number" name="experiencia" value="{{ $score->experiencia }}" class="form-control">
                </div>

            </div>
        </div>

        <!-- BOTÓN GUARDAR -->
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>

    </form>
</div>

@endsection
