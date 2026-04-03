@props(['producto'])

<div class="product-tabs my-5">
  <ul class="tabs nav nav-tabs" role="tablist">
    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-desc">Descripción</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-det">Detalles</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-com">Comentarios</button></li>
  </ul>
  <div class="tab-content p-4 bg-white rounded-bottom shadow-sm">
    <div id="tab-desc" class="tab-pane fade show active">
      {!! nl2br(e($producto->descripcion)) !!}
    </div>
    <div id="tab-det" class="tab-pane fade">
      {!! $producto->detalles !!}
    </div>
    <div id="tab-com" class="tab-pane fade">
      @include('productos.comentarios', ['producto' => $producto])
    </div>
  </div>
</div>
