<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->titulo }}</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h1>{{ $post->titulo }}</h1>
    <p>Hola {{ $user->nombre }},</p>
    <p>Se ha compartido un nuevo contenido contigo:</p>
    <div style="margin: 20px 0; padding: 15px; background: #f5f5f5; border-radius: 5px;">
        {!! nl2br(e($post->cuerpo)) !!}
    </div>
    <p>
        <a href="{{ url('/post/' . $post->slug) }}"
           style="display: inline-block; padding: 10px 20px; background: #0d6efd; color: #fff; text-decoration: none; border-radius: 5px;">
            Ver en el sitio
        </a>
    </p>
    <hr>
    <p style="color: #666; font-size: 12px;">Este mensaje fue enviado desde {{ config('app.name') }}.</p>
</body>
</html>
