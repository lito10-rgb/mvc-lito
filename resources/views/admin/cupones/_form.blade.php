@php $cupon = $cupon ?? null; @endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="codigo" class="form-label">Código del cupón *</label>
        <input type="text" name="codigo" id="codigo" class="form-control"
               value="{{ old('codigo', $cupon->codigo ?? '') }}" required maxlength="50"
               placeholder="Ej: VERANO2026" style="text-transform:uppercase;">
        <small class="text-muted">Se guardará en mayúsculas sin espacios</small>
    </div>
    <div class="col-md-3 mb-3">
        <label for="tipo" class="form-label">Tipo *</label>
        <select name="tipo" id="tipo" class="form-select" required>
            <option value="porcentaje" {{ old('tipo', $cupon->tipo ?? '') == 'porcentaje' ? 'selected' : '' }}>% Porcentaje</option>
            <option value="monto_fijo" {{ old('tipo', $cupon->tipo ?? '') == 'monto_fijo' ? 'selected' : '' }}>Monto fijo (S/)</option>
        </select>
    </div>
    <div class="col-md-3 mb-3">
        <label for="valor" class="form-label">Valor del descuento *</label>
        <input type="number" step="0.01" name="valor" id="valor" class="form-control"
               value="{{ old('valor', $cupon->valor ?? '') }}" required min="0.01" placeholder="10">
        <small class="text-muted" id="valor-hint">Ej: 10 = 10% de descuento</small>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-3">
        <label for="min_compra" class="form-label">Compra mínima (S/)</label>
        <input type="number" step="0.01" name="min_compra" id="min_compra" class="form-control"
               value="{{ old('min_compra', $cupon->min_compra ?? 0) }}" min="0" placeholder="0 = sin mínimo">
    </div>
    <div class="col-md-3 mb-3">
        <label for="max_usos" class="form-label">Usos máximos</label>
        <input type="number" name="max_usos" id="max_usos" class="form-control"
               value="{{ old('max_usos', $cupon->max_usos ?? '') }}" min="1" placeholder="Vacío = ilimitado">
    </div>
    <div class="col-md-3 mb-3">
        <label for="negocio_id" class="form-label">Negocio</label>
        <select name="negocio_id" id="negocio_id" class="form-select">
            <option value="">Todos los negocios</option>
            @foreach($negocios as $neg)
                <option value="{{ $neg->id }}" {{ old('negocio_id', $cupon->negocio_id ?? '') == $neg->id ? 'selected' : '' }}>
                    {{ $neg->nombre }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">&nbsp;</label>
        <div class="form-check form-switch">
            <input type="checkbox" name="activo" id="activo" class="form-check-input" value="1"
                   {{ old('activo', $cupon->activo ?? 1) ? 'checked' : '' }}>
            <label class="form-check-label" for="activo">Activo</label>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control"
               value="{{ old('fecha_inicio', $cupon->fecha_inicio ? $cupon->fecha_inicio->format('Y-m-d') : '') }}">
        <small class="text-muted">Vacío = sin restricción de inicio</small>
    </div>
    <div class="col-md-6 mb-3">
        <label for="fecha_fin" class="form-label">Fecha de fin</label>
        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control"
               value="{{ old('fecha_fin', $cupon->fecha_fin ? $cupon->fecha_fin->format('Y-m-d') : '') }}">
        <small class="text-muted">Vacío = sin restricción de fin</small>
    </div>
</div>

@if($cupon)
<div class="row mb-3">
    <div class="col-md-12">
        <div class="alert alert-info mb-0">
            <strong>Usos actuales:</strong> {{ $cupon->usos_actuales }}
            @if($cupon->max_usos)
                / {{ $cupon->max_usos }}
            @endif
            &mdash; Cupón creado: {{ $cupon->created_at->format('d/m/Y H:i') }}
        </div>
    </div>
</div>
@endif

<div class="mt-3">
    <button type="submit" class="btn btn-success">
        <i class="fas fa-save me-1"></i> {{ $cupon ? 'Actualizar' : 'Crear' }} Cupón
    </button>
</div>

@push('scripts')
<script>
document.getElementById('tipo').addEventListener('change', function () {
    var hint = document.getElementById('valor-hint');
    hint.textContent = this.value === 'porcentaje' ? 'Ej: 10 = 10% de descuento' : 'Ej: 5.50 = S/ 5.50 de descuento';
});
</script>
@endpush
