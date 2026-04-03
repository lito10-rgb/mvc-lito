@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center text-warning mb-4">Contáctanos</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('contacto.enviar') }}" method="POST" class="mx-auto" style="max-width: 600px;">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" class="form-control" required>
            @error('nombre') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" required>
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="mensaje" class="form-label">Mensaje</label>
            <textarea id="mensaje" name="mensaje" rows="5" class="form-control" required>{{ old('mensaje') }}</textarea>
            @error('mensaje') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-warning px-5">Enviar</button>
        </div>
    </form>
</div>
@endsection
