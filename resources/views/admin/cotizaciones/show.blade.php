@extends('layouts.admin')

@section('title', 'Detalle de Cotización')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Cotización #{{ $cotizacione->id }}</h3>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#enviarCorreoModal">
                <i class="fas fa-envelope me-1"></i> Enviar Correo
            </button>
            <a href="{{ route('admin.cotizaciones.print', $cotizacione) }}" class="btn btn-outline-dark" target="_blank">
                <i class="fas fa-print me-1"></i> Imprimir
            </a>
            <a href="{{ route('admin.cotizaciones.edit', $cotizacione) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
            <a href="{{ route('admin.cotizaciones.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    @php
        $badges = ['pendiente' => 'warning', 'aprobada' => 'success', 'rechazada' => 'danger', 'completada' => 'info'];
    @endphp

    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Datos del Cliente</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="ps-0" style="width:120px;">Cliente</th>
                            <td>{{ $cotizacione->cliente }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Teléfono</th>
                            <td>{{ $cotizacione->telefono ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Correo</th>
                            <td>{{ $cotizacione->cliente?->email ?? $cotizacione->correo ?? '—' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Resumen</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="ps-0" style="width:120px;">Fecha</th>
                            <td>{{ $cotizacione->fecha->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Estado</th>
                            <td><span class="badge bg-{{ $badges[$cotizacione->estado] ?? 'secondary' }}">{{ ucfirst($cotizacione->estado) }}</span></td>
                        </tr>
                        <tr>
                            <th class="ps-0">Productos</th>
                            <td>{{ count($cotizacione->items) }} item(s)</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Emisor</th>
                            <td>{{ $cotizacione->emisor_data['empresa'] ?: ($cotizacione->emisor_data['nombre'] ?? '—') }}</td>
                        </tr>
                        @if($cotizacione->logo)
                        <tr>
                            <th class="ps-0">Logo</th>
                            <td><img src="{{ asset('storage/' . $cotizacione->logo->ruta) }}" alt="" style="max-height:30px;"></td>
                        </tr>
                        @endif
                        <tr>
                            <th class="ps-0">Creado</th>
                            <td>{{ $cotizacione->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Detalle de Productos</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:40px;">Img</th>
                        <th>#</th>
                        <th>Producto</th>
                        <th>Descripción</th>
                        <th class="text-center">Cant.</th>
                        <th class="text-end">P. Unit.</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cotizacione->items as $idx => $item)
                    <tr>
                        <td class="text-center align-middle">
                            @if(!empty($item['portada']))
                                <img src="{{ asset('storage/' . $item['portada']) }}" alt="" style="max-width:35px;max-height:35px;border-radius:4px;">
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>{{ $idx + 1 }}</td>
                        <td>{{ $item['producto'] }}</td>
                        <td>{{ $item['descripcion'] ?? '' }}</td>
                        <td class="text-center">{{ $item['cantidad'] }}</td>
                        <td class="text-end">S/ {{ number_format($item['precio_unitario'], 2) }}</td>
                        <td class="text-end">S/ {{ number_format($item['cantidad'] * $item['precio_unitario'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-group-divider">
                    @if(($cotizacione->descuento_monto ?? 0) > 0)
                    <tr>
                        <th colspan="6" class="text-end">Descuento ({{ $cotizacione->descuento_porcentaje }}%)</th>
                        <td class="text-end">- S/ {{ number_format($cotizacione->descuento_monto, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th colspan="6" class="text-end">Subtotal</th>
                        <td class="text-end">S/ {{ number_format($cotizacione->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <th colspan="6" class="text-end">Impuesto</th>
                        <td class="text-end">S/ {{ number_format($cotizacione->impuesto, 2) }}</td>
                    </tr>
                    <tr class="table-active">
                        <th colspan="6" class="text-end">Total</th>
                        <td class="text-end"><strong>S/ {{ number_format($cotizacione->total, 2) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    @if($cotizacione->condiciones)
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Condiciones Comerciales</h5>
        </div>
        <div class="card-body">
            {!! nl2br(e(str_replace(['<br>', '<br/>', '<br />'], "\n", $cotizacione->condiciones))) !!}
        </div>
    </div>
    @endif
</div>
@if(!request()->has('print'))
@endif

<div class="modal fade" id="enviarCorreoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formEnviarCorreo">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">Enviar Correo - Cotización #{{ $cotizacione->id }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Para <span class="text-danger">*</span></label>
                            <input type="email" name="para" class="form-control"
                                   value="{{ $cotizacione->cliente?->email ?? $cotizacione->correo }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">CC (copia)</label>
                            <input type="email" name="cc" class="form-control" placeholder="correo@ejemplo.com">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Plantilla</label>
                            <select class="form-select" id="plantillaSelect">
                                <option value="">-- Seleccionar plantilla --</option>
                                @foreach($plantillas as $p)
                                    <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Asunto <span class="text-danger">*</span></label>
                            <input type="text" name="asunto" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Contenido <span class="text-danger">*</span></label>
                            <textarea name="contenido" class="form-control" rows="8" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Adjuntos (opcional)</label>
                            <input type="file" name="adjuntos[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png,.webp,.doc,.docx">
                            <div class="form-text">Máx. 10 MB por archivo. Se adjuntará automáticamente el PDF de la cotización.</div>
                        </div>
                    </div>
                    <div id="emailError" class="alert alert-danger d-none mt-3"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnEnviarCorreo">
                        <i class="fas fa-paper-plane me-1"></i> Enviar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@php $plantillasData = $plantillas->keyBy('id')->map(fn($p) => ['asunto' => $p->asunto, 'contenido' => $p->contenido]); @endphp
@push('scripts')
<script>
var plantillasMap = @json($plantillasData);

document.getElementById('plantillaSelect')?.addEventListener('change', function() {
    const id = this.value;
    if (id && plantillasMap[id]) {
        document.querySelector('[name="asunto"]').value = plantillasMap[id].asunto || '';
        document.querySelector('[name="contenido"]').value = plantillasMap[id].contenido || '';
    }
});

document.getElementById('formEnviarCorreo')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('btnEnviarCorreo');
    const errorDiv = document.getElementById('emailError');
    errorDiv.classList.add('d-none');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Enviando...';

    try {
        const formData = new FormData(this);
        const plantillaId = document.getElementById('plantillaSelect').value;
        if (plantillaId) formData.append('plantilla_id', plantillaId);

        const res = await fetch('{{ route("admin.cotizaciones.enviarCorreo", $cotizacione) }}', {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json',
            },
            body: formData,
        });

        const text = await res.text();
        let data;
        try { data = JSON.parse(text); } catch(e) {
            throw new Error('Respuesta inesperada del servidor: ' + text.substring(0, 200));
        }

        if (!res.ok) throw new Error(data.message || 'Error al enviar');

        alert('Correo enviado correctamente.');
        bootstrap.Modal.getInstance(document.getElementById('enviarCorreoModal')).hide();
        this.reset();
    } catch (err) {
        errorDiv.textContent = err.message;
        errorDiv.classList.remove('d-none');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Enviar';
    }
});
</script>
@endpush
