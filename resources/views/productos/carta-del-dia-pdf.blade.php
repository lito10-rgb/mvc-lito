<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Carta del Día</title>
<style>
    @page {
        margin: 28px 32px 40px 32px;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: "DejaVu Sans", sans-serif;
        font-size: 11px;
        color: #3b2a20;
        line-height: 1.45;
    }
    .cabecera { text-align: center; padding-bottom: 14px; border-bottom: 3px solid #6f4e37; margin-bottom: 18px; }
    .cabecera img.logo { height: 64px; margin-bottom: 8px; }
    .negocio-nombre {
        font-size: 26px;
        letter-spacing: 4px;
        color: #4b2e2a;
        text-transform: uppercase;
        font-weight: bold;
    }
    .carta-titulo {
        font-size: 15px;
        color: #a47148;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-top: 6px;
    }
    .fecha { font-size: 10px; color: #8d7b71; margin-top: 4px; }

    .seccion { margin-bottom: 22px; }
    .seccion-titulo {
        font-size: 14px;
        color: #ffffff;
        background-color: #6f4e37;
        padding: 5px 12px;
        letter-spacing: 2px;
        text-transform: uppercase;
        border-radius: 3px;
        margin-bottom: 10px;
    }
    table.item { width: 100%; border-collapse: collapse; margin-bottom: 9px; page-break-inside: avoid; }
    table.item td { vertical-align: top; }
    td.item-img { width: 78px; padding-right: 10px; }
    td.item-img img { width: 78px; height: 58px; object-fit: cover; border-radius: 3px; }
    td.item-info { padding-right: 8px; }
    .item-nombre { font-size: 12px; font-weight: bold; color: #4b2e2a; }
    .item-desc { font-size: 9.5px; color: #7a6a60; margin-top: 1px; }
    td.item-precio { width: 90px; text-align: right; white-space: nowrap; }
    .precio { font-size: 13px; font-weight: bold; color: #6f4e37; }
    .badge-desc {
        display: inline-block;
        background-color: #c1440e;
        color: #fff;
        font-size: 8.5px;
        padding: 1px 6px;
        border-radius: 8px;
        margin-top: 2px;
    }
    .consultar { font-size: 10px; font-weight: bold; color: #a47148; }

    .pie-pagina { margin-top: 14px; border-top: 2px solid #6f4e37; padding-top: 8px; text-align: center; font-size: 9px; color: #8d7b71; }
    .pie-pagina span { margin: 0 8px; }

    .sin-productos { font-style: italic; color: #a0938b; font-size: 10px; }
</style>
</head>
<body>

    <div class="cabecera">
        @if($negocio && $negocio->logo && is_file(storage_path('app/public/' . ltrim($negocio->logo, '/'))))
            <img class="logo" src="{{ storage_path('app/public/' . ltrim($negocio->logo, '/')) }}" alt="">
        @endif
        <div class="negocio-nombre">{{ $negocio?->nombre ?? 'Café Peruano' }}</div>
        <div class="carta-titulo">Carta del Día</div>
        <div class="fecha">{{ $fecha }}</div>
    </div>

    @foreach($secciones as $seccion)
        @if(count($seccion['items']) > 0)
            <div class="seccion">
                <div class="seccion-titulo">{{ $seccion['titulo'] }}</div>
                @foreach($seccion['items'] as $item)
                    <table class="item">
                        <tr>
                            <td class="item-img">
                                @if($item['imagen'])
                                    <img src="{{ $item['imagen'] }}" alt="">
                                @endif
                            </td>
                            <td class="item-info">
                                <div class="item-nombre">{{ $item['nombre'] }}</div>
                                @if($item['descripcion'])
                                    <div class="item-desc">{{ $item['descripcion'] }}</div>
                                @endif
                            </td>
                            <td class="item-precio">
                                @if($item['precio'] === null)
                                    <span class="consultar">Consultar precio</span>
                                @else
                                    <span class="precio">S/ {{ number_format($item['precio'], 2) }}</span>
                                    @if($item['descuento'] > 0)
                                        <br><span class="badge-desc">-{{ $item['descuento'] }}%</span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    </table>
                @endforeach
            </div>
        @endif
    @endforeach

    @if(collect($secciones)->sum(fn ($s) => count($s['items'])) === 0)
        <p class="sin-productos">Hoy no hay productos destacados en la carta.</p>
    @endif

    <div class="pie-pagina">
        @if($negocio?->footer_phone)<span>Tel: {{ $negocio->footer_phone }}</span>@endif
        @if($negocio?->footer_email)<span>{{ $negocio->footer_email }}</span>@endif
        @if($negocio?->footer_address)<span>{{ $negocio->footer_address }}</span>@endif
    </div>

</body>
</html>
