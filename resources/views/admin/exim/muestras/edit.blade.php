@extends('layouts.admin')

@section('title', 'Editar Muestra - EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Editar Muestra</h3>
        <a href="{{ route('admin.exim.muestras.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.exim.muestras.update', $muestra) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Producto</label>
                        <select name="producto_id" class="form-select @error('producto_id') is-invalid @enderror" required>
                            <option value="">Seleccione...</option>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->id }}" {{ old('producto_id', $muestra->producto_id) == $producto->id ? 'selected' : '' }}>
                                    {{ $producto->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('producto_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Cotización</label>
                        <select name="cotizacion_id" class="form-select @error('cotizacion_id') is-invalid @enderror">
                            <option value="">Ninguna</option>
                            @foreach($cotizaciones as $cotizacion)
                                <option value="{{ $cotizacion->id }}" {{ old('cotizacion_id', $muestra->cotizacion_id) == $cotizacion->id ? 'selected' : '' }}>
                                    #{{ $cotizacion->id }} - {{ $cotizacion->cliente->empresa ?? '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('cotizacion_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Cantidad</label>
                        <input type="number" name="cantidad" class="form-control @error('cantidad') is-invalid @enderror"
                               value="{{ old('cantidad', $muestra->cantidad) }}" min="1" required>
                        @error('cantidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Peso (kg)</label>
                        <input type="number" step="0.01" name="peso_kg" class="form-control @error('peso_kg') is-invalid @enderror"
                               value="{{ old('peso_kg', $muestra->peso_kg) }}" min="0" required>
                        @error('peso_kg') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tipo de Empaque</label>
                        <input type="text" name="tipo_empaque" class="form-control @error('tipo_empaque') is-invalid @enderror"
                               value="{{ old('tipo_empaque', $muestra->tipo_empaque) }}" placeholder="Ej: Bolsa sellada">
                        @error('tipo_empaque') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Caja</label>
                        <input type="text" name="caja" class="form-control @error('caja') is-invalid @enderror"
                               value="{{ old('caja', $muestra->caja) }}" placeholder="N° de caja">
                        @error('caja') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Etiquetas</label>
                        <input type="text" name="etiquetas" class="form-control @error('etiquetas') is-invalid @enderror"
                               value="{{ old('etiquetas', $muestra->etiquetas) }}" placeholder="Descripción de etiquetas">
                        @error('etiquetas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Certificados</label>
                        <input type="text" name="certificados" class="form-control @error('certificados') is-invalid @enderror"
                               value="{{ old('certificados', $muestra->certificados) }}" placeholder="Ej: Fitosanitario">
                        @error('certificados') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Courier</label>
                        <select name="courier" class="form-select @error('courier') is-invalid @enderror" required>
                            <option value="DHL" {{ old('courier', $muestra->courier) === 'DHL' ? 'selected' : '' }}>DHL</option>
                            <option value="FedEx" {{ old('courier', $muestra->courier) === 'FedEx' ? 'selected' : '' }}>FedEx</option>
                            <option value="UPS" {{ old('courier', $muestra->courier) === 'UPS' ? 'selected' : '' }}>UPS</option>
                            <option value="TNT" {{ old('courier', $muestra->courier) === 'TNT' ? 'selected' : '' }}>TNT</option>
                            <option value="Otro" {{ old('courier', $muestra->courier) === 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('courier') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Seguro</label>
                        <input type="number" step="0.01" name="seguro" class="form-control @error('seguro') is-invalid @enderror"
                               value="{{ old('seguro', $muestra->seguro) }}" min="0">
                        @error('seguro') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Valor de la Muestra</label>
                        <input type="number" step="0.01" name="valor_muestra" class="form-control @error('valor_muestra') is-invalid @enderror"
                               value="{{ old('valor_muestra', $muestra->valor_muestra) }}" min="0">
                        @error('valor_muestra') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Costo de Envío</label>
                        <input type="number" step="0.01" name="costo_envio" class="form-control @error('costo_envio') is-invalid @enderror"
                               value="{{ old('costo_envio', $muestra->costo_envio) }}" min="0" required>
                        @error('costo_envio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Costo Total</label>
                        <input type="number" step="0.01" name="costo_total" class="form-control @error('costo_total') is-invalid @enderror"
                               value="{{ old('costo_total', $muestra->costo_total) }}" min="0" required>
                        @error('costo_total') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
