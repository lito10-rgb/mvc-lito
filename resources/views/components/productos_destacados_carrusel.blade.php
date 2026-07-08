<div class="relative group">
    <div class="flex overflow-x-auto gap-6 no-scrollbar scroll-smooth snap-x snap-mandatory pb-4">
        @foreach($productos as $producto)
            <div class="flex-shrink-0 w-64 bg-white rounded-lg shadow hover:shadow-lg transition-all duration-300 snap-start">
                <a href="{{ route('producto.mostrar', $producto->ruta) }}">
                    <img src="{{ $producto->imagen_url }}" alt="{{ $producto->titulo }}" class="w-full h-40 object-cover rounded-t-lg">
                </a>
                <div class="p-4 relative group">
                    <h3 class="text-lg font-semibold text-gray-800 relative z-10">
                        <span class="hover:text-blue-600 cursor-pointer group-hover:underline">
                            {{ $producto->titulo }}
                        </span>
                        <!-- Bloque flotante en hover -->
                        <div class="absolute z-20 top-0 left-0 w-full hidden group-hover:block bg-white rounded shadow p-2 mt-8">
                            <form method="POST" action="{{ route('carrito.agregar', $producto->id) }}">
                                @csrf
                                <button type="submit" class="w-full text-left text-sm hover:text-green-600"><i class="fa-solid fa-cart-plus me-1"></i> Agregar al carrito</button>
                            </form>
                            <a href="#" class="block text-sm hover:text-blue-600 mt-1"><i class="fa-solid fa-eye me-1"></i> Vista rápida</a>
                            <form method="POST" action="{{ route('favoritos.agregar', $producto->id) }}">
                                @csrf
                                <button type="submit" class="w-full text-left text-sm hover:text-pink-600 mt-1"><i class="fa-solid fa-heart me-1"></i> Favorito</button>
                            </form>
                            <div class="flex gap-2 mt-2 text-sm">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('producto.mostrar', $producto->ruta)) }}" target="_blank" class="hover:text-blue-500"><i class="fa-brands fa-facebook me-1"></i> FB</a>
                                <a href="https://www.instagram.com/?url={{ urlencode(route('producto.mostrar', $producto->ruta)) }}" target="_blank" class="hover:text-pink-500"><i class="fa-brands fa-instagram me-1"></i> IG</a>
                                <a href="https://pinterest.com/pin/create/button/?url={{ urlencode(route('producto.mostrar', $producto->ruta)) }}" target="_blank" class="hover:text-red-500"><i class="fa-brands fa-pinterest me-1"></i> PIN</a>
                            </div>
                        </div>
                    </h3>
                    <div class="mt-2">
                        @if($producto->precioOferta)
                            <p class="text-red-600 font-bold">S/ {{ number_format($producto->precioOferta, 2) }}</p>
                            <p class="text-sm line-through text-gray-500">S/ {{ number_format($producto->precio, 2) }}</p>
                        @else
                            <p class="text-gray-800 font-bold">S/ {{ number_format($producto->precio, 2) }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Tailwind helpers -->
<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
