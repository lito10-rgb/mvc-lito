@extends('layouts.app')

@section('title', 'Checkout - Confirmar pedido')
@section('description', 'Resumen del pedido y selección de método de pago')
@section('keywords', 'checkout,pago,mercadopago,paypal')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-3">Confirmar pedido</h1>

            {{-- Mensajes --}}
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
        </div>
    </div>

    @if(empty($carrito) || count($carrito) === 0)
        <div class="alert alert-warning">
            Tu carrito está vacío. <a href="{{ route('productos.index') }}">Ver productos</a>
        </div>
    @else
        <div class="row">
            {{-- Resumen (izq) --}}
            <div class="col-lg-8">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Productos</h5>

                        <div class="list-group list-group-flush">
                            @foreach($carrito as $id => $item)
                                <div class="list-group-item d-flex align-items-center">
                                    <div style="width:80px; height:80px; flex: 0 0 80px; margin-right:1rem;">
                                        @if(!empty($item['imagen']))
                                            <img src="{{ asset('storage/' . $item['imagen']) }}" alt="{{ $item['titulo'] }}"
                                                 class="img-fluid rounded" style="width:100%; height:100%; object-fit:cover;">
                                        @else
                                            <img src="{{ asset('images/no-image.png') }}" alt="Sin imagen" class="img-fluid rounded"
                                                 style="width:100%; height:100%; object-fit:cover;">
                                        @endif
                                    </div>

                                    <div class="flex-grow-1">
                                        <a href="{{ route('producto.mostrar', $item['ruta'] ?? '#') }}" class="fw-bold text-decoration-none">
                                            {{ $item['titulo'] }}
                                        </a>
                                        <div class="small text-muted">
                                            Cantidad: {{ $item['cantidad'] }} &middot; Precio unitario: S/ {{ number_format($item['precio'], 2) }}
                                            @if(!empty($item['envio_gratis']))
                                                <span class="badge bg-success ms-1"><i class="fa-solid fa-truck-fast"></i> Envío gratis</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="text-end ms-3">
                                        <div class="fw-bold">S/ {{ number_format($item['precio'] * $item['cantidad'], 2) }}</div>

                                        <form action="{{ route('carrito.eliminar', $id) }}" method="POST" class="mt-2">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash-can"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            @if($mixto)
                                <h5>Dirección de envío y correo</h5>
                                <div class="row g-2">
                                    <div class="col-12">
                                        <input type="email" name="email_envio" class="form-control form-control-sm"
                                               placeholder="Correo electrónico (para productos digitales)"
                                               value="{{ old('email_envio', auth()->user()->email ?? '') }}" required>
                                    </div>
                                    <div class="col-12">
                                        <input type="text" name="direccion" class="form-control form-control-sm" placeholder="Dirección (para productos físicos)" value="{{ old('direccion', auth()->user()->direccion ?? '') }}" required>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="ciudad" class="form-control form-control-sm" placeholder="Ciudad" value="{{ old('ciudad') }}">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="departamento" class="form-control form-control-sm" placeholder="Estado / Provincia / Región" value="{{ old('departamento') }}">
                                    </div>
                                    <div class="col-12">
                                        <input type="text" name="telefono" class="form-control form-control-sm" placeholder="Teléfono" value="{{ old('telefono', auth()->user()->telefono ?? '') }}" required>
                                    </div>
                                </div>
                            @elseif($soloNoFisico)
                                <h5>Correo para recibir tu pedido</h5>
                                <div class="row g-2">
                                    <div class="col-12">
                                        <input type="email" name="email_envio" class="form-control form-control-sm"
                                               placeholder="Correo electrónico"
                                               value="{{ old('email_envio', auth()->user()->email ?? '') }}" required>
                                        <small class="text-muted">Te enviaremos el enlace de descarga o el archivo a este correo.</small>
                                    </div>
                                </div>
                            @else
                                <h5>Dirección de envío</h5>
                                <div class="row g-2">
                                    <div class="col-12">
                                        <input type="text" name="direccion" class="form-control form-control-sm" placeholder="Dirección" value="{{ old('direccion', auth()->user()->direccion ?? '') }}" required>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="ciudad" class="form-control form-control-sm" placeholder="Ciudad" value="{{ old('ciudad') }}">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="departamento" class="form-control form-control-sm" placeholder="Estado / Provincia / Región" value="{{ old('departamento') }}">
                                    </div>
                                    <div class="col-12">
                                        <input type="text" name="telefono" class="form-control form-control-sm" placeholder="Teléfono" value="{{ old('telefono', auth()->user()->telefono ?? '') }}" required>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            {{-- Pago (der) --}}
            <div class="col-lg-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Resumen</h5>

                        <div class="d-flex justify-content-between">
                            <div>Subtotal</div>
                            <div id="subtotal-valor">S/ {{ number_format($subtotal, 2) }}</div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div>Envío</div>
                            <div id="envio-valor">S/ {{ number_format($envio, 2) }}</div>
                        </div>
                        @if(session('cupon_descuento', 0) > 0)
                        <div class="d-flex justify-content-between text-success" id="fila-descuento">
                            <div>
                                Cupón <strong>{{ session('cupon_codigo', '') }}</strong>
                                <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-1" id="btn-quitar-cupon" title="Quitar cupón">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div id="descuento-valor">-S/ {{ number_format(session('cupon_descuento'), 2) }}</div>
                        </div>
                        @else
                        <div id="fila-descuento" style="display:none;">
                            <div class="d-flex justify-content-between text-success">
                                <div>
                                    Cupón <strong id="cupon-codigo-text"></strong>
                                    <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-1" id="btn-quitar-cupon" title="Quitar cupón">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div id="descuento-valor"></div>
                            </div>
                        </div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between fs-5 fw-bold">
                            <div>Total</div>
                            <div id="total-valor">S/ {{ number_format($total, 2) }}</div>
                        </div>

                        {{-- Cupón de descuento --}}
                        <div class="mt-3 mb-2">
                            <label class="form-label small fw-bold"><i class="fas fa-ticket me-1"></i>¿Tienes un cupón?</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="input-cupon" placeholder="Código" value="{{ session('cupon_codigo', '') }}" maxlength="50" style="text-transform:uppercase;">
                                <button class="btn btn-outline-success" type="button" id="btn-aplicar-cupon">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                            <div id="cupon-msg" class="small mt-1" style="display:none;"></div>
                        </div>

                        {{-- Tipo de envío --}}
                        @if(!$soloNoFisico && $tipos->isNotEmpty())
                        <hr>
                        <h6>Tipo de envío</h6>
                        <div id="tipos-envio">
                            @foreach($tipos as $tipo)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_envio_id" id="tipo-{{ $tipo->id }}"
                                           value="{{ $tipo->id }}"
                                           {{ ($tipoSeleccionado == $tipo->id) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tipo-{{ $tipo->id }}">
                                        {{ $tipo->nombre }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div id="envio-loading" class="text-muted small" style="display:none;">
                            <i class="fas fa-spinner fa-spin"></i> Actualizando envío…
                        </div>
                        @endif

                        <hr>
                        <div class="d-flex justify-content-between fs-5 fw-bold">
                            <div>Total</div>
                            <div>S/ {{ number_format($total, 2) }}</div>
                        </div>

                        <hr>

                        {{-- Formulario para elegir método de pago --}}
                        <form id="checkoutForm" action="{{ route('checkout.pay') }}" method="POST" novalidate>
    @csrf
    <input type="hidden" name="tipo_envio_id" id="tipo_envio_id" value="{{ $tipoSeleccionado ?? $tipos->first()?->id }}">

    <div class="mb-3">
        <label for="metodo" class="form-label">Selecciona método de pago</label>
        <select name="metodo" id="metodo" class="form-select" required>
            @if(app()->environment('local'))
                <option value="simulado">Simulado (pruebas)</option>
            @endif
            <option value="mercadopago">Mercado Pago</option>
            <option value="paypal">PayPal</option>
        </select>
    </div>

    <button id="paySubmit" type="submit" class="btn btn-success w-100 mb-2">
        <i class="fas fa-credit-card me-2"></i> Pagar ahora
    </button>

    <div id="paySpinner" class="text-center mt-2" style="display:none;">
        <div class="spinner-border" role="status" aria-hidden="true"></div>
        <div class="small mt-2">Redirigiendo a la pasarela de pago…</div>
    </div>

    {{-- Alternativa: botones directos por pasarela --}}
    <div class="d-grid gap-2 mt-3">
        <button type="button" id="btnMP" class="btn btn-outline-theme-accent">
            <i class="fab fa-mercado-pago me-2"></i> Pagar con Mercado Pago
        </button>

        <button type="button" id="btnPP" class="btn btn-outline-primary">
            <i class="fab fa-paypal me-2"></i> Pagar con PayPal
        </button>
    </div>
</form>

{{-- Script para mejorar UX --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('checkoutForm');
    const spinner = document.getElementById('paySpinner');
    const submit = document.getElementById('paySubmit');
    const metodo = document.getElementById('metodo');

    // Botones directos
    document.getElementById('btnMP').addEventListener('click', () => {
        metodo.value = 'mercadopago';
        form.submit();
    });
    document.getElementById('btnPP').addEventListener('click', () => {
        metodo.value = 'paypal';
        form.submit();
    });

    // Mostrar spinner/desactivar al enviar
    form.addEventListener('submit', () => {
        submit.disabled = true;
        spinner.style.display = 'block';
        // deshabilita botones alternativos
        document.getElementById('btnMP').disabled = true;
        document.getElementById('btnPP').disabled = true;
    });

    // Actualizar envío al cambiar tipo de envío
    const tiposRadios = document.querySelectorAll('input[name="tipo_envio_id"]');
    const envioLoading = document.getElementById('envio-loading');
    tiposRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            const tipoId = this.value;
            document.getElementById('tipo_envio_id').value = tipoId;
            if (envioLoading) envioLoading.style.display = 'block';
            fetch('{{ route("checkout.envio") }}', {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                body: JSON.stringify({tipo_envio_id: tipoId})
            })
            .then(r => r.json())
            .then(data => {
                document.getElementById('envio-valor').textContent = 'S/ ' + data.envio;
                document.getElementById('total-valor').textContent = 'S/ ' + data.total;
            })
            .catch(() => {})
            .finally(() => { if (envioLoading) envioLoading.style.display = 'none'; });
        });
    });

    // Cupón de descuento
    var subtotal = {{ $subtotal }};
    var cuponCodigo = document.getElementById('input-cupon');
    var btnAplicar = document.getElementById('btn-aplicar-cupon');
    var btnQuitar = document.getElementById('btn-quitar-cupon');
    var cuponMsg = document.getElementById('cupon-msg');
    var filaDescuento = document.getElementById('fila-descuento');

    function recalcularTotal(descuento) {
        var envio = parseFloat(document.getElementById('envio-valor').textContent.replace('S/ ', '')) || 0;
        var total = subtotal + envio - descuento;
        if (total < 0) total = 0;
        document.getElementById('total-valor').textContent = 'S/ ' + total.toFixed(2);
    }

    btnAplicar.addEventListener('click', function () {
        var codigo = cuponCodigo.value.trim();
        if (!codigo) return;

        btnAplicar.disabled = true;
        btnAplicar.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        fetch('{{ route("checkout.cupon") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ codigo: codigo, subtotal: subtotal })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            btnAplicar.disabled = false;
            btnAplicar.innerHTML = '<i class="fas fa-check"></i>';

            if (data.valido) {
                cuponMsg.className = 'small mt-1 text-success';
                cuponMsg.textContent = data.mensaje;
                cuponMsg.style.display = 'block';

                document.getElementById('cupon-codigo-text').textContent = data.codigo;
                document.getElementById('descuento-valor').textContent = '-S/ ' + data.descuento.toFixed(2);
                filaDescuento.style.display = 'block';

                recalcularTotal(data.descuento);
            } else {
                cuponMsg.className = 'small mt-1 text-danger';
                cuponMsg.textContent = data.mensaje;
                cuponMsg.style.display = 'block';
            }
        })
        .catch(function () {
            btnAplicar.disabled = false;
            btnAplicar.innerHTML = '<i class="fas fa-check"></i>';
            cuponMsg.className = 'small mt-1 text-danger';
            cuponMsg.textContent = 'Error al validar cupón';
            cuponMsg.style.display = 'block';
        });
    });

    cuponCodigo.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); btnAplicar.click(); }
    });

    if (btnQuitar) {
        btnQuitar.addEventListener('click', function () {
            fetch('{{ route("checkout.cupon-quitar") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(function (r) { return r.json(); })
            .then(function () {
                filaDescuento.style.display = 'none';
                cuponCodigo.value = '';
                cuponMsg.style.display = 'none';
                recalcularTotal(0);
            });
        });
    }
});
</script>
@endpush


                        <small class="text-muted d-block mt-3">
                            Debes estar <a href="{{ route('login') }}">logueado</a> para completar la compra.
                        </small>
                    </div>
                </div>

                <a href="{{ route('carrito.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                    ← Volver al carrito
                </a>

                <a href="{{ route('productos.index') }}" class="btn btn-outline-dark w-100">
                    ← Seguir comprando
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
