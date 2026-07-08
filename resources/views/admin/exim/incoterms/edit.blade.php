@extends('layouts.admin')

@section('title', 'Editar Incoterm - EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Editar Incoterm</h3>
        <a href="{{ route('admin.exim.incoterms.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.exim.incoterms.update', $incoterm) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Código</label>
                        <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror"
                               value="{{ old('codigo', $incoterm->codigo) }}" maxlength="3" required placeholder="Ej: FOB">
                        @error('codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre', $incoterm->nombre) }}" required placeholder="Ej: Free On Board">
                        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror"
                                  rows="2">{{ old('descripcion', $incoterm->descripcion) }}</textarea>
                        @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Cobertura</label>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="incluye_transporte_interno" value="1"
                                           {{ old('incluye_transporte_interno', $incoterm->incluye_transporte_interno) ? 'checked' : '' }}>
                                    <label class="form-check-label">Transporte Interno</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="incluye_flete_maritimo" value="1"
                                           {{ old('incluye_flete_maritimo', $incoterm->incluye_flete_maritimo) ? 'checked' : '' }}>
                                    <label class="form-check-label">Flete Marítimo</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="incluye_seguro" value="1"
                                           {{ old('incluye_seguro', $incoterm->incluye_seguro) ? 'checked' : '' }}>
                                    <label class="form-check-label">Seguro</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="incluye_aduanas_origen" value="1"
                                           {{ old('incluye_aduanas_origen', $incoterm->incluye_aduanas_origen) ? 'checked' : '' }}>
                                    <label class="form-check-label">Aduanas Origen</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="incluye_aduanas_destino" value="1"
                                           {{ old('incluye_aduanas_destino', $incoterm->incluye_aduanas_destino) ? 'checked' : '' }}>
                                    <label class="form-check-label">Aduanas Destino</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="incluye_transporte_destino" value="1"
                                           {{ old('incluye_transporte_destino', $incoterm->incluye_transporte_destino) ? 'checked' : '' }}>
                                    <label class="form-check-label">Transporte Destino</label>
                                </div>
                            </div>
                        </div>
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