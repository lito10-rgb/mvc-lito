@extends('layouts.admin')

@section('title', 'Nueva Cotización')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Nueva Cotización</h3>
        <a href="{{ route('admin.cotizaciones.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.cotizaciones.store') }}" method="POST" id="formCotizacion">
                @csrf

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Fecha</label>
                        <input type="date" name="fecha" class="form-control @error('fecha') is-invalid @enderror"
                               value="{{ old('fecha', date('Y-m-d')) }}" required>
                        @error('fecha') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Cliente</label>
                        <div class="input-group">
                            <input type="text" name="cliente" class="form-control @error('cliente') is-invalid @enderror"
                                   value="{{ old('cliente') }}" required>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#clienteModal">
                                <i class="fas fa-search"></i>
                            </button>
                            @error('cliente') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
                               value="{{ old('telefono') }}">
                        @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Correo</label>
                        <input type="hidden" name="cliente_id" value="{{ old('cliente_id') }}">
                        <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror"
                               value="{{ old('correo') }}">
                        @error('correo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="fw-bold mb-3"><i class="fas fa-building me-2"></i>Datos del Emisor (tu empresa)</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Seleccionar Emisor</label>
                        <select name="emisor_id" class="form-select" id="emisorSelect">
                            <option value="">-- Seleccionar usuario admin --</option>
                            @foreach($emisores as $e)
                                <option value="{{ $e->id }}" data-empresa="{{ $e->profile->empresa ?? '' }}"
                                    data-telefono="{{ $e->profile->telefono ?? '' }}"
                                    data-email="{{ $e->email }}"
                                    data-direccion="{{ $e->profile->direccion ?? '' }}">
                                    {{ $e->nombre }} {{ $e->apellidos }} {{ $e->profile->empresa ? '(' . $e->profile->empresa . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Logo de Empresa</label>
                        <select name="logo_id" class="form-select">
                            <option value="">-- Sin logo --</option>
                            @foreach($logos as $l)
                                <option value="{{ $l->id }}" {{ $l->por_defecto ? 'selected' : '' }}>
                                    {{ $l->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <div class="mt-1" id="logoPreview">
                            @foreach($logos as $l)
                                @if($l->por_defecto)
                                    <img src="{{ asset('storage/' . $l->ruta) }}" alt="" style="max-height:40px;">
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="fw-bold mb-3"><i class="fas fa-box me-2"></i>Productos</h5>

                <div class="table-responsive">
                    <table class="table table-bordered" id="tablaProductos">
                        <thead class="table-dark">
                            <tr>
                                <th style="width:5%;">Img</th>
                                <th style="width:28%;">Producto</th>
                                <th style="width:22%;">Descripción</th>
                                <th style="width:8%;">Cant.</th>
                                <th style="width:10%;">P. Unit.</th>
                                <th style="width:10%;">Subtotal</th>
                                <th style="width:3%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="fila-producto">
                                <td class="text-center align-middle">
                                    <img class="producto-thumb" src="" alt="" style="max-width:40px;max-height:40px;display:none;border-radius:4px;">
                                </td>
                                <td>
                                    <input type="hidden" name="productos[0][producto_id]" class="producto-id">
                                    <div class="input-group">
                                        <input type="text" name="productos[0][producto]" class="form-control form-control-sm" required>
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="abrirModalProducto(this)">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </td>
                                <td><textarea name="productos[0][descripcion]" class="form-control form-control-sm" rows="1"></textarea></td>
                                <td><input type="number" name="productos[0][cantidad]" class="form-control form-control-sm cantidad" value="1" min="1" required></td>
                                <td><input type="number" step="0.01" name="productos[0][precio_unitario]" class="form-control form-control-sm precio-unitario" value="0" min="0" required></td>
                                <td class="subtotal-cell text-end fw-bold">0.00</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm eliminar-fila" onclick="this.closest('tr').remove(); calcularTotales();">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <button type="button" class="btn btn-outline-primary btn-sm mb-3" onclick="agregarFila()">
                    <i class="fas fa-plus me-1"></i> Agregar producto
                </button>

                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Condiciones Comerciales</label>
                        <div class="input-group mb-2">
                            <select class="form-select" id="condicionSelect">
                                <option value="">-- Cargar condición predefinida --</option>
                                @foreach($condiciones as $cond)
                                    <option value="{{ $cond->id }}">{{ $cond->titulo }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-secondary" onclick="cargarCondicion()">
                                <i class="fas fa-file-import"></i> Cargar
                            </button>
                        </div>
                        <textarea name="condiciones" class="form-control @error('condiciones') is-invalid @enderror"
                                  rows="3" placeholder="Tiempo de entrega, forma de pago, validez de la oferta, etc.">{{ old('condiciones') }}</textarea>
                        @error('condiciones') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Subtotal</label>
                        <input type="text" class="form-control" id="displaySubtotal" readonly value="0.00">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Impuesto (S/)</label>
                        <input type="number" step="0.01" name="impuesto" class="form-control @error('impuesto') is-invalid @enderror"
                               value="{{ old('impuesto', 0) }}" min="0" oninput="calcularTotales()">
                        @error('impuesto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Descuento (%)</label>
                        <input type="number" step="0.01" name="descuento_porcentaje" class="form-control @error('descuento_porcentaje') is-invalid @enderror"
                               value="{{ old('descuento_porcentaje', 0) }}" min="0" max="100" oninput="calcularTotales()">
                        @error('descuento_porcentaje') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Dscto. (S/)</label>
                        <input type="text" class="form-control" id="displayDescuentoMonto" readonly value="0.00">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Total</label>
                        <input type="text" class="form-control fw-bold fs-5" id="displayTotal" readonly value="0.00">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                            <option value="pendiente" {{ old('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="aprobada" {{ old('estado') === 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                            <option value="rechazada" {{ old('estado') === 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                            <option value="completada" {{ old('estado') === 'completada' ? 'selected' : '' }}>Completada</option>
                        </select>
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

@include('admin.cotizaciones._product_modal')
@include('admin.cotizaciones._client_modal')
@endsection

@push('scripts')
<script>
let filaIndex = 1;

function agregarFila() {
    const tbody = document.querySelector('#tablaProductos tbody');
    const row = document.createElement('tr');
    row.className = 'fila-producto';
    row.innerHTML = `
        <td class="text-center align-middle">
            <img class="producto-thumb" src="" alt="" style="max-width:40px;max-height:40px;display:none;border-radius:4px;">
        </td>
        <td>
            <input type="hidden" name="productos[${filaIndex}][producto_id]" class="producto-id">
            <div class="input-group">
                <input type="text" name="productos[${filaIndex}][producto]" class="form-control form-control-sm" required>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="abrirModalProducto(this)">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </td>
        <td><textarea name="productos[${filaIndex}][descripcion]" class="form-control form-control-sm" rows="1"></textarea></td>
        <td><input type="number" name="productos[${filaIndex}][cantidad]" class="form-control form-control-sm cantidad" value="1" min="1" required></td>
        <td><input type="number" step="0.01" name="productos[${filaIndex}][precio_unitario]" class="form-control form-control-sm precio-unitario" value="0" min="0" required></td>
        <td class="subtotal-cell text-end fw-bold">0.00</td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove(); calcularTotales();">
                <i class="fas fa-times"></i>
            </button>
        </td>
    `;
    row.querySelectorAll('.cantidad, .precio-unitario').forEach(el => {
        el.addEventListener('input', () => calcularFila(row));
    });
    tbody.appendChild(row);
    filaIndex++;
}

function calcularFila(row) {
    const cant = parseFloat(row.querySelector('.cantidad').value) || 0;
    const pu = parseFloat(row.querySelector('.precio-unitario').value) || 0;
    const subtotal = cant * pu;
    row.querySelector('.subtotal-cell').textContent = subtotal.toFixed(2);
    calcularTotales();
}

function calcularTotales() {
    const subtotales = Array.from(document.querySelectorAll('.subtotal-cell')).map(td => parseFloat(td.textContent) || 0);
    const subtotal = subtotales.reduce((a, b) => a + b, 0);
    const impuesto = parseFloat(document.querySelector('[name="impuesto"]').value) || 0;
    const descPct = parseFloat(document.querySelector('[name="descuento_porcentaje"]').value) || 0;
    const descMonto = subtotal * (descPct / 100);
    const total = subtotal + impuesto - descMonto;

    document.getElementById('displaySubtotal').value = subtotal.toFixed(2);
    document.getElementById('displayDescuentoMonto').value = descMonto.toFixed(2);
    document.getElementById('displayTotal').value = total.toFixed(2);
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.fila-producto .cantidad, .fila-producto .precio-unitario').forEach(el => {
        el.addEventListener('input', function() {
            calcularFila(this.closest('tr'));
        });
    });
    calcularTotales();
});

function abrirModalProducto(btn) {
    const input = btn.closest('.input-group').querySelector('input');
    const modal = new bootstrap.Modal(document.getElementById('productoModal'));
    window._productoInput = input;
    modal.show();
}

@php $condicionesData = $condiciones->keyBy('id')->map->contenido; @endphp
var condicionesMap = @json($condicionesData);

function cargarCondicion() {
    const sel = document.getElementById('condicionSelect');
    const id = sel.value;
    if (id && condicionesMap[id]) {
        document.querySelector('[name="condiciones"]').value = condicionesMap[id].replace(/<br\s*\/?>/gi, '\n');
    }
}

// Hook into product modal selection
document.addEventListener('productoSeleccionado', function(e) {
    if (window._productoInput) {
        window._productoInput.value = e.detail.titulo;
        const row = window._productoInput.closest('tr');
        const pu = row.querySelector('.precio-unitario');
        if (pu) pu.value = e.detail.precio;
        calcularFila(row);
        bootstrap.Modal.getInstance(document.getElementById('productoModal')).hide();
    }
});
</script>
@endpush
