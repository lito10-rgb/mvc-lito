@props(['producto'])

@php
    // Clasificación amigable según el tipo de producto
    $esVirtual   = in_array($producto->tipo, ['virtual', 'digital', 'no_fisico', 'servicio']);
    $esFisico    = in_array($producto->tipo, ['fisico', 'maquinaria', 'empaque']);
    $etiquetaTipo = match ($producto->tipo) {
        'fisico'     => 'Producto físico',
        'maquinaria' => 'Maquinaria / Equipo',
        'empaque'    => 'Empaque',
        'servicio'   => 'Servicio',
        'virtual'    => 'Producto digital / Virtual',
        'no_fisico'  => 'Producto digital / Virtual',
        default      => 'Producto',
    };
@endphp

<div class="product-tabs my-5">
  <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
    <span class="badge bg-primary-subtle text-primary px-3 py-2 fs-6">
      <i class="fa-solid fa-tags me-1"></i>{{ $etiquetaTipo }}
    </span>
    <span class="badge bg-light text-body px-3 py-2 fs-6 border">
      <i class="fa-solid fa-eye text-primary me-1"></i>{{ number_format($producto->vistas) }} vistas
    </span>
    <span class="badge bg-light text-body px-3 py-2 fs-6 border">
      <i class="fa-solid fa-bag-shopping text-success me-1"></i>{{ number_format($producto->ventas) }} ventas
    </span>
  </div>

  <ul class="tabs nav nav-tabs" role="tablist">
    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-desc">Descripción</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-det">Detalles</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-com">Comentarios</button></li>
  </ul>
  <div class="tab-content p-4 bg-white rounded-bottom shadow-sm">
    <div id="tab-desc" class="tab-pane fade show active">
      {!! nl2br(html_entity_decode($producto->descripcion, ENT_QUOTES, 'UTF-8')) !!}
    </div>
    <div id="tab-det" class="tab-pane fade">
      @php
        $detallesRaw = $producto->detalles ?? '';
        $detallesRaw = html_entity_decode($detallesRaw, ENT_QUOTES, 'UTF-8');
        $detalles = json_decode($detallesRaw, true);
        if (!is_array($detalles)) {
            $detallesRaw = stripslashes($detallesRaw);
            $detalles = json_decode($detallesRaw, true);
        }
      @endphp

      {{-- Datos clave según el tipo de producto --}}
      @if($producto->tipo === 'servicio' && $producto->precio == 0)
        <div class="alert alert-info small">
          <i class="fa-solid fa-circle-info me-1"></i> Solicita una cotización para conocer precios, tiempos y condiciones.
        </div>
      @endif

      @if($esFisico)
        <div class="row g-3 mb-4">
          <div class="col-6 col-md-4 col-lg-3">
            <div class="border rounded p-3 h-100 text-center bg-light">
              <i class="fa-solid fa-cubes fs-3 text-primary mb-2 d-block"></i>
              <div class="small text-muted">Disponibilidad</div>
              <div class="fw-bold">{{ $producto->stock > 0 ? number_format($producto->stock) : 'Por encargo' }}</div>
            </div>
          </div>
          <div class="col-6 col-md-4 col-lg-3">
            <div class="border rounded p-3 h-100 text-center bg-light">
              <i class="fa-solid fa-truck-fast fs-3 text-success mb-2 d-block"></i>
              <div class="small text-muted">Entrega</div>
              <div class="fw-bold">{{ $producto->entrega == 0 ? 'Inmediata / Coordinar' : $producto->entrega . ' días' }}</div>
            </div>
          </div>
          <div class="col-6 col-md-4 col-lg-3">
            <div class="border rounded p-3 h-100 text-center bg-light">
              <i class="fa-solid fa-box fs-3 text-info mb-2 d-block"></i>
              <div class="small text-muted">Peso</div>
              <div class="fw-bold">{{ $producto->peso ? $producto->peso . ' kg' : 'Consultar' }}</div>
            </div>
          </div>
          <div class="col-6 col-md-4 col-lg-3">
            <div class="border rounded p-3 h-100 text-center bg-light">
              <i class="fa-solid fa-truck fs-3 text-warning mb-2 d-block"></i>
              <div class="small text-muted">Envío</div>
              <div class="fw-bold">{{ $producto->envio_gratis ? 'Gratuito' : $producto->costo_envio }}</div>
            </div>
          </div>
          @if($producto->marca)
            <div class="col-6 col-md-4 col-lg-3">
              <div class="border rounded p-3 h-100 text-center bg-light">
                <i class="fa-solid fa-registered fs-3 text-secondary mb-2 d-block"></i>
                <div class="small text-muted">Marca</div>
                <div class="fw-bold">{{ $producto->marca->nombre ?? '' }}</div>
              </div>
            </div>
          @endif
        </div>
      @elseif($esVirtual)
        <div class="row g-3 mb-4">
          <div class="col-6 col-md-4 col-lg-3">
            <div class="border rounded p-3 h-100 text-center bg-light">
              <i class="fa-solid fa-download fs-3 text-success mb-2 d-block"></i>
              <div class="small text-muted">Entrega</div>
              <div class="fw-bold">Descarga / Acceso digital</div>
            </div>
          </div>
          <div class="col-6 col-md-4 col-lg-3">
            <div class="border rounded p-3 h-100 text-center bg-light">
              <i class="fa-solid fa-headset fs-3 text-primary mb-2 d-block"></i>
              <div class="small text-muted">Soporte / Coordinación</div>
              <div class="fw-bold">{{ $producto->entrega == 0 ? 'Inmediato' : $producto->entrega . ' días' }}</div>
            </div>
          </div>
        </div>
      @endif

      {{-- Detalles estructurados (JSON) --}}
      @if (is_array($detalles) && count($detalles))
        <h6 class="fw-bold mb-3"><i class="fa-solid fa-list-check me-1"></i>Especificaciones</h6>
        <div class="table-responsive">
          <table class="table table-sm table-borderless align-middle mb-0">
            @foreach ($detalles as $label => $values)
              <tr>
                <th class="text-muted ps-0 pe-3" style="width: 40%; white-space: normal">{{ $label }}</th>
                <td class="fw-semibold">
                  @php
                    $flat = is_array($values)
                        ? array_reduce($values, function($carry, $item) {
                            return $carry . (is_array($item) ? implode(', ', $item) : $item) . ', ';
                        }, '')
                        : $values;
                    echo trim($flat, ', ');
                  @endphp
                </td>
              </tr>
            @endforeach
          </table>
        </div>
      @elseif(trim($detallesRaw))
        <p class="text-muted">Detalles: {{ $detallesRaw }}</p>
      @else
        <p class="text-muted">No hay detalles adicionales para este producto.</p>
      @endif
    </div>
    <div id="tab-com" class="tab-pane fade">
      @include('productos.comentarios', ['producto' => $producto])
    </div>
  </div>
</div>
