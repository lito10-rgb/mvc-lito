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
                    <label class="form-label">Oferta (%)</label>
                    <select name="oferta" id="selectOferta" class="form-control">
                        @php $descuentos = [0,5,10,15,20,25,30,50]; @endphp
                        <option value="0">-- Sin descuento --</option>
                        @foreach($descuentos as $desc)
                        <option value="{{ $desc }}" {{ old('oferta', 0) == $desc ? 'selected' : '' }}>
                            {{ $desc }} %
                        </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Se aplica automáticamente a todos los productos de esta categoría.</small>
                </div>

                <div id="bloqueOferta">
                    <div class="mb-3">
                        <label class="form-label">Etiqueta de Oferta</label>
                        <input type="text" name="etiquetaOferta" class="form-control" maxlength="255" placeholder="Ej: Por día de la juventud" value="{{ old('etiquetaOferta') }}">
                        <small class="text-muted">Texto que acompaña al descuento en la tienda (ej: "Por día de la juventud").</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Inicio de Oferta</label>
                            <input type="date" name="fechaInicioOferta" class="form-control" value="{{ old('fechaInicioOferta') }}">
                            <small class="text-muted">Fecha en que la oferta empieza a aplicar. Vacío = inmediata.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fin de Oferta</label>
                            <input type="date" name="finOferta" class="form-control" value="{{ old('finOferta') }}">
                            <small class="text-muted">Déjalo vacío para oferta permanente. Al pasar la fecha, la oferta se desactiva sola.</small>
                        </div>
                    </div>
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sel = document.getElementById('selectOferta');
        const bloque = document.getElementById('bloqueOferta');
        const toggle = () => { bloque.style.display = sel.value > 0 ? 'block' : 'none'; };
        toggle();
        sel.addEventListener('change', toggle);
    });
</script>
@endsection
