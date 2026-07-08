@extends('layouts.admin')

@section('title', 'Editar Cotización - EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Editar Cotización #{{ $cotizacione->id }}</h3>
        <a href="{{ route('admin.exim.cotizaciones.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.exim.cotizaciones.update', $cotizacione) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Cliente</label>
                        <select name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror" required>
                            <option value="">Seleccione...</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ old('cliente_id', $cotizacione->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->empresa }} - {{ $cliente->contacto }}
                                </option>
                            @endforeach
                        </select>
                        @error('cliente_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Fecha</label>
                        <input type="date" name="fecha" class="form-control @error('fecha') is-invalid @enderror"
                               value="{{ old('fecha', $cotizacione->fecha) }}" required>
                        @error('fecha') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Validez (días)</label>
                        <input type="number" name="validez_dias" class="form-control @error('validez_dias') is-invalid @enderror"
                               value="{{ old('validez_dias', $cotizacione->validez_dias) }}" min="1" required>
                        @error('validez_dias') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Incoterm</label>
                        <select name="incoterm_id" class="form-select @error('incoterm_id') is-invalid @enderror" required>
                            <option value="">Seleccione...</option>
                            @foreach($incoterms as $incoterm)
                                <option value="{{ $incoterm->id }}" {{ old('incoterm_id', $cotizacione->incoterm_id) == $incoterm->id ? 'selected' : '' }}>
                                    {{ $incoterm->codigo }} - {{ $incoterm->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('incoterm_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Transporte</label>
                        <select name="transporte_id" class="form-select @error('transporte_id') is-invalid @enderror" required>
                            <option value="">Seleccione...</option>
                            @foreach($transportes as $transporte)
                                <option value="{{ $transporte->id }}" {{ old('transporte_id', $cotizacione->transporte_id) == $transporte->id ? 'selected' : '' }}>
                                    {{ $transporte->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('transporte_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Contenedor</label>
                        <select name="contenedor_id" class="form-select @error('contenedor_id') is-invalid @enderror" required>
                            <option value="">Seleccione...</option>
                            @foreach($contenedores as $contenedor)
                                <option value="{{ $contenedor->id }}" {{ old('contenedor_id', $cotizacione->contenedor_id) == $contenedor->id ? 'selected' : '' }}>
                                    {{ strtoupper($contenedor->tipo) }} - {{ $contenedor->largo_cm }}×{{ $contenedor->ancho_cm }}cm
                                </option>
                            @endforeach
                        </select>
                        @error('contenedor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Moneda</label>
                        <select name="moneda_id" class="form-select @error('moneda_id') is-invalid @enderror" required>
                            <option value="">Seleccione...</option>
                            @foreach($monedas as $moneda)
                                <option value="{{ $moneda->id }}" {{ old('moneda_id', $cotizacione->moneda_id) == $moneda->id ? 'selected' : '' }}>
                                    {{ $moneda->codigo }} - {{ $moneda->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('moneda_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tipo de Cambio</label>
                        <input type="number" step="0.0001" name="tipo_cambio" class="form-control @error('tipo_cambio') is-invalid @enderror"
                               value="{{ old('tipo_cambio', $cotizacione->tipo_cambio) }}" min="0" required>
                        @error('tipo_cambio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Utilidad (%)</label>
                        <input type="number" step="0.01" name="utilidad_porcentaje" class="form-control @error('utilidad_porcentaje') is-invalid @enderror"
                               value="{{ old('utilidad_porcentaje', $cotizacione->utilidad_porcentaje) }}" min="0" required>
                        @error('utilidad_porcentaje') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                            <option value="borrador" {{ old('estado', $cotizacione->estado) === 'borrador' ? 'selected' : '' }}>Borrador</option>
                            <option value="enviada" {{ old('estado', $cotizacione->estado) === 'enviada' ? 'selected' : '' }}>Enviada</option>
                            <option value="aprobada" {{ old('estado', $cotizacione->estado) === 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                            <option value="rechazada" {{ old('estado', $cotizacione->estado) === 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                        </select>
                        @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Notas</label>
                        <textarea name="notas" class="form-control @error('notas') is-invalid @enderror"
                                  rows="3">{{ old('notas', $cotizacione->notas) }}</textarea>
                        @error('notas') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
