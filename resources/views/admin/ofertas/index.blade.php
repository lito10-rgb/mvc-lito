@extends('layouts.volt')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold"><i class="fas fa-tags me-2 text-warning"></i>Gestión de Ofertas</h3>
        <div>
            <span class="badge bg-warning text-dark fs-6">{{ $productos->total() }} productos con oferta</span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filtros --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small">Negocio</label>
                    <select name="negocio_id" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        @foreach($negocios as $neg)
                            <option value="{{ $neg->id }}" {{ request('negocio_id') == $neg->id ? 'selected' : '' }}>{{ $neg->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Categoría</label>
                    <select name="categoria_id" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->categoria }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Subcategoría</label>
                    <select name="subcategoria_id" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        @foreach($subcategorias as $sub)
                            <option value="{{ $sub->id }}" {{ request('subcategoria_id') == $sub->id ? 'selected' : '' }}>{{ $sub->subcategoria }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Tipo de oferta</label>
                    <select name="tipo" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        <option value="producto" {{ request('tipo') == 'producto' ? 'selected' : '' }}>% producto</option>
                        <option value="precio_fijo" {{ request('tipo') == 'precio_fijo' ? 'selected' : '' }}>Precio fijo</option>
                        <option value="descuento_soles" {{ request('tipo') == 'descuento_soles' ? 'selected' : '' }}>Descuento S/</option>
                        <option value="vencida" {{ request('tipo') == 'vencida' ? 'selected' : '' }}>Vencida</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-filter me-1"></i> Filtrar</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.ofertas.index') }}" class="btn btn-outline-secondary btn-sm w-100"><i class="fas fa-rotate-right me-1"></i> Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Acciones en bloque --}}
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="select-all">
            <label class="form-check-label" for="select-all">Seleccionar todos</label>
        </div>
        <button class="btn btn-danger btn-sm" id="btn-quitar-bloque" disabled>
            <i class="fas fa-ban me-1"></i> Quitar oferta ({!! '<span id="count-selected">0</span>' !!})
        </button>
    </div>

    {{-- Tabla --}}
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tabla-ofertas">
                <thead class="table-dark">
                    <tr>
                        <th style="width:40px"><input type="checkbox" class="form-check-input check-all" style="display:none;"></th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Precio Final</th>
                        <th>Origen</th>
                        <th>Fin Oferta</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $producto)
                    @php
                        $precio = (float) $producto->precio;
                        $final = (float) $producto->precioFinal;
                        $origen = '-';
                        $origenColor = 'secondary';
                        if ($producto->oferta > 0) {
                            $origen = $producto->oferta . '% propio';
                            $origenColor = 'primary';
                        } elseif ($producto->precioOferta > 0) {
                            $origen = 'Precio fijo S/' . number_format($producto->precioOferta, 2);
                            $origenColor = 'primary';
                        } elseif ($producto->descuentoOferta > 0) {
                            $origen = 'Dcto S/' . number_format($producto->descuentoOferta, 2);
                            $origenColor = 'primary';
                        } elseif (($producto->categoria->oferta ?? 0) > 0) {
                            $origen = 'Cat: ' . $producto->categoria->oferta . '%';
                            $origenColor = 'warning';
                        } elseif (($producto->subcategoria->oferta ?? 0) > 0) {
                            $origen = 'Sub: ' . $producto->subcategoria->oferta . '%';
                            $origenColor = 'info';
                        }
                        $finOferta = $producto->finOfertaEfectiva;
                    @endphp
                    <tr data-id="{{ $producto->id }}">
                        <td><input type="checkbox" class="form-check-input row-check" value="{{ $producto->id }}"></td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($producto->portada)
                                    <img src="{{ asset('storage/' . $producto->portada) }}" class="rounded me-2" width="40" height="40" style="object-fit:cover;">
                                @endif
                                <div>
                                    <div class="fw-bold small">{{ $producto->titulo }}</div>
                                    <small class="text-muted">ID: {{ $producto->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td><small>{{ $producto->categoria->categoria ?? '-' }}</small></td>
                        <td>
                            @if($final < $precio)
                                <span class="text-decoration-line-through text-muted">S/ {{ number_format($precio, 2) }}</span>
                            @else
                                <strong>S/ {{ number_format($precio, 2) }}</strong>
                            @endif
                        </td>
                        <td>
                            @if($final < $precio)
                                <strong class="text-danger">S/ {{ number_format($final, 2) }}</strong>
                                <span class="badge text-bg-danger ms-1">{{ $producto->descuentoEtiqueta }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $origenColor }}">{{ $origen }}</span>
                            @if($producto->etiquetaOfertaVisible)
                                <br><small class="text-muted fst-italic">{{ $producto->etiquetaOfertaVisible }}</small>
                            @endif
                        </td>
                        <td>
                            @if($finOferta)
                                <small class="{{ $finOferta->isPast() ? 'text-danger' : ($finOferta->diffInDays(now()) <= 3 ? 'text-warning' : '') }}">
                                    {{ $finOferta->format('d/m/Y H:i') }}
                                    @if($finOferta->isPast())
                                        <span class="badge bg-danger">Vencida</span>
                                    @endif
                                </small>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-outline-danger btn-sm btn-quitar" title="Quitar oferta" data-id="{{ $producto->id }}">
                                <i class="fas fa-ban"></i> Quitar
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-tags fa-2x mb-2 d-block"></i>
                            No hay productos con ofertas
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $productos->links() }}
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var tbody = document.querySelector('#tabla-ofertas tbody');
    var selectAll = document.getElementById('select-all');
    var btnBloque = document.getElementById('btn-quitar-bloque');
    var countSpan = document.getElementById('count-selected');

    function updateCount() {
        var checked = tbody.querySelectorAll('.row-check:checked').length;
        countSpan.textContent = checked;
        btnBloque.disabled = checked === 0;
    }

    selectAll.addEventListener('change', function () {
        tbody.querySelectorAll('.row-check').forEach(function (cb) {
            cb.checked = selectAll.checked;
        });
        updateCount();
    });

    tbody.addEventListener('change', function (e) {
        if (e.target.classList.contains('row-check')) updateCount();
    });

    tbody.addEventListener('click', function (e) {
        var quitBtn = e.target.closest('.btn-quitar');
        if (quitBtn) {
            if (!confirm('¿Quitar la oferta de este producto?')) return;
            var id = quitBtn.dataset.id;

            fetch('{{ url("admin/ofertas") }}/' + id + '/quitar', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                var row = document.querySelector('tr[data-id="' + id + '"]');
                if (row) row.remove();
                showToast(data.message || 'Oferta removida', 'success');
                updateCount();
            });
        }
    });

    btnBloque.addEventListener('click', function () {
        var ids = [];
        tbody.querySelectorAll('.row-check:checked').forEach(function (cb) {
            ids.push(cb.value);
        });
        if (ids.length === 0) return;
        if (!confirm('¿Quitar oferta de ' + ids.length + ' productos?')) return;

        btnBloque.disabled = true;
        btnBloque.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

        fetch('{{ route("admin.ofertas.quitar-multiple") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ids: ids })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            ids.forEach(function (id) {
                var row = document.querySelector('tr[data-id="' + id + '"]');
                if (row) row.remove();
            });
            selectAll.checked = false;
            updateCount();
            showToast(data.message || 'Ofertas removidas', 'success');
            btnBloque.innerHTML = '<i class="fas fa-ban me-1"></i> Quitar oferta (<span id="count-selected">0</span>)';
            countSpan = document.getElementById('count-selected');
        });
    });

    function showToast(msg, type) {
        var toast = document.createElement('div');
        toast.className = 'position-fixed top-0 end-0 m-3 alert alert-' + type + ' py-2 px-3 shadow';
        toast.style.zIndex = 9999;
        toast.textContent = msg;
        document.body.appendChild(toast);
        setTimeout(function () { toast.remove(); }, 2500);
    }
});
</script>
@endpush
@endsection
