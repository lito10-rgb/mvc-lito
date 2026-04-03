@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Registro de Usuario</h2>
    <form method="POST" action="{{ url('/register') }}">
        @csrf

        <div class="mb-3">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" name="nombre" value="{{ old('nombre') }}" required>
        </div>

        <div class="mb-3">
            <label for="email">Correo</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label for="password">Contraseña</label>
            <input type="password" class="form-control" name="password" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation">Confirmar Contraseña</label>
            <input type="password" class="form-control" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-success">Registrarse</button>
    </form>
</div>
@endsection
