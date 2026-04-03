{{-- resources/views/components/product-card.blade.php --}}
@props([
  'title' => 'Producto',
  'price' => null,
  'image' => null,
  'buyRoute' => '#',
  'moreRoute' => '#',
])

<div {{ $attributes->merge(['class' => 'card shadow-sm h-100']) }}>
  @if($image)
    <img src="{{ $image }}" class="card-img-top" alt="{{ $title }}">
  @endif

  <div class="card-body d-flex flex-column">
    <h2 class="h2-titulo">{{ $title }}</h2>

    @if(!is_null($price))
      <div class="precio-destacado mb-3">
        {{ is_numeric($price) ? 'S/ '.number_format($price, 2) : $price }}
      </div>
    @endif

    <div class="mt-auto d-flex flex-wrap gap-2">
      <a href="{{ $buyRoute }}" class="btn-cafe">
        <i class="fas fa-shopping-cart"></i> Comprar
      </a>
      <a href="{{ $moreRoute }}" class="btn-dorado">
        <i class="fas fa-arrow-right"></i> Ver más productos
      </a>
    </div>
  </div>
</div>
