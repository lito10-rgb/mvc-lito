<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo }} - {{ $negNombre }}</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; color: #333; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #c5a200; }
        .header h1 { font-size: 26px; color: #103067; margin-bottom: 5px; }
        .header p { font-size: 14px; color: #666; }
        .empresa-text { font-size: 11px; color: #555; line-height: 1.5; white-space: pre-line; margin: 0 auto; max-width: 500px; }
        .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .card { border: 1px solid #ddd; border-radius: 8px; overflow: hidden; page-break-inside: avoid; break-inside: avoid; }
        .card img { width: 100%; height: 200px; object-fit: contain; background: #f8f5f2; display: block; }
        .card-body { padding: 12px; }
        .card-body h3 { font-size: 13px; color: #103067; margin-bottom: 6px; line-height: 1.3; }
        .card-body .marca { font-size: 11px; color: #c5a200; font-weight: bold; margin-bottom: 4px; }
        .card-body .precio { font-size: 18px; font-weight: bold; color: #103067; }
        .card-body .precio-oferta { font-size: 18px; font-weight: bold; color: #c00; }
        .card-body .precio-tachado { font-size: 13px; text-decoration: line-through; color: #888; margin-left: 8px; }
        .card-body .descripcion { font-size: 11px; color: #555; margin-top: 6px; line-height: 1.4; }
        .card-body .detalle { font-size: 11px; color: #555; margin-top: 4px; line-height: 1.4; }
        .no-products { text-align: center; padding: 60px; color: #999; font-size: 18px; }
        .footer { text-align: center; margin-top: 30px; padding-top: 15px; border-top: 1px solid #ddd; font-size: 12px; color: #999; }
        @media print {
            body { padding: 10px; }
            .grid { grid-template-columns: repeat(3, 1fr); gap: 12px; }
            .card img { height: 160px; }
            .header { margin-bottom: 15px; }
            .header h1 { font-size: 22px; }
        }
        @page { margin: 1cm; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $titulo }}</h1>
        <p>{{ $negNombre }} — Catálogo de productos · {{ now()->format('d/m/Y') }}</p>
        @if ($negWeb)
            <p style="font-size:13px;color:#c5a200;">{{ $negWeb }}</p>
        @endif
    </div>

    @if ($productos->isEmpty())
        <div class="no-products">No hay productos disponibles para esta selección.</div>
    @else
        <div class="grid">
            @foreach ($productos as $p)
                <div class="card">
                    @if ($p->portada)
                        <img src="{{ asset('storage/' . $p->portada) }}" alt="{{ $p->titulo }}" loading="lazy">
                    @else
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='300'%3E%3Crect fill='%23f8f5f2' width='400' height='300'/%3E%3Ctext x='200' y='150' text-anchor='middle' fill='%23ccc' font-size='16'%3ESin imagen%3C/text%3E%3C/svg%3E" alt="Sin imagen">
                    @endif
                    <div class="card-body">
                        @if ($p->marca)
                            <div class="marca">{{ $p->marca->nombre }}</div>
                        @endif
                        <h3>{{ $p->titulo }}</h3>
                        @if (!$sinPrecio)
                            @if (!empty($p->precioOferta) || $p->enOferta)
                                <div>
                                    <span class="precio-oferta">S/ {{ number_format($p->precioFinal, 2) }}</span>
                                    <span class="precio-tachado">S/ {{ number_format($p->precio, 2) }}</span>
                                </div>
                            @else
                                <div class="precio">S/ {{ number_format($p->precio, 2) }}</div>
                            @endif
                        @endif
                        @php
                            $desc = strip_tags($p->descripcion);
                            $desc = mb_strlen($desc) > 120 ? mb_substr($desc, 0, 120) . '...' : $desc;
                        @endphp
                        @if ($desc)
                            <div class="descripcion">{{ $desc }}</div>
                        @endif
                        @if ($p->detalles)
                            @php $det = json_decode($p->detalles, true); @endphp
                            @if (is_array($det) && count($det))
                                <div class="detalle">
                                    @foreach ($det as $label => $values)
                                        <div><strong>{{ $label }}:</strong> {{ is_array($values) ? implode(', ', $values) : $values }}</div>
                                    @endforeach
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="footer">
        @if ($negEmpresa)
            <div class="empresa-text">{{ $negEmpresa }}</div>
            <br>
        @endif
        Generado el {{ now()->format('d/m/Y H:i') }} — {{ $negNombre }}
    </div>

    <script>
        window.onload = function () { window.print(); };
    </script>
</body>
</html>
