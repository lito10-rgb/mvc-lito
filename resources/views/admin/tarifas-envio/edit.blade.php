@extends('layouts.volt')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Editar Tarifa</h3>
        <a href="{{ route('admin.tarifas-envio.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Volver</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.tarifas-envio.update', $tarifaEnvio) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Tipo de Envío</label>
                    <select name="tipo_envio_id" class="form-control @error('tipo_envio_id') is-invalid @enderror" required>
                        @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id }}" {{ old('tipo_envio_id', $tarifaEnvio->tipo_envio_id) == $tipo->id ? 'selected' : '' }}>{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                    @error('tipo_envio_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Categoría (dejar vacío = general)</label>
                    <select name="categoria_id" id="cat-select" class="form-control">
                        <option value="">-- General --</option>
                        @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ old('categoria_id', $tarifaEnvio->categoria_id) == $cat->id ? 'selected' : '' }}>{{ $cat->categoria }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Subcategoría (opcional, más específico)</label>
                    <select name="subcategoria_id" id="subcat-select" class="form-control">
                        <option value="">-- Todas las subcategorías --</option>
                        @foreach($categorias as $cat)
                            @foreach($cat->subcategorias as $sub)
                            <option value="{{ $sub->id }}" data-cat="{{ $cat->id }}" {{ old('subcategoria_id', $tarifaEnvio->subcategoria_id) == $sub->id ? 'selected' : '' }}>{{ $cat->categoria }} → {{ $sub->subcategoria }}</option>
                            @endforeach
                        @endforeach
                    </select>
                    <small class="text-muted">Si seleccionas subcategoría, la tarifa aplica solo a productos de esa subcategoría.</small>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Monto Mínimo (subtotal)</label>
                        <input type="number" step="0.01" name="minimo" class="form-control @error('minimo') is-invalid @enderror" value="{{ old('minimo', $tarifaEnvio->minimo) }}" min="0">
                        @error('minimo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Monto Máximo (subtotal)</label>
                        <input type="number" step="0.01" name="maximo" class="form-control @error('maximo') is-invalid @enderror" value="{{ old('maximo', $tarifaEnvio->maximo) }}" min="0">
                        @error('maximo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Costo de envío</label>
                        <input type="number" step="0.01" name="costo" id="input-costo" class="form-control @error('costo') is-invalid @enderror" value="{{ old('costo', $tarifaEnvio->costo) }}" {{ old('gratis', $tarifaEnvio->gratis) ? 'disabled' : '' }}>
                        @error('costo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Envío gratuito</label>
                        <div class="form-check form-switch mt-2">
                            <input type="checkbox" name="gratis" value="1" id="check-gratis" class="form-check-input" {{ old('gratis', $tarifaEnvio->gratis) ? 'checked' : '' }}>
                            <label class="form-check-label" for="check-gratis">Marcar si el envío es gratis</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Estado</label>
                        <select name="activo" class="form-control">
                            <option value="1" {{ old('activo', $tarifaEnvio->activo) ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ old('activo') !== null && !old('activo', $tarifaEnvio->activo) ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Guardar</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const check = document.getElementById('check-gratis');
    const input = document.getElementById('input-costo');
    check.addEventListener('change', function () {
        input.disabled = this.checked;
        if (this.checked) input.value = '';
    });
});
</script>
@endsection