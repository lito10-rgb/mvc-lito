<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family:Arial,sans-serif;padding:20px;">
    @if($nombre)
        <p style="color:#666;margin:0 0 15px;">Para: <strong>{{ $nombre }}</strong></p>
    @endif
    <div style="white-space:normal;">{!! $mensaje !!}</div>
    <hr>
    <p style="color:#666;font-size:12px;">
        {{ $negocio->nombre ?? config('app.name') }} · Equipo de Compras<br>
        {{ $negocio->dominio ?? config('mail.from.address') }}
    </p>
</body>
</html>