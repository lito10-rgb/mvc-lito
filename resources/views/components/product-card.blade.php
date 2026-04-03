@props(['producto'])

<div class="col-md-7">
    <h2 class="fw-bold">{{ $producto->titulo }}</h2>
    <p class="fs-5">Precio: <strong>{{ $producto->precio }} S/</strong></p>

    @if($producto->precio_oferta)
        <p class="fs-5 text-danger">Oferta: <strong>{{ $producto->precio_oferta }} S/</strong></p>
    @endif

    <!-- Acciones -->
    <div class="acciones mt-4">
        <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST" class="d-inline">
            @csrf
            <!-- <button type="submit" class="btn-custom primary mb-2">🛒 Añadir al carrito</button> -->
                        <button
              class="btn btn-primary btn-agregar-carrito"
              data-url="{{ route('carrito.agregar', $producto->id) }}"
              data-product-id="{{ $producto->id }}"
              data-qty="1"
            >  <i class="fa-solid fa-cart-plus"></i>
  Añadir al carrito
            </button>

        </form>
 <a href="{{ route('cotizacion.solicitar', $producto->id) }}"
       class="btn-custom secondary btn-icon ms-2">
      <i class="fa-solid fa-file-invoice-dollar"></i>
      Solicitar cotización
    </a>

        <!-- <a href="{{ route('cotizacion.solicitar', $producto->id) }}" class="btn-custom secondary mb-2 ms-2">  <i class="fa-solid fa-file-invoice-dollar"></i>Solicitar Cotización</a> -->

        <div class="mt-3">
            <button onclick="mostrarQr('{{ route('producto.mostrar', $producto->ruta) }}')" class="btn-custom info btn-sm"> <i class="fa-solid fa-qrcode"></i> Ver QR</button>
            <button class="btn-custom danger btn-sm"><i class="fa-solid fa-heart"></i> Favorito</button>
            <button class="btn-custom warning btn-sm"><i class="fa-solid fa-star"></i> Lista de deseos</button>
        </div>

        <div class="compartir mt-3">
          <a href="#" onclick="compartir('facebook')" class="me-2">
              <i class="fab fa-facebook fa-lg"></i>
          </a>
          <a href="#" onclick="compartir('instagram')" class="me-2">
              <i class="fab fa-instagram fa-lg"></i>
          </a>
          <a href="#" onclick="compartir('pinterest')">
              <i class="fab fa-pinterest fa-lg"></i>
          </a>
      </div>

    </div>
</div>
