@extends('layouts.volt')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Nuevo Tipo de Envío</h3>
        <a href="{{ route('admin.tipos-envio.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Volver</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.tipos-envio.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" required>
                    @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <input type="text" name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" value="{{ old('descripcion') }}">
                    @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Estado</label>
                    <select name="activo" class="form-control">
                        <option value="1" {{ old('activo', true) ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ old('activo') !== null && !old('activo', true) ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Orden</label>
                    <input type="number" name="orden" class="form-control @error('orden') is-invalid @enderror" value="{{ old('orden', 0) }}" min="0">
                    @error('orden')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Guardar</button>
            </form>
        </div>
    </div>
</div>
@endsection