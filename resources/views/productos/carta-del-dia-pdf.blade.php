<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Carta del Día</title>
<style>
    @page {
        margin: 24px 30px 38px 30px;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: "DejaVu Sans", sans-serif;
        font-size: 11px;
        color: #3b2a20;
        line-height: 1.5;
        padding: 0 22px;
    }

    /* ── Cabecera ── */
    .cabecera {
        text-align: center;
        padding: 18px 0 16px;
        border-bottom: 4px solid #6f4e37;
        margin-bottom: 20px;
        background: linear-gradient(to bottom, #f7f1ea 0%, #ffffff 100%);
        border-radius: 8px 8px 0 0;
    }
    .cabecera img.logo {
        height: 100px;
        margin-bottom: 10px;
        object-fit: contain;
    }
    .negocio-nombre {
        font-size: 30px;
        letter-spacing: 5px;
        color: #4b2e2a;
        text-transform: uppercase;
        font-weight: bold;
    }
    .carta-titulo {
        font-size: 17px;
        color: #a47148;
        letter-spacing: 3px;
        text-transform: uppercase;
        font-weight: bold;
        margin-top: 6px;
    }
    .fecha {
        font-size: 10px;
        color: #8d7b71;
        margin-top: 5px;
        letter-spacing: 1px;
    }

    /* ── Secciones ── */
    .seccion { margin-bottom: 24px; }
    .seccion-titulo {
        font-size: 14px;
        color: #ffffff;
        background-color: #6f4e37;
        padding: 7px 14px;
        letter-spacing: 3px;
        text-transform: uppercase;
        border-radius: 4px;
        margin-bottom: 12px;
        font-weight: bold;
        box-shadow: 0 2px 4px rgba(0,0,0,0.15);
    }

    table.item {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
        page-break-inside: avoid;
        border-bottom: 1px solid #f0e6dd;
        padding-bottom: 6px;
    }
    table.item td { vertical-align: middle; }
    td.item-img { width: 92px; padding-right: 12px; }
    td.item-img img {
        width: 92px;
        height: 72px;
        object-fit: cover;
        border-radius: 6px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.18);
    }
    td.item-info { padding-right: 10px; }
    .item-nombre {
        font-size: 12.5px;
        font-weight: bold;
        color: #4b2e2a;
        letter-spacing: 0.3px;
    }
    .item-desc {
        font-size: 9.5px;
        color: #7a6a60;
        margin-top: 2px;
        line-height: 1.4;
    }
    td.item-precio { width: 100px; text-align: right; white-space: nowrap; }
    .precio {
        font-size: 14px;
        font-weight: bold;
        color: #6f4e37;
        background-color: #f7f1ea;
        padding: 3px 8px;
        border-radius: 4px;
        display: inline-block;
    }
    .badge-desc {
        display: inline-block;
        background-color: #c1440e;
        color: #fff;
        font-size: 8.5px;
        font-weight: bold;
        padding: 1px 7px;
        border-radius: 8px;
        margin-top: 3px;
    }
    .consultar { font-size: 10px; font-weight: bold; color: #a47148; }

    /* ── Pie de página ── */
    .pie-pagina {
        margin-top: 16px;
        border-top: 3px solid #6f4e37;
        padding-top: 10px;
        text-align: center;
        font-size: 9px;
        color: #8d7b71;
        letter-spacing: 0.5px;
    }
    .pie-pagina span { margin: 0 10px; }

    .sin-productos { font-style: italic; color: #a0938b; font-size: 10px; text-align: center; padding: 20px 0; }
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
