@extends('layouts.admin')

@section('title', 'Editar Cotización')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Editar Cotización #{{ $cotizacione->id }}</h3>
        <a href="{{ route('admin.cotizaciones.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.cotizaciones.update', $cotizacione) }}" method="POST" id="formCotizacion">
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

                    <div class="col-md-2">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
                               value="{{ old('telefono', $cotizacione->telefono) }}">
                        @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Correo</label>
                        <input type="hidden" name="cliente_id" value="{{ old('cliente_id', $cotizacione->cliente_id) }}">
                        <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror"
                               value="{{ old('correo', $cotizacione->cliente?->email ?? $cotizacione->correo) }}">
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
                                <option value="{{ $e->id }}" {{ $cotizacione->emisor_id == $e->id ? 'selected' : '' }}
                                    data-empresa="{{ $e->profile->empresa ?? '' }}"
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
                        <select name="logo_id" class="form-select" onchange="actualizarLogoPreview(this)">
                            <option value="">-- Sin logo --</option>
                            @foreach($logos as $l)
                                <option value="{{ $l->id }}" {{ $cotizacione->logo_id == $l->id ? 'selected' : '' }}>
                                    {{ $l->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <div class="mt-1" id="logoPreview">
                            @php $logoSeleccionado = $logos->firstWhere('id', $cotizacione->logo_id); @endphp
                            @if($logoSeleccionado)
                                <img src="{{ asset('storage/' . $logoSeleccionado->ruta) }}" alt="" style="max-height:40px;">
                            @endif
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
                            @foreach($cotizacione->items as $i => $item)
                            <tr class="fila-producto">
                                <td class="text-center align-middle">
                                    <img class="producto-thumb" src="{{ isset($item['portada']) && $item['portada'] ? asset('storage/' . $item['portada']) : '' }}"
                                         alt="" style="max-width:40px;max-height:40px;{{ empty($item['portada']) ? 'display:none;' : '' }}border-radius:4px;">
                                </td>
                                <td>
                                    <input type="hidden" name="productos[{{ $i }}][producto_id]" class="producto-id" value="{{ $item['producto_id'] ?? '' }}">
                                    <div class="input-group">
                                        <input type="text" name="productos[{{ $i }}][producto]" class="form-control form-control-sm"
                                               value="{{ $item['producto'] }}" required>
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="abrirModalProducto(this)">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </td>
                                <td><textarea name="productos[{{ $i }}][descripcion]" class="form-control form-control-sm" rows="1">{{ $item['descripcion'] ?? '' }}</textarea></td>
                                <td><input type="number" name="productos[{{ $i }}][cantidad]" class="form-control form-control-sm cantidad" value="{{ $item['cantidad'] }}" min="1" required></td>
                                <td><input type="number" step="0.01" name="productos[{{ $i }}][precio_unitario]" class="form-control form-control-sm precio-unitario" value="{{ $item['precio_unitario'] }}" min="0" required></td>
                                <td class="subtotal-cell text-end fw-bold">{{ number_format($item['cantidad'] * $item['precio_unitario'], 2) }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove(); calcularTotales();">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
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
                                  rows="3" placeholder="Tiempo de entrega, forma de pago, validez de la oferta, etc.">{{ old('condiciones', str_replace(['<br>', '<br/>', '<br />'], "\n", $cotizacione->condiciones ?? '')) }}</textarea>
                        @error('condiciones') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Subtotal</label>
                        <input type="text" class="form-control" id="displaySubtotal" readonly value="{{ number_format($cotizacione->subtotal, 2) }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Impuesto (S/)</label>
                        <input type="number" step="0.01" name="impuesto" class="form-control @error('impuesto') is-invalid @enderror"
                               value="{{ old('impuesto', $cotizacione->impuesto) }}" min="0" oninput="calcularTotales()">
                        @error('impuesto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Descuento (%)</label>
                        <input type="number" step="0.01" name="descuento_porcentaje" class="form-control @error('descuento_porcentaje') is-invalid @enderror"
                               value="{{ old('descuento_porcentaje', $cotizacione->descuento_porcentaje) }}" min="0" max="100" oninput="calcularTotales()">
                        @error('descuento_porcentaje') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Dscto. (S/)</label>
                        <input type="text" class="form-control" id="displayDescuentoMonto" readonly value="{{ number_format($cotizacione->descuento_monto ?? 0, 2) }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Total</label>
                        <input type="text" class="form-control fw-bold fs-5" id="displayTotal" readonly value="{{ number_format($cotizacione->total, 2) }}">
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

@push('scripts')
<script>
let filaIndex = {{ count($cotizacione->items) }};

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

function actualizarLogoPreview(sel) {
    const preview = document.getElementById('logoPreview');
    @php $logosData = $logos->map(function($l) { return ['id' => $l->id, 'url' => asset('storage/' . $l->ruta)]; })->values(); @endphp
    const logos = @json($logosData);
    const selected = logos.find(l => l.id == sel.value);
    preview.innerHTML = selected
        ? `<img src="${selected.url}" alt="" style="max-height:40px;">`
        : '';
}
</script>
@endpush
