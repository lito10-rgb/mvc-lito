@extends('layouts.admin')

@section('title', 'Editar Documento - EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Editar Documento</h3>
        <a href="{{ route('admin.exim.documentos.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.exim.documentos.update', $documento) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Cotización</label>
                        <select name="cotizacion_id" class="form-select @error('cotizacion_id') is-invalid @enderror" required>
                            <option value="">Seleccione...</option>
                            @foreach($cotizaciones as $cotizacion)
                                <option value="{{ $cotizacion->id }}" {{ old('cotizacion_id', $documento->cotizacion_id) == $cotizacion->id ? 'selected' : '' }}>
                                    #{{ $cotizacion->id }} - {{ $cotizacion->cliente->empresa ?? '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('cotizacion_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tipo</label>
                        <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                            <option value="factura" {{ old('tipo', $documento->tipo) === 'factura' ? 'selected' : '' }}>Factura</option>
                            <option value="guia" {{ old('tipo', $documento->tipo) === 'guia' ? 'selected' : '' }}>Guía</option>
                            <option value="certificado" {{ old('tipo', $documento->tipo) === 'certificado' ? 'selected' : '' }}>Certificado</option>
                            <option value="conocimiento_embarque" {{ old('tipo', $documento->tipo) === 'conocimiento_embarque' ? 'selected' : '' }}>Conocimiento de Embarque</option>
                            <option value="packing_list" {{ old('tipo', $documento->tipo) === 'packing_list' ? 'selected' : '' }}>Packing List</option>
                            <option value="seguro" {{ old('tipo', $documento->tipo) === 'seguro' ? 'selected' : '' }}>Seguro</option>
                            <option value="otro" {{ old('tipo', $documento->tipo) === 'otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">N° Documento</label>
                        <input type="text" name="numero_documento" class="form-control @error('numero_documento') is-invalid @enderror"
                               value="{{ old('numero_documento', $documento->numero_documento) }}" required placeholder="Ej: FAC-001-2025">
                        @error('numero_documento') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
