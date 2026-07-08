@extends('layouts.admin')

@section('title', 'Editar Pallet - EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Editar Pallet</h3>
        <a href="{{ route('admin.exim.pallets.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.exim.pallets.update', $pallet) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tipo</label>
                        <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                            <option value="estandar" {{ old('tipo', $pallet->tipo) === 'estandar' ? 'selected' : '' }}>Estándar</option>
                            <option value="euro" {{ old('tipo', $pallet->tipo) === 'euro' ? 'selected' : '' }}>Euro</option>
                            <option value="industrial" {{ old('tipo', $pallet->tipo) === 'industrial' ? 'selected' : '' }}>Industrial</option>
                            <option value="personalizado" {{ old('tipo', $pallet->tipo) === 'personalizado' ? 'selected' : '' }}>Personalizado</option>
                        </select>
                        @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Material</label>
                        <select name="material" class="form-select @error('material') is-invalid @enderror" required>
                            <option value="madera" {{ old('material', $pallet->material) === 'madera' ? 'selected' : '' }}>Madera</option>
                            <option value="plastico" {{ old('material', $pallet->material) === 'plastico' ? 'selected' : '' }}>Plástico</option>
                            <option value="metal" {{ old('material', $pallet->material) === 'metal' ? 'selected' : '' }}>Metal</option>
                        </select>
                        @error('material') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Costo Unitario</label>
                        <input type="number" step="0.01" name="costo_unitario" class="form-control @error('costo_unitario') is-invalid @enderror"
                               value="{{ old('costo_unitario', $pallet->costo_unitario) }}" min="0" required>
                        @error('costo_unitario') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Largo (cm)</label>
                        <input type="number" step="0.01" name="largo_cm" class="form-control @error('largo_cm') is-invalid @enderror"
                               value="{{ old('largo_cm', $pallet->largo_cm) }}" required>
                        @error('largo_cm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Ancho (cm)</label>
                        <input type="number" step="0.01" name="ancho_cm" class="form-control @error('ancho_cm') is-invalid @enderror"
                               value="{{ old('ancho_cm', $pallet->ancho_cm) }}" required>
                        @error('ancho_cm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Alto (cm)</label>
                        <input type="number" step="0.01" name="alto_cm" class="form-control @error('alto_cm') is-invalid @enderror"
                               value="{{ old('alto_cm', $pallet->alto_cm) }}" required>
                        @error('alto_cm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Peso (kg)</label>
                        <input type="number" step="0.01" name="peso_kg" class="form-control @error('peso_kg') is-invalid @enderror"
                               value="{{ old('peso_kg', $pallet->peso_kg) }}" required>
                        @error('peso_kg') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Capacidad (kg)</label>
                        <input type="number" step="0.01" name="capacidad_kg" class="form-control @error('capacidad_kg') is-invalid @enderror"
                               value="{{ old('capacidad_kg', $pallet->capacidad_kg) }}" required>
                        @error('capacidad_kg') <div class="invalid-feedback">{{ $message }}</div> @enderror
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