@extends('layouts.volt')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Nueva Tarifa de Envío</h3>
        <a href="{{ route('admin.tarifas-envio.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Volver</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.tarifas-envio.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Tipo de Envío</label>
                    <select name="tipo_envio_id" class="form-control @error('tipo_envio_id') is-invalid @enderror" required>
                        <option value="">-- Seleccionar --</option>
                        @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id }}" {{ old('tipo_envio_id') == $tipo->id ? 'selected' : '' }}>{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                    @error('tipo_envio_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Categoría (dejar vacío = general)</label>
                    <select name="categoria_id" class="form-control">
                        <option value="">-- General --</option>
                        @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->categoria }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Monto Mínimo (subtotal)</label>
                        <input type="number" step="0.01" name="minimo" class="form-control @error('minimo') is-invalid @enderror" value="{{ old('minimo') }}" min="0">
                        @error('minimo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Vacío = desde 0.</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Monto Máximo (subtotal)</label>
                        <input type="number" step="0.01" name="maximo" class="form-control @error('maximo') is-invalid @enderror" value="{{ old('maximo') }}" min="0">
                        @error('maximo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Vacío = sin tope.</small>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Costo de envío</label>
                    <input type="number" step="0.01" name="costo" class="form-control @error('costo') is-invalid @enderror" value="{{ old('costo') }}" required>
                    @error('costo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Estado</label>
                    <select name="activo" class="form-control">
                        <option value="1" {{ old('activo', true) ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ old('activo') !== null && !old('activo', true) ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Guardar</button>
            </form>
        </div>
    </div>
</div>
@endsection