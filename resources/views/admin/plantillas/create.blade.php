@extends('layouts.admin')

@section('title', 'Nueva Plantilla de Correo')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Nueva Plantilla de Correo</h3>
        <a href="{{ route('admin.plantillas.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.plantillas.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre') }}" required>
                        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Asunto <span class="text-danger">*</span></label>
                        <input type="text" name="asunto" class="form-control @error('asunto') is-invalid @enderror"
                               value="{{ old('asunto') }}" required>
                        @error('asunto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Contenido <span class="text-danger">*</span></label>
                        <textarea name="contenido" class="form-control @error('contenido') is-invalid @enderror"
                                  rows="10" required>{{ old('contenido') }}</textarea>
                        @error('contenido') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Puedes usar variables: {cliente}, {telefono}, {correo}, {total}, {fecha}, {id}</div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-check">
                            <input type="checkbox" name="activo" class="form-check-input" value="1" id="activo" checked>
                            <label class="form-check-label" for="activo">Activo</label>
                        </div>
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
