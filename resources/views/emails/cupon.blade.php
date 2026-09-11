<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $cupon->codigo }}</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5;">
    <div style="max-width: 560px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; border: 1px solid #e5e5e5;">
        <div style="background: {{ $negocio?->color_primary ?? '#5e422b' }}; padding: 24px; color: #fff; text-align: center;">
            <h1 style="margin: 0; font-size: 22px;">{{ $negocio?->nombre ?? negocio_actual_nombre() }}</h1>
            <p style="margin: 8px 0 0; opacity: 0.9;">Te enviamos un cupón de descuento</p>
        </div>
        <div style="padding: 24px;">
            @if($usuario)
                <p>Hola <strong>{{ $usuario->nombre }}</strong>,</p>
            @else
                <p>Hola,</p>
            @endif
            <p>Tenemos un beneficio especial para ti:</p>

            <div style="margin: 24px 0; padding: 20px; background: #f8f9fa; border: 2px dashed #28a745; border-radius: 8px; text-align: center;">
                <div style="font-size: 14px; color: #666;">Tu código de descuento</div>
                <div style="font-size: 32px; font-weight: bold; letter-spacing: 3px; color: #1a7a37;">
                    {{ $cupon->codigo }}
                </div>
                <div style="margin-top: 8px; font-size: 18px; color: #333;">
                    {{ $cupon->tipo === 'porcentaje' ? $cupon->valor . '% de descuento' : 'S/ ' . number_format($cupon->valor, 2) . ' de descuento' }}
                </div>
            </div>

            <ul style="color: #444; font-size: 14px; line-height: 1.8;">
                @if($cupon->min_compra > 0)
                    <li>Compra mínima: S/ {{ number_format($cupon->min_compra, 2) }}</li>
                @endif
                @if($cupon->fecha_fin)
                    <li>Válido hasta: {{ $cupon->fecha_fin->format('d/m/Y') }}</li>
                @endif
                <li>Usa el código en tu próxima compra en el sitio.</li>
            </ul>

            @if($producto)
                <div style="margin: 20px 0; padding: 16px; background: #f1f1f1; border-radius: 8px;">
                    <div style="font-size: 13px; color: #666; margin-bottom: 6px;">También te recomendamos:</div>
                    @if($producto->portada)
                        <img src="{{ asset('storage/' . $producto->portada) }}" alt="{{ $producto->titulo }}" style="max-width: 100%; height: auto; max-height: 120px; border-radius: 6px; margin-bottom: 8px;">
                    @endif
                    <div style="font-weight: bold; color: #333;">{{ $producto->titulo }}</div>
                    @if($producto->precio > 0)
                        <div style="color: #1a7a37; font-weight: bold;">S/ {{ number_format($producto->precio, 2) }}</div>
                    @endif
                    @if($producto->ruta)
                        <a href="{{ $sitio . '/producto/' . $producto->ruta }}" style="display: inline-block; padding: 8px 16px; background: #0d6efd; color: #fff; text-decoration: none; border-radius: 5px; margin-top: 8px;">
                            Ver producto
                        </a>
                    @endif
                </div>
            @endif

            <p>
                <a href="{{ $sitio }}" style="display: inline-block; padding: 12px 24px; background: #1a7a37; color: #fff; text-decoration: none; border-radius: 6px;">
                    Visitar tienda
                </a>
            </p>

            <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
            <p style="color: #999; font-size: 12px;">
                Este correo fue enviado desde {{ $negocio?->dominio ? 'https://' . $negocio->dominio : $sitio }}. Si no solicitaste este mensaje, ignóralo.
            </p>
        </div>
    </div>
</body>
</html>