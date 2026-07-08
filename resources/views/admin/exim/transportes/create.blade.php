@extends('layouts.admin')

@section('title', 'Nuevo Transporte - EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Nuevo Transporte</h3>
        <a href="{{ route('admin.exim.transportes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.exim.transportes.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre') }}" required placeholder="Ej: Transporte Marítimo SAC">
                        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tipo</label>
                        <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                            <option value="maritimo" {{ old('tipo') === 'maritimo' ? 'selected' : '' }}>Marítimo</option>
                            <option value="aereo" {{ old('tipo') === 'aereo' ? 'selected' : '' }}>Aéreo</option>
                            <option value="terrestre" {{ old('tipo') === 'terrestre' ? 'selected' : '' }}>Terrestre</option>
                            <option value="courier" {{ old('tipo') === 'courier' ? 'selected' : '' }}>Courier</option>
                        </select>
                        @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Moneda</label>
                        <select name="moneda_id" class="form-select @error('moneda_id') is-invalid @enderror">
                            <option value="">Seleccione...</option>
                            @foreach($monedas as $moneda)
                                <option value="{{ $moneda->id }}" {{ old('moneda_id') == $moneda->id ? 'selected' : '' }}>
                                    {{ $moneda->codigo }} - {{ $moneda->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('moneda_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Costo Base</label>
                        <input type="number" step="0.01" name="costo_base" class="form-control @error('costo_base') is-invalid @enderror"
                               value="{{ old('costo_base', 0) }}" min="0" required>
                        @error('costo_base') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror"
                                  rows="2">{{ old('descripcion') }}</textarea>
                        @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Estado</label>
                        <select name="activo" class="form-select @error('activo') is-invalid @enderror">
                            <option value="1" {{ old('activo', '1') == '1' ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ old('activo') === '0' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('activo') <div class="invalid-feedback">{{ $message }}</div> @enderror
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