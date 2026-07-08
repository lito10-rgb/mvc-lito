@props(['producto'])

<div class="col-md-7">
    <h2 class="fw-bold">{{ $producto->titulo }}</h2>
    <p class="fs-5">Precio: <strong>S/ {{ number_format($producto->precio, 2) }}</strong></p>

    @if($producto->precioOferta)
        <p class="fs-5 text-danger">Oferta: <strong>S/ {{ number_format($producto->precioOferta, 2) }}</strong></p>
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
            <button onclick="mostrarQr('{{ route('producto.mostrar', $producto->ruta) }}')" class="btn-custom info btn-sm"><i class="fa-solid fa-qrcode"></i> Ver QR</button>

            <form action="{{ route('favoritos.agregar', $producto->id) }}" method="POST" style="display:inline">
                @csrf
                <button type="submit" class="btn-custom danger btn-sm"><i class="fa-solid fa-heart"></i> Favorito</button>
            </form>

            <form action="{{ route('favoritos.agregar', $producto->id) }}" method="POST" style="display:inline">
                @csrf
                <button type="submit" class="btn-custom warning btn-sm"><i class="fa-solid fa-star"></i> Lista de deseos</button>
            </form>
        </div>

        <!-- QR Modal -->
        <div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa-solid fa-qrcode"></i> Código QR</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="qrImage" src="" alt="QR" class="img-fluid" style="max-width: 300px;">
                        <p class="mt-2 text-break small" id="qrUrl"></p>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
        function mostrarQr(url) {
            document.getElementById('qrImage').src = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' + encodeURIComponent(url);
            document.getElementById('qrUrl').textContent = url;
            var modal = new bootstrap.Modal(document.getElementById('qrModal'));
            modal.show();
        }
        </script>
        @endpush

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
