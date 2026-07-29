@props(['producto'])

<div class="product-tabs my-5">
  <ul class="tabs nav nav-tabs" role="tablist">
    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-desc">Descripción</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-det">Detalles</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-com">Comentarios</button></li>
  </ul>
  <div class="tab-content p-4 bg-white rounded-bottom shadow-sm">
    <div id="tab-desc" class="tab-pane fade show active">
      {!! nl2br($producto->descripcion) !!}
    </div>
    <div id="tab-det" class="tab-pane fade">
      @php
        $detalles = json_decode($producto->detalles, true);
      @endphp
      @if (is_array($detalles) && count($detalles))
        <table class="table table-sm table-borderless mb-0">
          @foreach ($detalles as $label => $values)
            <tr>
              <th class="text-muted ps-0 pe-2" style="width: 1%; white-space: nowrap">{{ $label }}</th>
              <td>@php
                $flat = array_reduce($values, function($carry, $item) {
                    return $carry . (is_array($item) ? implode(', ', $item) : $item) . ', ';
                }, '');
                echo trim($flat, ', ');
              @endphp</td>
            </tr>
          @endforeach
        </table>
      @else
        {!! nl2br(e($producto->detalles)) !!}
      @endif
    </div>
    <div id="tab-com" class="tab-pane fade">
      @include('productos.comentarios', ['producto' => $producto])
    </div>
  </div>
</div>
