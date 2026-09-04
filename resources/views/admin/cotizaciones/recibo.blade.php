<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo N.º {{ $cotizacione->recibo_numero }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 13px; color: #222; padding: 40px; }
        .no-print { text-align: right; margin-bottom: 20px; }
        .no-print a, .no-print button { padding: 8px 20px; font-size: 14px; cursor: pointer; text-decoration: none; border-radius: 6px; border: none; margin-left: 6px; }
        .no-print .btn-print { background: #1a1a2e; color: #fff; }
        .no-print .btn-edit { background: #f59e0b; color: #fff; }
        .no-print .btn-back { background: #e5e7eb; color: #333; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; margin-bottom: 24px; border-bottom: 3px solid #1a1a2e; padding-bottom: 16px; }
        .header .left { display: flex; align-items: flex-start; gap: 15px; }
        .empresa-info { font-size: 13px; line-height: 1.45; }
        .empresa-info .empresa { font-size: 18px; font-weight: 700; color: #1a1a2e; margin-bottom: 3px; }
        .empresa-info .meta { color: #4b5563; font-size: 12px; }
        .empresa-info .meta.doc { color: #1a1a2e; margin-top: 2px; margin-bottom: 2px; }
        .header h1 { font-size: 30px; color: #1a1a2e; letter-spacing: 1px; }
        .header .sub { color: #6b7280; font-size: 12px; margin-top: 2px; }
        .recibo-badge { background: #f59e0b; color: #fff; display: inline-block; padding: 6px 16px; border-radius: 4px; font-size: 16px; font-weight: 700; margin-top: 6px; }
        .emisor { text-align: right; font-size: 13px; }
        .emisor .meta { color: #6b7280; font-size: 12px; line-height: 1.5; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px; }
        .info-box { border: 1px solid #d1d5db; border-radius: 6px; padding: 14px; }
        .info-box h3 { font-size: 12px; color: #6b7280; margin-bottom: 8px; text-transform: uppercase; letter-spacing: .5px; }
        .info-box p { font-size: 14px; margin-bottom: 2px; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.items th, table.items td { padding: 8px 10px; text-align: left; border: 1px solid #d1d5db; }
        table.items th { background: #f3f4f6; font-size: 11px; text-transform: uppercase; letter-spacing: .5px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .receipt-box { border: 2px solid #1a1a2e; border-radius: 8px; padding: 18px; margin-bottom: 24px; }
        .receipt-box h2 { font-size: 18px; color: #1a1a2e; margin-bottom: 14px; text-transform: uppercase; letter-spacing: 1px; border-bottom: 2px solid #f59e0b; padding-bottom: 8px; }
        .badge-cuenta { display: inline-block; padding: 4px 14px; border-radius: 4px; font-size: 13px; letter-spacing: 1px; background: #f59e0b; color: #fff; margin-left: 8px; vertical-align: middle; }
        .badge-pagado { background: #10b981; }
        .receipt-fields .resta { color: #b91c1c; font-weight: 700; }
        .receipt-fields { width: 100%; border-collapse: collapse; }
        .receipt-fields td { padding: 10px 12px; border: 1px solid #e5e7eb; vertical-align: top; }
        .receipt-fields .label { width: 32%; background: #f9fafb; font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: .5px; }
        .receipt-fields .value { font-size: 14px; }
        .receipt-fields .amount { font-size: 22px; font-weight: 700; color: #1a1a2e; }
        .observation { margin-top: 18px; }
        .observation h3 { font-size: 12px; color: #6b7280; margin-bottom: 6px; text-transform: uppercase; letter-spacing: .5px; }
        .observation p { font-size: 13px; line-height: 1.6; white-space: pre-wrap; }
        .signature { margin-top: 60px; display: flex; justify-content: space-between; gap: 40px; }
        .signature .sig { text-align: center; width: 45%; }
        .signature .line { border-top: 1.5px solid #222; padding-top: 8px; font-size: 12px; color: #6b7280; }
        .footer { margin-top: 40px; font-size: 11px; color: #9ca3af; text-align: center; border-top: 1px solid #e5e7eb; padding-top: 12px; }
        @media print {
            body { padding: 24px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn-print">Imprimir / PDF</button>
        <a href="{{ route('admin.cotizaciones.recibo.edit', $cotizacione) }}" class="btn-edit">Editar</a>
        <a href="{{ route('admin.cotizaciones.show', $cotizacione) }}" class="btn-back">Volver</a>
    </div>

    @php
        $moneda = 'S/ ';
        $_total = (float) $cotizacione->total;
        $_pagado = ($cotizacione->recibo_monto_pagado !== null) ? (float) $cotizacione->recibo_monto_pagado : $_total;
        $_resta = max($_total - $_pagado, 0);
        $_es_cuenta = $_resta > 0.005;
    @endphp

    @php
        $_negLogo = $cotizacione->logo ? $cotizacione->logo->negocio : null;
        $_neg = negocio_actual();
        $_empresa = $cotizacione->emisor_data ?? null;
        $_fuente = null;
        if (!empty($_empresa['empresa']) || !empty($_empresa['nombre'])) {
            $_fuente = $_empresa;
        } elseif ($_negLogo) {
            $_fuente = $_negLogo;
        } else {
            $_fuente = $_neg;
        }
        $_emp_nombre = ($_fuente['empresa'] ?? null) ?: ($_fuente->empresa ?? null) ?: ($_fuente['nombre'] ?? $_fuente->nombre ?? '');
        $_emp_direccion = $_fuente['direccion'] ?? $_fuente->footer_address ?? null;
        $_emp_telefono = $_fuente['telefono'] ?? $_fuente->footer_phone ?? null;
        $_emp_email = $_fuente['email'] ?? $_fuente->footer_email ?? null;
        $_emp_tipo_doc = $_empresa['tipo_documento'] ?? ($_negLogo && $_negLogo->tipo_documento ?? null) ?? $_neg->tipo_documento ?? null;
        $_emp_num_doc = $_empresa['num_documento'] ?? null;
        if (empty($_emp_num_doc) && $cotizacione->emisor && $cotizacione->emisor->profile) {
            $_pf = $cotizacione->emisor->profile;
            $_emp_tipo_doc = $_emp_tipo_doc ?: $_pf->tipo_documento;
            $_emp_num_doc = $_emp_num_doc ?: $_pf->num_documento;
        }
        $_logo = $cotizacione->logo
            ? asset('storage/' . $cotizacione->logo->ruta)
            : ($_negLogo && $_negLogo->logo ? asset('storage/' . $_negLogo->logo) : null);
    @endphp

    <div class="header">
        <div class="left">
            @if($_logo)
                <img src="{{ $_logo }}" alt="{{ $_emp_nombre }}" style="max-height:70px;">
            @endif
            <div class="empresa-info">
                <p class="empresa">{{ $_emp_nombre }}</p>
                @if($_emp_num_doc && $_emp_num_doc !== '0' && $_emp_num_doc != '2147483647')
                    <p class="meta doc"><strong>{{ strtoupper($_emp_tipo_doc ?: 'N.º Doc') }}: {{ $_emp_num_doc }}</strong></p>
                @endif
                @if($_emp_direccion)<p class="meta"><i></i>{{ $_emp_direccion }}</p>@endif
                @if($_emp_telefono)<p class="meta"><i></i>Tel: {{ $_emp_telefono }}</p>@endif
                @if($_emp_email)<p class="meta"><i></i>{{ $_emp_email }}</p>@endif
            </div>
        </div>
        <div class="emisor">
            <h1>RECIBO</h1>
            <div class="sub">Comprobante de pago</div>
            <span class="recibo-badge">N.º {{ str_pad($cotizacione->recibo_numero ?? 0, 6, '0', STR_PAD_LEFT) }}</span>
            <p class="meta" style="margin-top:6px;">Fecha: <strong>{{ ($cotizacione->recibo_fecha ?? $cotizacione->fecha)->format('d/m/Y') }}</strong></p>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-box">
            <h3>Recibido de (Cliente)</h3>
            <p><strong>{{ $cotizacione->recibo_pagado_por ?: $cotizacione->cliente }}</strong></p>
            @if($cotizacione->telefono)<p>Tel: {{ $cotizacione->telefono }}</p>@endif
            @php $email = $cotizacione->cliente?->email ?? $cotizacione->correo; @endphp
            @if($email)<p>Email: {{ $email }}</p>@endif
        </div>
        <div class="info-box">
            <h3>Detalles del Recibo</h3>
            <p>Fecha de emisión: <strong>{{ ($cotizacione->recibo_fecha ?? $cotizacione->fecha)->format('d/m/Y') }}</strong></p>
            <p>Referencia: Cotización N.º {{ $cotizacione->id }}</p>
            @if($cotizacione->recibo_metodo_pago)<p>Método de pago: <strong>{{ $cotizacione->recibo_metodo_pago }}</strong></p>@endif
        </div>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th style="width:4%;">#</th>
                <th style="width:34%;">Concepto</th>
                <th style="width:24%;">Descripción</th>
                <th style="width:8%;text-align:center;">Cant.</th>
                <th style="width:14%;text-align:right;">P. Unit.</th>
                <th style="width:16%;text-align:right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cotizacione->items as $idx => $item)
            @php $m = ($item['moneda'] ?? 'PEN') === 'USD' ? '$' : 'S/'; @endphp
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $item['producto'] }}</td>
                <td>{{ $item['descripcion'] ?? '' }}</td>
                <td class="text-center">{{ $item['cantidad'] }}</td>
                <td class="text-right">{{ $m }} {{ number_format($item['precio_unitario'], 2) }}</td>
                <td class="text-right">{{ $m }} {{ number_format($item['cantidad'] * $item['precio_unitario'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="receipt-box">
        <h2>Recibo de Pago
            @if($_es_cuenta)
                <span class="badge-cuenta">A CUENTA</span>
            @else
                <span class="badge-cuenta badge-pagado">PAGADO</span>
            @endif
        </h2>
        <table class="receipt-fields">
            <tr>
                <td class="label">Recibido Por</td>
                <td class="value">{{ $cotizacione->recibo_recibido_por ?: '—' }}</td>
                <td class="label">Fecha</td>
                <td class="value">{{ ($cotizacione->recibo_fecha ?? $cotizacione->fecha)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="label">Pagado Por</td>
                <td class="value">{{ $cotizacione->recibo_pagado_por ?: $cotizacione->cliente }}</td>
                <td class="label">Método de Pago</td>
                <td class="value">{{ $cotizacione->recibo_metodo_pago ?: 'Efectivo' }}</td>
            </tr>
            <tr>
                <td class="label">Número de Recibo</td>
                <td class="value">N.º {{ $cotizacione->recibo_numero }}</td>
                <td class="label">Monto Pagado</td>
                <td class="value">
                    <span class="amount">{{ $moneda }}{{ number_format($_pagado, 2) }}</span>
                </td>
            </tr>
            <tr>
                <td class="label">Monto Total</td>
                <td class="value">{{ $moneda }}{{ number_format($_total, 2) }}</td>
                <td class="label">Resta por Pagar</td>
                <td class="value {{ $_es_cuenta ? 'resta' : '' }}">{{ $moneda }}{{ number_format($_resta, 2) }}</td>
            </tr>
        </table>
        @if($cotizacione->recibo_observaciones)
        <div class="observation">
            <h3>Observaciones</h3>
            <p>{{ $cotizacione->recibo_observaciones }}</p>
        </div>
        @endif
    </div>

    <div class="signature">
        <div class="sig">
            <div class="line">Recibido Por / Firma</div>
        </div>
        <div class="sig">
            <div class="line">Entregado Por / Firma</div>
        </div>
    </div>

    <div class="footer">
        Documento generado el {{ now()->format('d/m/Y H:i') }} &mdash; {{ $cotizacione->emisor_data['empresa'] ?? ($cotizacione->emisor_data['nombre'] ?? 'Sistema de Cotizaciones') }}
    </div>
</body>
</html>
