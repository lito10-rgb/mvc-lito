@extends('layouts.admin')

@section('title', 'Nuevo Producto - EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Nuevo Producto</h3>
        <a href="{{ route('admin.exim.productos.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.exim.productos.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tipo</label>
                        <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                            <option value="cafe_verde" {{ old('tipo') === 'cafe_verde' ? 'selected' : '' }}>Café Verde</option>
                            <option value="cafe_tostado" {{ old('tipo') === 'cafe_tostado' ? 'selected' : '' }}>Café Tostado</option>
                            <option value="cafe_molido" {{ old('tipo') === 'cafe_molido' ? 'selected' : '' }}>Café Molido</option>
                            <option value="maquinaria" {{ old('tipo') === 'maquinaria' ? 'selected' : '' }}>Maquinaria</option>
                            <option value="equipo_industrial" {{ old('tipo') === 'equipo_industrial' ? 'selected' : '' }}>Equipo Industrial</option>
                            <option value="repuesto" {{ old('tipo') === 'repuesto' ? 'selected' : '' }}>Repuesto</option>
                            <option value="accesorio" {{ old('tipo') === 'accesorio' ? 'selected' : '' }}>Accesorio</option>
                        </select>
                        @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Producto Relacionado</label>
                        <select name="producto_id" class="form-select @error('producto_id') is-invalid @enderror">
                            <option value="">Ninguno</option>
                            @foreach($productosLocales as $prod)
                                <option value="{{ $prod->id }}" {{ old('producto_id') == $prod->id ? 'selected' : '' }}>
                                    {{ $prod->titulo }}
                                </option>
                            @endforeach
                        </select>
                        @error('producto_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre') }}" required>
                        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Precio Base</label>
                        <input type="number" step="0.01" name="precio_base" class="form-control @error('precio_base') is-invalid @enderror"
                               value="{{ old('precio_base', 0) }}" min="0" required>
                        @error('precio_base') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Moneda</label>
                        <select name="moneda_id" class="form-select @error('moneda_id') is-invalid @enderror" required>
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
                        <div class="form-check mt-4">
                            <input type="checkbox" name="estado" class="form-check-input" value="1" id="estado" {{ old('estado', '1') ? 'checked' : '' }}>
                            <label class="form-check-label" for="estado">Activo</label>
                        </div>
                        @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
