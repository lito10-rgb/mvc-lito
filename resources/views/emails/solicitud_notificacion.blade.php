<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family:Arial,sans-serif;padding:20px;">
    <h3 style="color:#B20000;">Nueva solicitud de cotización</h3>
    <p>Se ha recibido una nueva solicitud en <strong>{{ $negocio }}</strong>.</p>

    <table style="border-collapse:collapse;width:100%;max-width:600px;">
        <tr><td style="padding:6px;border:1px solid #ddd;width:160px;"><strong>Cliente</strong></td>
            <td style="padding:6px;border:1px solid #ddd;">{{ $cliente }}</td></tr>
        <tr><td style="padding:6px;border:1px solid #ddd;"><strong>Correo</strong></td>
            <td style="padding:6px;border:1px solid #ddd;">{{ $correo }}</td></tr>
        <tr><td style="padding:6px;border:1px solid #ddd;"><strong>Teléfono</strong></td>
            <td style="padding:6px;border:1px solid #ddd;">{{ $telefono ?: '—' }}</td></tr>
        <tr><td style="padding:6px;border:1px solid #ddd;"><strong>Producto</strong></td>
            <td style="padding:6px;border:1px solid #ddd;">{{ $producto }}</td></tr>
        <tr><td style="padding:6px;border:1px solid #ddd;"><strong>Cantidad</strong></td>
            <td style="padding:6px;border:1px solid #ddd;">{{ $cantidad }}</td></tr>
        <tr><td style="padding:6px;border:1px solid #ddd;"><strong>Precio unitario</strong></td>
            <td style="padding:6px;border:1px solid #ddd;">S/ {{ number_format($precio, 2) }}</td></tr>
        <tr><td style="padding:6px;border:1px solid #ddd;"><strong>Subtotal</strong></td>
            <td style="padding:6px;border:1px solid #ddd;">S/ {{ number_format($subtotal, 2) }}</td></tr>
        <tr><td style="padding:6px;border:1px solid #ddd;"><strong>Detalle</strong></td>
            <td style="padding:6px;border:1px solid #ddd;">{{ $descripcion }}</td></tr>
    </table>

    <hr style="margin-top:20px;">
    <p style="color:#666;font-size:12px;">Por favor contactar al cliente para atender su solicitud.</p>
</body>
</html>
