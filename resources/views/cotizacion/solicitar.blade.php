@extends('layouts.app')

@section('title', 'Solicitar Cotización')

@section('content')
<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <h3 class="fw-bold mb-4">Solicitar Cotización</h3>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5>{{ $producto->titulo }}</h5>
            <p class="text-muted">{{ Str::limit($producto->descripcion, 200) }}</p>

            <form method="POST" action="{{ route('cotizacion.store') }}">
                @csrf
                <input type="hidden" name="producto" value="{{ $producto->titulo }}">
                <input type="hidden" name="precio_unitario" value="{{ $producto->precio ?? 0 }}">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="cliente" class="form-control @error('cliente') is-invalid @enderror" value="{{ old('cliente') }}" required>
                        @error('cliente') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Correo</label>
                        <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror" value="{{ old('correo') }}" required>
                        @error('correo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono') }}">
                        @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Cantidad</label>
                        <input type="number" name="cantidad" class="form-control @error('cantidad') is-invalid @enderror" value="{{ old('cantidad', 1) }}" min="1" required>
                        @error('cantidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Detalle / Descripción</label>
                        <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="4" required>{{ old('descripcion', 'Me interesa cotizar este producto.') }}</textarea>
                        @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-success mt-3">Enviar solicitud</button>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary mt-3">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection
