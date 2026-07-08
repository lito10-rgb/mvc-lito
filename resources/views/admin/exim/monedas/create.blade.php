@extends('layouts.admin')

@section('title', 'Nueva Moneda - EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Nueva Moneda</h3>
        <a href="{{ route('admin.exim.monedas.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.exim.monedas.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Código</label>
                        <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror"
                               value="{{ old('codigo') }}" maxlength="3" required placeholder="Ej: USD">
                        @error('codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre') }}" required placeholder="Ej: Dólar Americano">
                        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Símbolo</label>
                        <input type="text" name="simbolo" class="form-control @error('simbolo') is-invalid @enderror"
                               value="{{ old('simbolo') }}" required placeholder="Ej: $">
                        @error('simbolo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tipo de Cambio</label>
                        <input type="number" step="0.0001" name="tipo_cambio" class="form-control @error('tipo_cambio') is-invalid @enderror"
                               value="{{ old('tipo_cambio', 1) }}" min="0" required>
                        @error('tipo_cambio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-success mt-4">
                    <i class="fas fa-save me-1"></i> Guardar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection