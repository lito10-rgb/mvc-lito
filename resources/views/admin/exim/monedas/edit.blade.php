@extends('layouts.admin')

@section('title', 'Editar Moneda - EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Editar Moneda</h3>
        <a href="{{ route('admin.exim.monedas.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.exim.monedas.update', $moneda) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Código</label>
                        <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror"
                               value="{{ old('codigo', $moneda->codigo) }}" maxlength="3" required placeholder="Ej: USD">
                        @error('codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre', $moneda->nombre) }}" required placeholder="Ej: Dólar Americano">
                        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Símbolo</label>
                        <input type="text" name="simbolo" class="form-control @error('simbolo') is-invalid @enderror"
                               value="{{ old('simbolo', $moneda->simbolo) }}" required placeholder="Ej: $">
                        @error('simbolo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tipo de Cambio</label>
                        <input type="number" step="0.0001" name="tipo_cambio" class="form-control @error('tipo_cambio') is-invalid @enderror"
                               value="{{ old('tipo_cambio', $moneda->tipo_cambio) }}" min="0" required>
                        @error('tipo_cambio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-success mt-4">
                    <i class="fas fa-save me-1"></i> Actualizar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection