@extends('layouts.admin')

@section('title', 'Productos hermanos desde cotizaciones')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0"><i class="fas fa-copy me-2 text-success"></i> Productos hermanos desde cotizaciones</h3>
        <a href="{{ route('admin.tareas.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Volver a Tareas
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="alert alert-info small">
        <i class="fas fa-info-circle me-1"></i>
        Estos son los productos que has cotizado, agrupados. Crea <strong>productos hermanos</strong> partiendo de ellos
        (título, descripción, precio e imagen se capturan automáticamente).
    </div>

    <div class="row g-3">
        @forelse($agrupados as $item)
        <div class="col-md-6 col-xl-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex mb-2">
                        @if($item['portada'])
                            <img src="{{ asset('storage/' . $item['portada']) }}" alt=""
                                 class="me-3 rounded" style="width:70px;height:70px;object-fit:cover;"
                                 onerror="this.style.display='none'">
                        @endif
                        <div>
                            <h6 class="fw-bold mb-1">{{ $item['producto'] }}</h6>
                            <div class="text-muted small mb-1">
                                Cotizado {{ $item['veces'] }}x · Cantidad {{ $item['cantidad_total'] }}
                                @if($item['producto_id'])
                                    · <span class="badge bg-success">tiene producto</span>
                                @else
                                    · <span class="badge bg-warning text-dark">item libre</span>
                                @endif
                            </div>
                            @if($item['precio_unitario'] > 0)
                                <span class="fw-bold">S/ {{ number_format($item['precio_unitario'], 2) }}</span>
                            @endif
                        </div>
                    </div>

                    @if($item['descripcion'])
                        <p class="text-muted small mb-2">{{ Str::limit($item['descripcion'], 120) }}</p>
                    @endif

                    <div class="d-flex gap-2">
                        @if($item['producto_id'])
                            <a href="{{ route('admin.productos.duplicar', $item['producto_id']) }}" class="btn btn-sm btn-success">
                                <i class="fas fa-copy me-1"></i> Crear hermano (duplicar)
                            </a>
                        @else
                            <button type="button" class="btn btn-sm btn-success"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalHermano"
                                    data-titulo="{{ $item['producto'] }}"
                                    data-descripcion="{{ $item['descripcion'] }}"
                                    data-precio="{{ $item['precio_unitario'] > 0 ? $item['precio_unitario'] : '' }}"
                                    data-portada="{{ $item['portada'] }}">
                                <i class="fas fa-copy me-1"></i> Crear hermano
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-secondary text-center">
                No hay productos cotizados aún. Crea cotizaciones primero.
            </div>
        </div>
        @endforelse
    </div>
</div>

{{-- ===== MODAL CREAR HERMANO (item libre) ===== --}}
<div class="modal fade" id="modalHermano" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <form method="POST" action="{{ route('admin.tareas.productosHermano') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-copy me-2"></i> Crear producto hermano</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-md-8">
                        <label class="form-label small fw-bold">Título</label>
                        <input type="text" name="titulo" class="form-control hh-titulo" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Precio (S/)</label>
                        <input type="number" step="0.01" min="0" name="precio" class="form-control hh-precio" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold">Descripción</label>
                        <textarea name="descripcion" rows="2" class="form-control hh-descripcion"></textarea>
                    </div>
                    <input type="hidden" name="portada" class="hh-portada">

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Categoría</label>
                        <select name="categoria_id" class="form-select hh-categoria" required>
                            <option value="">-- Elegir --</option>
                            @foreach(\App\Models\Categoria::orderBy('categoria')->get() as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->categoria }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Subcategoría</label>
                        <select name="subcategoria_id" class="form-select hh-subcategoria" required disabled>
                            <option value="">-- Primero categoría --</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Marca</label>
                        <select name="marca_id" class="form-select hh-marca">
                            <option value="">-- Opcional --</option>
                            @foreach(\App\Models\Marca::orderBy('nombre')->get() as $marca)
                                <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Proveedor</label>
                        <select name="proveedor_id" class="form-select hh-proveedor">
                            <option value="">-- Opcional --</option>
                            @foreach(\App\Models\Proveedor::orderBy('nombre')->get() as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-none hh-portada-preview-wrap">
                        <label class="form-label small fw-bold">Portada capturada</label>
                        <img class="hh-portada-preview img-fluid rounded border" style="max-height:70px;" alt="">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Crear hermano</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-bs-target="#modalHermano"]').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelector('.hh-titulo').value = this.dataset.titulo || '';
            document.querySelector('.hh-descripcion').value = this.dataset.descripcion || '';
            document.querySelector('.hh-precio').value = this.dataset.precio || '';
            document.querySelector('.hh-portada').value = this.dataset.portada || '';
            const img = document.querySelector('.hh-portada-preview');
            if (this.dataset.portada) {
                img.src = '{{ asset('storage') }}/' + this.dataset.portada;
                document.querySelector('.hh-portada-preview-wrap').classList.remove('d-none');
            } else {
                img.removeAttribute('src');
                document.querySelector('.hh-portada-preview-wrap').classList.add('d-none');
            }
        });
    });

    // Cargar subcategorías según categoría
    document.querySelector('.hh-categoria').addEventListener('change', function () {
        const sub = document.querySelector('.hh-subcategoria');
        sub.innerHTML = '<option value="">-- Cargando --</option>';
        sub.disabled = false;
        if (!this.value) return;
        fetch('{{ route('admin.tareas.subcategorias', ['categoria' => '__ID__']) }}'.replace('__ID__', this.value))
            .then(r => r.json())
            .then(data => {
                sub.innerHTML = '<option value="">-- Elegir --</option>';
                data.forEach(s => {
                    const o = document.createElement('option');
                    o.value = s.id;
                    o.textContent = s.nombre;
                    sub.appendChild(o);
                });
            });
    });
</script>
@endpush