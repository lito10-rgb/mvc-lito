<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family:Arial,sans-serif;padding:20px;">
    <h3 style="color:#B20000;">Recibimos tu solicitud de cotización</h3>
    <p>Hola <strong>{{ $cliente }}</strong>, gracias por contactarnos.</p>
    <p>Hemos recibido tu solicitud para cotizar el siguiente producto en <strong>{{ $negocio }}</strong>:</p>

    <table style="border-collapse:collapse;width:100%;max-width:600px;">
        <tr><td style="padding:6px;border:1px solid #ddd;width:160px;"><strong>Producto</strong></td>
            <td style="padding:6px;border:1px solid #ddd;">{{ $producto }}</td></tr>
        <tr><td style="padding:6px;border:1px solid #ddd;"><strong>Cantidad</strong></td>
            <td style="padding:6px;border:1px solid #ddd;">{{ $cantidad }}</td></tr>
        <tr><td style="padding:6px;border:1px solid #ddd;"><strong>Detalle</strong></td>
            <td style="padding:6px;border:1px solid #ddd;">{{ $descripcion }}</td></tr>
    </table>

    <p>Uno de nuestros asesores te contactará pronto con el detalle de tu cotización.</p>

    <hr style="margin-top:20px;">
    <p style="color:#666;font-size:12px;">Este es un correo automático, por favor no respondas a este mensaje.</p>
</body>
</html>
