<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif;background:#f4f4f4;padding:30px;">
<div style="max-width:600px;margin:auto;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.1);">
    <div style="background:linear-gradient(135deg,#f59e0b,#ef4444);padding:30px;text-align:center;">
        <h1 style="color:#fff;margin:0;font-size:28px;">Recordatorio: {{ $concurso->nombre }}</h1>
    </div>
    <div style="padding:30px;">
        <p style="font-size:16px;">Hola <strong>{{ $user->nombre }}</strong>,</p>
        <p style="font-size:15px;color:#555;">Te recordamos que estás participando en nuestro sorteo <strong>{{ $concurso->nombre }}</strong>.</p>
        <p style="font-size:15px;color:#555;">La fecha del sorteo es el <strong>{{ $concurso->fecha_sorteo->format('d/m/Y') }}</strong>. ¡No olvides tu código!</p>

        <div style="background:#fef3c7;border:2px dashed #f59e0b;border-radius:10px;padding:25px;text-align:center;margin:25px 0;">
            <p style="margin:0 0 5px;color:#92400e;font-size:14px;">Tu código de participación es:</p>
            <p style="margin:0;font-size:36px;font-weight:bold;color:#92400e;letter-spacing:5px;">{{ $participante->codigo }}</p>
        </div>

        <table width="100%" style="margin:15px 0;">
            <tr><td style="padding:8px;color:#666;">Premio:</td><td style="padding:8px;font-weight:bold;">{{ $concurso->premio ?: 'Sorprise' }}</td></tr>
            <tr><td style="padding:8px;color:#666;">Fecha del sorteo:</td><td style="padding:8px;font-weight:bold;">{{ $concurso->fecha_sorteo->format('d/m/Y') }}</td></tr>
        </table>

        <p style="font-size:13px;color:#999;text-align:center;margin-top:25px;">
            Guarda este código. Necesitarás presentarlo para reclamar tu premio.
        </p>
    </div>
    <div style="background:#f9fafb;padding:15px;text-align:center;font-size:12px;color:#999;">
        Internacional Company Peru SCRL &mdash; www.cafe-peruano.com
    </div>
</div>
</body>
</html>
