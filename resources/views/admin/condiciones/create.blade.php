@extends('layouts.admin')

@section('title', 'Nueva Condición Comercial')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Nueva Condición Comercial</h3>
        <a href="{{ route('admin.condiciones.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.condiciones.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Título</label>
                    <input name="titulo" class="form-control @error('titulo') is-invalid @enderror"
                           value="{{ old('titulo') }}" required>
                    @error('titulo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Contenido</label>
                    <textarea name="contenido" class="form-control @error('contenido') is-invalid @enderror"
                              rows="5" required>{{ old('contenido') }}</textarea>
                    @error('contenido') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="activo" value="1" class="form-check-input" id="activo" checked>
                    <label class="form-check-label" for="activo">Activo</label>
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Guardar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
