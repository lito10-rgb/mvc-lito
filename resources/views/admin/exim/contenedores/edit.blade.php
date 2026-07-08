@extends('layouts.admin')

@section('title', 'Editar Contenedor - EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Editar Contenedor</h3>
        <a href="{{ route('admin.exim.contenedores.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.exim.contenedores.update', $contenedor) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tipo</label>
                        <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                            <option value="20ft" {{ old('tipo', $contenedor->tipo) === '20ft' ? 'selected' : '' }}>20ft</option>
                            <option value="40ft" {{ old('tipo', $contenedor->tipo) === '40ft' ? 'selected' : '' }}>40ft</option>
                            <option value="40hq" {{ old('tipo', $contenedor->tipo) === '40hq' ? 'selected' : '' }}>40hq</option>
                        </select>
                        @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Largo (cm)</label>
                        <input type="number" step="0.01" name="largo_cm" class="form-control @error('largo_cm') is-invalid @enderror"
                               value="{{ old('largo_cm', $contenedor->largo_cm) }}" required>
                        @error('largo_cm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Ancho (cm)</label>
                        <input type="number" step="0.01" name="ancho_cm" class="form-control @error('ancho_cm') is-invalid @enderror"
                               value="{{ old('ancho_cm', $contenedor->ancho_cm) }}" required>
                        @error('ancho_cm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Alto (cm)</label>
                        <input type="number" step="0.01" name="alto_cm" class="form-control @error('alto_cm') is-invalid @enderror"
                               value="{{ old('alto_cm', $contenedor->alto_cm) }}" required>
                        @error('alto_cm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Capacidad Máx. (kg)</label>
                        <input type="number" step="0.01" name="capacidad_max_kg" class="form-control @error('capacidad_max_kg') is-invalid @enderror"
                               value="{{ old('capacidad_max_kg', $contenedor->capacidad_max_kg) }}" required>
                        @error('capacidad_max_kg') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Pallets Máx.</label>
                        <input type="number" name="pallets_max" class="form-control @error('pallets_max') is-invalid @enderror"
                               value="{{ old('pallets_max', $contenedor->pallets_max) }}" required>
                        @error('pallets_max') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Sacos Máx.</label>
                        <input type="number" name="sacos_max" class="form-control @error('sacos_max') is-invalid @enderror"
                               value="{{ old('sacos_max', $contenedor->sacos_max) }}" required>
                        @error('sacos_max') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Flete Marítimo</label>
                        <input type="number" step="0.01" name="flete_maritimo" class="form-control @error('flete_maritimo') is-invalid @enderror"
                               value="{{ old('flete_maritimo', $contenedor->flete_maritimo) }}" min="0" required>
                        @error('flete_maritimo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Seguro</label>
                        <input type="number" step="0.01" name="seguro" class="form-control @error('seguro') is-invalid @enderror"
                               value="{{ old('seguro', $contenedor->seguro) }}" min="0" required>
                        @error('seguro') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Gastos Portuarios</label>
                        <input type="number" step="0.01" name="gastos_portuarios" class="form-control @error('gastos_portuarios') is-invalid @enderror"
                               value="{{ old('gastos_portuarios', $contenedor->gastos_portuarios) }}" min="0" required>
                        @error('gastos_portuarios') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Documentación</label>
                        <input type="number" step="0.01" name="documentacion" class="form-control @error('documentacion') is-invalid @enderror"
                               value="{{ old('documentacion', $contenedor->documentacion) }}" min="0" required>
                        @error('documentacion') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
