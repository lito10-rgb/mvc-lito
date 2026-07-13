@extends('layouts.volt')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Nueva Categoría</h3>
        <a href="{{ route('admin.categorias.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.categorias.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nombre de la categoría</label>
                    <input type="text" name="categoria" class="form-control @error('categoria') is-invalid @enderror"
                           value="{{ old('categoria') }}" required>
                    @error('categoria')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Ruta (slug)</label>
                    <input type="text" name="ruta" class="form-control @error('ruta') is-invalid @enderror"
                           value="{{ old('ruta') }}" placeholder="Dejar vacío para generar automáticamente">
                    @error('ruta')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-control">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Negocios (donde se publica)</label>
                    <div class="row">
                        @foreach($negocios as $neg)
                        <div class="col-md-4">
                            <div class="form-check">
                                <input type="checkbox" name="negocios[]" value="{{ $neg->id }}" class="form-check-input"
                                    id="cat_neg_{{ $neg->id }}"
                                    {{ in_array($neg->id, old('negocios', $categoriaNegocioIds ?? [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="cat_neg_{{ $neg->id }}">{{ $neg->nombre }}</label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Guardar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
