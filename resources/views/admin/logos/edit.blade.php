@extends('layouts.admin')

@section('title', 'Editar Logo')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Editar Logo</h3>
        <a href="{{ route('admin.logos.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.logos.update', $logo) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre', $logo->nombre) }}" required>
                    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Logo actual</label><br>
                    <img src="{{ asset('storage/' . $logo->ruta) }}" alt="" style="max-height:80px;">
                </div>
                <div class="mb-3">
                    <label class="form-label">Reemplazar logo (opcional)</label>
                    <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror">
                    @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="por_defecto" value="1" class="form-check-input" id="por_defecto"
                           {{ old('por_defecto', $logo->por_defecto) ? 'checked' : '' }}>
                    <label class="form-check-label" for="por_defecto">Logo por defecto</label>
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Actualizar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
