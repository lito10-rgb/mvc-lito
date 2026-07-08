@extends('layouts.admin')

@section('title', 'Editar Gasto Logístico - EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Editar Gasto Logístico</h3>
        <a href="{{ route('admin.exim.gastos-logisticos.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.exim.gastos-logisticos.update', $gastoLogistico) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre', $gastoLogistico->nombre) }}" required placeholder="Ej: Almacenaje">
                        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Costo</label>
                        <input type="number" step="0.01" name="costo" class="form-control @error('costo') is-invalid @enderror"
                               value="{{ old('costo', $gastoLogistico->costo) }}" min="0" required>
                        @error('costo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Moneda</label>
                        <select name="moneda_id" class="form-select @error('moneda_id') is-invalid @enderror">
                            <option value="">Seleccione...</option>
                            @foreach($monedas as $moneda)
                                <option value="{{ $moneda->id }}" {{ old('moneda_id', $gastoLogistico->moneda_id) == $moneda->id ? 'selected' : '' }}>
                                    {{ $moneda->codigo }} - {{ $moneda->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('moneda_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror"
                                  rows="2">{{ old('descripcion', $gastoLogistico->descripcion) }}</textarea>
                        @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="activo" class="form-check-input" value="1" id="activo" {{ old('activo', $gastoLogistico->activo ? '1' : '0') ? 'checked' : '' }}>
                            <label class="form-check-label" for="activo">Activo</label>
                        </div>
                        @error('activo') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
