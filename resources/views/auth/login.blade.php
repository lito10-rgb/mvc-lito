@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Iniciar Sesión</h2>
    <form method="POST" action="{{ url('/login') }}">
        @csrf

        <div class="mb-3">
            <label for="email">Correo</label>
            <input type="email" class="form-control" name="email" required>
        </div>

        <div class="mb-3">
            <label for="password">Contraseña</label>
            <input type="password" class="form-control" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary cart-actions">Ingresar</button>
    </form>
</div>
@endsection
