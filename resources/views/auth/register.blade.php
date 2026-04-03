@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>{{ isset($usuario) ? 'Editar Usuario' : 'Registro de Usuario' }}</h2>

    {{-- Mostrar errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ isset($usuario) ? route('register.update', $usuario->id) : url('/register') }}">
        @csrf

        {{-- Nombre --}}
        <div class="mb-3">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" name="nombre" value="{{ old('nombre', $usuario->nombre ?? '') }}" required>
        </div>

        {{-- Correo --}}
        <div class="mb-3">
            <label for="email">Correo</label>
            <input type="email" class="form-control" name="email" value="{{ old('email', $usuario->email ?? '') }}" required>
        </div>

        {{-- Contraseña --}}
        <div class="mb-3">
            <label for="password">Contraseña {{ isset($usuario) ? '(dejar vacío si no desea cambiarla)' : '' }}</label>
            <input type="password" class="form-control" name="password" {{ isset($usuario) ? '' : 'required' }}>
        </div>

        {{-- Confirmar contraseña --}}
        <div class="mb-3">
            <label for="password_confirmation">Confirmar Contraseña</label>
            <input type="password" class="form-control" name="password_confirmation" {{ isset($usuario) ? '' : 'required' }}>
        </div>

        <button type="submit" class="btn btn-success">
            {{ isset($usuario) ? 'Actualizar' : 'Registrarse' }}
        </button>
    </form>
</div>
@endsection
