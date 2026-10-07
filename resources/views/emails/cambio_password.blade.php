<!DOCTYPE html>
<html lang="es">
<head><meta charset="utf-8"></head>
<body style="font-family:Arial,sans-serif;padding:20px;">
    <h3 style="color:#c0392b;margin-bottom:12px;">Cambie su contraseña de acceso</h3>
    <p>Estimado/a <strong>{{ $nombre }}</strong>:</p>
    <p>Para acceder a su cuenta en nuestro portal utilice la siguiente contraseña <strong>temporal</strong>:</p>
    <p style="font-size:18px;font-weight:bold;background:#f4f6f7;padding:10px;border:1px dashed #95a5a6;text-align:center;">{{ $password }}</p>
    <p>Por su seguridad, <strong>le pedimos que la cambie por una contraseña propia</strong> al iniciar sesión:</p>
    <ul>
        <li>Ingrese aquí: <a href="{{ $login_url }}">{{ $login_url }}</a></li>
        <li>Luego cambie su contraseña en su perfil: <a href="{{ $perfil_url }}">{{ $perfil_url }}</a></li>
    </ul>
    <p style="color:#7f8c8d;font-size:12px;">Si usted no solicitó este aviso, puede ignorar este correo y cambiar su contraseña por precaución.</p>
    <hr>
    <p style="color:#95a5a6;font-size:12px;">Este es un correo automático del sistema; no responda a este mensaje.</p>
</body>
</html>