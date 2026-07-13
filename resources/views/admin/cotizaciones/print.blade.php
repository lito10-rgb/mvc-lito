<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cotización #{{ $cotizacione->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 13px; color: #222; padding: 40px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; }
        .header h1 { font-size: 26px; color: #1a1a2e; margin-bottom: 4px; }
        .header .badge { display: inline-block; padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; }
        .badge-warning { background: #f59e0b; color: #fff; }
        .badge-success { background: #10b981; color: #fff; }
        .badge-danger { background: #ef4444; color: #fff; }
        .badge-info { background: #3b82f6; color: #fff; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        .info-box { border: 1px solid #d1d5db; border-radius: 6px; padding: 14px; }
        .info-box h3 { font-size: 14px; color: #6b7280; margin-bottom: 8px; text-transform: uppercase; letter-spacing: .5px; }
        .info-box p { font-size: 14px; margin-bottom: 2px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 8px 10px; text-align: left; border: 1px solid #d1d5db; }
        th { background: #f3f4f6; font-size: 12px; text-transform: uppercase; letter-spacing: .5px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .totals { width: 350px; margin-left: auto; }
        .totals td { border: none; padding: 4px 10px; }
        .totals .final td { font-size: 16px; font-weight: 700; border-top: 2px solid #222; padding-top: 8px; }
        .condiciones { border: 1px solid #d1d5db; border-radius: 6px; padding: 14px; margin-top: 20px; }
        .condiciones h3 { font-size: 14px; color: #6b7280; margin-bottom: 6px; text-transform: uppercase; letter-spacing: .5px; }
        .condiciones p { font-size: 13px; line-height: 1.6; }
        .footer { margin-top: 40px; font-size: 11px; color: #9ca3af; text-align: center; border-top: 1px solid #e5e7eb; padding-top: 12px; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align:right;margin-bottom:20px;">
        <button onclick="window.print()" style="padding:8px 20px;font-size:14px;cursor:pointer;">Imprimir / PDF</button>
    </div>

    <div class="header">
        <div style="display:flex;align-items:center;gap:15px;">
            @if($cotizacione->logo)
                <img src="{{ asset('storage/' . $cotizacione->logo->ruta) }}" alt="Logo" style="max-height:60px;">
            @endif
            <div>
                <h1>COTIZACIÓN</h1>
                <p style="color:#6b7280;">N.º {{ str_pad($cotizacione->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>
        <div style="text-align:right;">
            @if($cotizacione->emisor_data)
                <p style="font-size:14px;font-weight:600;">{{ $cotizacione->emisor_data['empresa'] ?: $cotizacione->emisor_data['nombre'] }}</p>
                <p style="font-size:12px;color:#6b7280;">
                    @if($cotizacione->emisor_data['direccion'])<span>{{ $cotizacione->emisor_data['direccion'] }}<br></span>@endif
                    @if($cotizacione->emisor_data['telefono'])<span>{{ $cotizacione->emisor_data['telefono'] }}<br></span>@endif
                    {{ $cotizacione->emisor_data['email'] ?? '' }}
                </p>
            @else
                <p style="font-size:14px;font-weight:600;">{{ config('app.name') }}</p>
                <p style="font-size:12px;color:#6b7280;">{{ config('theme.telefono') }}<br>{{ config('theme.email') }}</p>
            @endif
        </div>
    </div>

    <div class="info-grid">
        <div class="info-box">
            <h3>Cliente</h3>
            <p><strong>{{ $cotizacione->cliente }}</strong></p>
            @if($cotizacione->telefono)<p>Tel: {{ $cotizacione->telefono }}</p>@endif
            @php $email = $cotizacione->cliente?->email ?? $cotizacione->correo; @endphp
            @if($email)<p>Email: {{ $email }}</p>@endif
        </div>
        <div class="info-box">
            <h3>Detalles</h3>
            <p>Fecha: <strong>{{ $cotizacione->fecha->format('d/m/Y') }}</strong></p>
            <p>Estado: <span class="badge badge-{{ $cotizacione->estado === 'pendiente' ? 'warning' : ($cotizacione->estado === 'aprobada' ? 'success' : ($cotizacione->estado === 'rechazada' ? 'danger' : 'info')) }}">{{ ucfirst($cotizacione->estado) }}</span></p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:40px;">Img</th>
                <th style="width:4%;">#</th>
                <th style="width:30%;">Producto</th>
                <th style="width:20%;">Descripción</th>
                <th style="width:8%;text-align:center;">Cant.</th>
                <th style="width:10%;text-align:right;">P. Unit.</th>
                <th style="width:12%;text-align:right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cotizacione->items as $idx => $item)
            <tr>
                <td class="text-center">
                    @if(!empty($item['portada']))
                        <img src="{{ asset('storage/' . $item['portada']) }}" alt="" style="max-width:35px;max-height:35px;border-radius:4px;">
                    @endif
                </td>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $item['producto'] }}</td>
                <td>{{ $item['descripcion'] ?? '' }}</td>
                <td class="text-center">{{ $item['cantidad'] }}</td>
                <td class="text-right">S/ {{ number_format($item['precio_unitario'], 2) }}</td>
                <td class="text-right">S/ {{ number_format($item['cantidad'] * $item['precio_unitario'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr><td>Subtotal</td><td class="text-right">S/ {{ number_format($cotizacione->subtotal, 2) }}</td></tr>
        @if(($cotizacione->descuento_monto ?? 0) > 0)
        <tr><td>Descuento ({{ $cotizacione->descuento_porcentaje }}%)</td><td class="text-right">- S/ {{ number_format($cotizacione->descuento_monto, 2) }}</td></tr>
        @endif
        <tr><td>Impuesto</td><td class="text-right">S/ {{ number_format($cotizacione->impuesto, 2) }}</td></tr>
        <tr class="final"><td>Total</td><td class="text-right">S/ {{ number_format($cotizacione->total, 2) }}</td></tr>
    </table>

    @if($cotizacione->condiciones)
    <div class="condiciones">
        <h3>Condiciones Comerciales</h3>
        <p>{!! nl2br(e(str_replace(['<br>', '<br/>', '<br />'], "\n", $cotizacione->condiciones))) !!}</p>
    </div>
    @endif

    <div class="footer">
        Documento generado el {{ now()->format('d/m/Y H:i') }} &mdash; {{ $cotizacione->emisor_data['empresa'] ?? ($cotizacione->emisor_data['nombre'] ?? 'Sistema de Cotizaciones') }}
    </div>
</body>
</html>
