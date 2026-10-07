Estimado/a {{ $correo['remitente'] ?: 'señor/a' }},

Le escribimos desde {{ $negocio->nombre ?: config('app.name') }}
({{ $negocio->dominio }}) porque recibimos su correo
"{{ $correo['asunto'] }}" del {{ $correo['fecha'] }}.

Nos interesa conocer en detalle sus productos y condiciones comerciales.
Le agradeceremos nos envíe:

1. Catálogo actualizado con precios.
2. Ficha técnica y especificaciones de los productos.
3. Condiciones comerciales: precios mínimos, plazos de entrega, formas de pago y envío.
4. Imágenes o fotos de los productos.

Quedamos atentos a su respuesta.

{{ $negocio->nombre ?: config('app.name') }} · Equipo de Compras
@if($negocio->dominio)
Web: {{ $negocio->dominio }}
@endif