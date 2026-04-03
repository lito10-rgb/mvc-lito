<!-- resources/views/componentes/productos_destacados.blade.php -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
  @foreach ($productos as $producto)
    <div class="group relative border rounded-2xl p-4 shadow hover:shadow-lg transition duration-300">
      <!-- Imagen -->
      <a href="{{ url('/producto/' . $producto->slug) }}">
        <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->titulo }}" class="w-full h-48 object-cover rounded-xl mb-4">
      </a>

      <!-- Título con opciones en hover -->
      <div class="relative">
        <h3 class="text-lg font-semibold text-gray-800 group-hover:text-blue-600">
          <a href="{{ url('/producto/' . $producto->slug) }}">{{ $producto->titulo }}</a>
        </h3>

        <div class="absolute top-full mt-1 left-0 z-10 hidden group-hover:flex gap-2 bg-white border rounded-xl shadow p-3 w-max">
          <form method="POST" action="{{ route('carrito.agregar', $producto->id) }}">
            @csrf
            <button type="submit" class="text-sm text-gray-700 hover:text-blue-600">🛒</button>
          </form>

          <button onclick="vistaRapida('{{ route('producto.vistaRapida', $producto->id) }}')" class="text-sm text-gray-700 hover:text-green-600">👁 Vista</button>

          <form method="POST" action="{{ route('favoritos.agregar', $producto->id) }}">
            @csrf
            <button type="submit" class="text-sm text-gray-700 hover:text-red-600">❤️</button>
          </form>

          <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/producto/' . $producto->slug)) }}" target="_blank" class="text-sm hover:text-blue-600">Fb</a>
          <a href="https://www.instagram.com/" target="_blank" class="text-sm hover:text-pink-600">Ig</a>
          <a href="https://pinterest.com/pin/create/button/?url={{ urlencode(url('/producto/' . $producto->slug)) }}" target="_blank" class="text-sm hover:text-red-600">Pt</a>
        </div>
      </div>

      <!-- Precio -->
      <div class="mt-2">
        @if ($producto->precio_oferta && $producto->precio_oferta < $producto->precio)
          <span class="text-green-600 font-bold">S/ {{ number_format($producto->precio_oferta, 2) }}</span>
          <span class="ml-2 line-through text-gray-400">S/ {{ number_format($producto->precio, 2) }}</span>
        @else
          <span class="text-gray-900 font-bold">S/ {{ number_format($producto->precio, 2) }}</span>
        @endif
      </div>
    </div>
  @endforeach
</div>

<script>
function vistaRapida(url) {
  // Aquí puedes usar un modal JS o AlpineJS para abrir una vista rápida
  alert('Cargar vista rápida desde: ' + url);
}
</script>
