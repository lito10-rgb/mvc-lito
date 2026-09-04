@extends('layouts.admin')

@section('title', 'Editar Recibo N.º ' . $cotizacione->recibo_numero)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Editar Recibo N.º {{ $cotizacione->recibo_numero }}</h3>
        <div>
            <a href="{{ route('admin.cotizaciones.recibo', $cotizacione) }}" class="btn btn-outline-primary" target="_blank">
                <i class="fas fa-eye me-1"></i> Ver Recibo
            </a>
            <a href="{{ route('admin.cotizaciones.show', $cotizacione) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Datos del Recibo</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="alert alert-info py-2 mb-4">
                        <strong>Recibo N.º {{ $cotizacione->recibo_numero }}</strong><br>
                        Emitido el {{ ($cotizacione->recibo_fecha ?? $cotizacione->fecha)->format('d/m/Y h:i A') }}<br>
                        Cotización de referencia: <strong>N.º {{ $cotizacione->id }}</strong><br>
                        Total: <strong>S/ {{ number_format($cotizacione->total, 2) }}</strong>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.cotizaciones.recibo.update', $cotizacione) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Fecha del Recibo</label>
                        <input type="datetime-local" name="recibo_fecha" class="form-control"
                               value="{{ old('recibo_fecha', ($cotizacione->recibo_fecha ?? now())->format('Y-m-d\TH:i')) }}">
                        <div class="form-text">Fecha y hora de emisión del recibo (editable).</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Recibido Por</label>
                        <input type="text" name="recibo_recibido_por" class="form-control"
                               value="{{ old('recibo_recibido_por', $cotizacione->recibo_recibido_por) }}"
                               placeholder="Nombre de quien recibe el pago">
                        <div class="form-text">Quien recibe el pago (suele ser la empresa o el vendedor).</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Pagado Por</label>
                        <input type="text" name="recibo_pagado_por" class="form-control"
                               value="{{ old('recibo_pagado_por', $cotizacione->recibo_pagado_por ?? $cotizacione->cliente) }}">
                        <div class="form-text">Quien realiza el pago (por defecto el cliente).</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Método de Pago</label>
                        <select name="recibo_metodo_pago" class="form-select">
                            @php $mp = $cotizacione->recibo_metodo_pago; @endphp
                            @foreach(['Efectivo', 'Transferencia', 'Depósito', 'Tarjeta de Crédito', 'Tarjeta de Débito', 'Yape', 'Plin', 'Cheque'] as $opcion)
                                <option value="{{ $opcion }}" @selected($mp === $opcion)>{{ $opcion }}</option>
                            @endforeach
                            <option value="Otro" @selected($mp && !in_array($mp, ['Efectivo', 'Transferencia', 'Depósito', 'Tarjeta de Crédito', 'Tarjeta de Débito', 'Yape', 'Plin', 'Cheque']))>Otro</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Monto Pagado (S/)</label>
                        <div class="input-group">
                            <span class="input-group-text">S/</span>
                            <input type="number" step="0.01" min="0" name="recibo_monto_pagado" id="reciboMonto" class="form-control"
                                   value="{{ old('recibo_monto_pagado', is_null($cotizacione->recibo_monto_pagado) ? $cotizacione->total : $cotizacione->recibo_monto_pagado) }}">
                        </div>
                        <div class="form-text">
                            Total de la cotización: <strong>S/ {{ number_format($cotizacione->total, 2) }}</strong><br>
                            <span id="restaInfo"></span>
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="chkPagoTotal" checked>
                            <label class="form-check-label" for="chkPagoTotal">Pago total (monto = total)</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Observaciones</label>
                        <textarea name="recibo_observaciones" class="form-control" rows="4"
                                  placeholder="Notas, condiciones o instrucciones del recibo">{{ old('recibo_observaciones', $cotizacione->recibo_observaciones) }}</textarea>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Guardar y ver recibo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    var total = {{ $cotizacione->total }};
    var monto = document.getElementById('reciboMonto');
    var chk = document.getElementById('chkPagoTotal');
    var restaInfo = document.getElementById('restaInfo');

    function actualizar() {
        var val = parseFloat(monto.value);
        if (isNaN(val)) { restaInfo.textContent = ''; return; }
        var resta = total - val;
        if (resta > 0.005) {
            restaInfo.innerHTML = '<span class="text-danger"><strong>A CUENTA</strong> &mdash; Resta por pagar: S/ ' + resta.toFixed(2) + '</span>';
        } else {
            restaInfo.innerHTML = '<span class="text-success"><strong>PAGADO</strong></span>';
        }
    }

    chk.addEventListener('change', function() {
        if (chk.checked) {
            monto.value = total.toFixed(2);
            monto.disabled = true;
        } else {
            monto.disabled = false;
            monto.focus();
        }
        actualizar();
    });

    monto.addEventListener('input', actualizar);
    actualizar();
})();
</script>
@endpush
