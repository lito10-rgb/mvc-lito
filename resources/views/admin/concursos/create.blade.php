@extends('layouts.admin')
@section('title', 'Nuevo Concurso')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Nuevo Concurso</h3>
        <a href="{{ route('admin.concursos.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Volver</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.concursos.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre del Concurso <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required placeholder="Ej: Sorteo Navidad 2026">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Fecha del Sorteo <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_sorteo" class="form-control" value="{{ old('fecha_sorteo') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Premio</label>
                        <input type="text" name="premio" class="form-control" value="{{ old('premio') }}" placeholder="Ej: Cafetera premium">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3" placeholder="Detalles del concurso...">{{ old('descripcion') }}</textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-4"><i class="fas fa-save me-1"></i> Crear Concurso</button>
            </form>
        </div>
    </div>
</div>
@endsection
