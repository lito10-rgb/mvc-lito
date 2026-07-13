<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family:Arial,sans-serif;padding:20px;">
    <p>{!! $contenido !!}</p>
    <hr>
    <p style="color:#666;font-size:12px;">
        Cotización #{{ $cotizacion->id }} | {{ $cotizacion->cliente }}<br>
        <a href="{{ route('admin.cotizaciones.show', $cotizacion) }}">Ver cotización</a>
    </p>
</body>
</html>
