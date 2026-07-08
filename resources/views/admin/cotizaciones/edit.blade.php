@extends('layouts.admin')

@section('title', 'Editar Cotización')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Editar Cotización</h3>
        <a href="{{ route('admin.cotizaciones.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.cotizaciones.update', $cotizacione) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Fecha</label>
                        <input type="date" name="fecha" class="form-control @error('fecha') is-invalid @enderror"
                               value="{{ old('fecha', $cotizacione->fecha->format('Y-m-d')) }}" required>
                        @error('fecha') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Cliente</label>
                        <div class="input-group">
                            <input type="text" name="cliente" class="form-control @error('cliente') is-invalid @enderror"
                                   value="{{ old('cliente', $cotizacione->cliente) }}" required>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#clienteModal">
                                <i class="fas fa-search"></i>
                            </button>
                            @error('cliente') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
                               value="{{ old('telefono', $cotizacione->telefono) }}">
                        @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Correo</label>
                        <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror"
                               value="{{ old('correo', $cotizacione->correo) }}">
                        @error('correo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Producto</label>
                        <div class="input-group">
                            <input type="text" name="producto" class="form-control @error('producto') is-invalid @enderror"
                                   value="{{ old('producto', $cotizacione->producto) }}" required>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#productoModal">
                                <i class="fas fa-search"></i>
                            </button>
                            @error('producto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror"
                                  rows="3">{{ old('descripcion', $cotizacione->descripcion) }}</textarea>
                        @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Cantidad</label>
                        <input type="number" name="cantidad" class="form-control @error('cantidad') is-invalid @enderror"
                               value="{{ old('cantidad', $cotizacione->cantidad) }}" min="1" required>
                        @error('cantidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Precio Unitario (S/)</label>
                        <input type="number" step="0.01" name="precio_unitario" class="form-control @error('precio_unitario') is-invalid @enderror"
                               value="{{ old('precio_unitario', $cotizacione->precio_unitario) }}" min="0" required>
                        @error('precio_unitario') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Impuesto (S/)</label>
                        <input type="number" step="0.01" name="impuesto" class="form-control @error('impuesto') is-invalid @enderror"
                               value="{{ old('impuesto', $cotizacione->impuesto) }}" min="0">
                        @error('impuesto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                            <option value="pendiente" {{ old('estado', $cotizacione->estado) === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="aprobada" {{ old('estado', $cotizacione->estado) === 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                            <option value="rechazada" {{ old('estado', $cotizacione->estado) === 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                            <option value="completada" {{ old('estado', $cotizacione->estado) === 'completada' ? 'selected' : '' }}>Completada</option>
                        </select>
                        @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-success mt-4">
                    <i class="fas fa-save me-1"></i> Actualizar
                </button>
            </form>
        </div>
    </div>
</div>

@include('admin.cotizaciones._product_modal')
@include('admin.cotizaciones._client_modal')
@endsection
