@extends('layouts.admin')

@section('title', 'Catálogo')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Generar Catálogo</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.catalogos.print') }}" target="_blank" id="formCatalogo">
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Negocio</label>
                        <select name="negocio_id" id="negocio_id" class="form-select">
                            <option value="">Todos los negocios</option>
                            @foreach ($negocios as $n)
                                <option value="{{ $n->id }}" {{ session('negocio_id') == $n->id ? 'selected' : '' }}>{{ $n->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Tipo</label>
                        <select name="tipo" id="tipo" class="form-select">
                            <option value="todo">Todo</option>
                            <option value="categoria">Por Categoría</option>
                            <option value="marca">Por Marca</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Seleccionar</label>
                        <select name="id" id="selector" class="form-select" disabled>
                            <option value="">— Primero seleccione tipo —</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Opciones</label>
                        <div class="d-flex align-items-center gap-3">
                            <div class="form-check form-switch mt-1">
                                <input class="form-check-input" type="checkbox" name="sin_precio" value="1" id="sinPrecio">
                                <label class="form-check-label" for="sinPrecio">Sin precios</label>
                            </div>
                            <div class="form-check form-switch mt-1">
                                <input class="form-check-input" type="checkbox" id="enDolares" onchange="toggleDolares()">
                                <label class="form-check-label" for="enDolares">Precios en dólares</label>
                            </div>
                            <div id="tipoCambioGroup" class="d-none">
                                <label class="form-label small mb-0">Tipo de cambio</label>
                                <input type="number" step="0.001" name="tipo_cambio" id="tipoCambioInput" class="form-control form-control-sm" style="width:110px;" value="{{ $ultimoTipoCambio ?? '3.750' }}" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 text-end d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-outline-secondary btn-lg" id="btnVistaPrevia">
                            <i class="fas fa-eye me-2"></i>Vista previa
                        </button>
                        <button type="submit" class="btn btn-theme-accent btn-lg" id="btnGenerar">
                            <i class="fas fa-file-pdf me-2"></i>Generar Catálogo
                        </button>
                        <input type="hidden" name="preview" id="previewFlag" value="0">
                        <input type="hidden" name="moneda" id="monedaFlag" value="PEN">
                    </div>
                </div>

                <hr>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Buscar producto</label>
                        <input type="text" id="buscadorProductos" class="form-control" placeholder="Escriba el nombre del producto para filtrar...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Categoría</label>
                        <select id="filtroCategoria" class="form-select">
                            <option value="">Todas</option>
                            @foreach ($categorias as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Subcategoría</label>
                        <select id="filtroSubcategoria" class="form-select" disabled>
                            <option value="">Todas</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Marca</label>
                        <select id="filtroMarca" class="form-select">
                            <option value="">Todas</option>
                            @foreach ($marcas as $marca)
                                <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end justify-content-between">
                        <span class="badge bg-secondary fs-6 p-2" id="productosCount">0 productos</span>
                        <span class="badge bg-success fs-6 p-2" id="seleccionadosCount">0 seleccionados</span>
                    </div>
                </div>

                <div class="form-check form-switch mt-3">
                    <input class="form-check-input" type="checkbox" id="seleccionarTodo">
                    <label class="form-check-label" for="seleccionarTodo">Seleccionar todos los productos (filtrados)</label>
                </div>

                <div class="table-responsive" style="max-height:400px;overflow-y:auto;">
                    <table class="table table-hover table-sm mt-2">
                        <thead class="table-dark sticky-top">
                            <tr>
                                <th style="width:40px;"><input type="checkbox" id="checkTodoFila"></th>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Marca</th>
                                <th style="width:110px;">Precio</th>
                                <th style="width:70px;"></th>
                            </tr>
                        </thead>
                        <tbody id="tablaProductos">
                            @foreach ($productos as $p)
                            <tr class="fila-producto"
                                data-nombre="{{ mb_strtolower($p->titulo) }}"
                                data-categoria="{{ mb_strtolower($p->categoria->nombre ?? '') }}"
                                data-subcategoria="{{ mb_strtolower($p->subcategoria->subcategoria ?? '') }}"
                                data-marca="{{ mb_strtolower($p->marca->nombre ?? '') }}"
                                data-categoria-id="{{ $p->categoria_id }}"
                                data-subcategoria-id="{{ $p->subcategoria_id }}"
                                data-marca-id="{{ $p->marca_id }}"
                                data-negocios="{{ $p->negocios->pluck('id')->join(',') }}">
                                <td>
                                    <input type="checkbox" name="productos[]" value="{{ $p->id }}" class="check-producto">
                                </td>
                                <td>{{ $p->titulo }}</td>
                                <td>
                                    @foreach ($p->negocios as $neg)
                                        <span class="badge bg-dark me-1">{{ $neg->nombre }}</span>
                                    @endforeach
                                    {{ $p->categoria->nombre ?? '—' }}
                                    @if ($p->subcategoria)
                                        <span class="badge bg-info text-dark ms-1">{{ $p->subcategoria->subcategoria }}</span>
                                    @endif
                                </td>
                                <td>{{ $p->marca->nombre ?? '—' }}</td>
                                <td class="text-end">S/ {{ number_format($p->precio, 2) }}</td>
                                <td class="text-center text-nowrap">
                                    <a href="{{ route('admin.productos.duplicar', $p) }}" target="_blank" class="btn btn-outline-success btn-sm" title="Duplicar producto">
                                        <i class="fas fa-copy"></i>
                                    </a>
                                    <a href="{{ route('admin.productos.edit', $p) }}" target="_blank" class="btn btn-outline-primary btn-sm" title="Editar producto">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const negocioSelect = document.getElementById('negocio_id');
const tipoSelect = document.getElementById('tipo');
const selector = document.getElementById('selector');
const btn = document.getElementById('btnGenerar');
const buscador = document.getElementById('buscadorProductos');
const filtroCategoria = document.getElementById('filtroCategoria');
const filtroSubcategoria = document.getElementById('filtroSubcategoria');
const filtroMarca = document.getElementById('filtroMarca');
const checkTodoFila = document.getElementById('checkTodoFila');
const seleccionarTodo = document.getElementById('seleccionarTodo');
const filas = Array.from(document.querySelectorAll('.fila-producto'));
const checks = Array.from(document.querySelectorAll('.check-producto'));
const countProd = document.getElementById('productosCount');
const countSel = document.getElementById('seleccionadosCount');

const categorias = @json($categorias->map(fn($c) => ['id' => $c->id, 'nombre' => $c->nombre, 'negocios' => $c->negocios->pluck('id')]));
const marcas = @json($marcas->map(fn($m) => ['id' => $m->id, 'nombre' => $m->nombre, 'negocios' => $m->negocios->pluck('id')]));
const subcategorias = @json($subcategorias->map(fn($s) => ['id' => $s->id, 'nombre' => $s->subcategoria, 'categoria_id' => $s->id_categoria]));

function populateSubcategorias() {
    const catId = filtroCategoria.value;
    filtroSubcategoria.innerHTML = '<option value="">Todas</option>';
    if (!catId) {
        filtroSubcategoria.disabled = true;
        return;
    }
    subcategorias.filter(s => s.categoria_id == catId).forEach(s => {
        const opt = document.createElement('option');
        opt.value = s.id;
        opt.textContent = s.nombre;
        filtroSubcategoria.appendChild(opt);
    });
    filtroSubcategoria.disabled = false;
}

function populateSelector() {
    const tipo = tipoSelect.value;
    const negId = negocioSelect.value;

    selector.innerHTML = '<option value="">— Seleccione —</option>';

    if (tipo === 'todo' || !tipo) {
        selector.disabled = true;
        return;
    }

    const data = tipo === 'categoria' ? categorias : marcas;
    const filtered = negId ? data.filter(item => item.negocios.includes(parseInt(negId))) : data;

    if (filtered.length === 0) {
        selector.innerHTML = '<option value="">— Sin opciones para este negocio —</option>';
        selector.disabled = true;
        return;
    }

    filtered.forEach(item => {
        const opt = document.createElement('option');
        opt.value = item.id;
        opt.textContent = item.nombre;
        selector.appendChild(opt);
    });
    selector.disabled = false;
}

function visibles() {
    const q = buscador.value.trim().toLowerCase();
    const negId = negocioSelect.value;
    const catId = filtroCategoria.value;
    const subcatId = filtroSubcategoria.value;
    const marcaId = filtroMarca.value;

    return filas.filter(row => {
        const nombre = row.dataset.nombre || '';
        const categoria = row.dataset.categoria || '';
        const subcategoria = row.dataset.subcategoria || '';
        const marca = row.dataset.marca || '';
        const negocios = (row.dataset.negocios || '').split(',').map(Number);

        if (q && !nombre.includes(q) && !marca.includes(q) && !categoria.includes(q) && !subcategoria.includes(q)) return false;
        if (negId && !negocios.includes(parseInt(negId))) return false;
        if (catId && row.dataset.categoriaId != catId) return false;
        if (subcatId && row.dataset.subcategoriaId != subcatId) return false;
        if (marcaId && row.dataset.marcaId != marcaId) return false;
        return true;
    });
}

function aplicarFiltros() {
    const vis = visibles();
    filas.forEach(row => {
        row.style.display = vis.includes(row) ? '' : 'none';
    });
    checkTodoFila.checked = vis.length > 0 && vis.every(r => r.querySelector('.check-producto').checked);
    contar();
}

function contar() {
    const total = visibles().length;
    const seleccionados = checks.filter(c => c.checked).length;
    countProd.textContent = total + ' productos';
    countSel.textContent = seleccionados + ' seleccionados';
    btn.disabled = seleccionados === 0;
    document.getElementById('btnVistaPrevia').disabled = seleccionados === 0;
}

document.getElementById('btnVistaPrevia').addEventListener('click', function() {
    document.getElementById('previewFlag').value = '1';
});

document.getElementById('btnGenerar').addEventListener('click', function() {
    document.getElementById('previewFlag').value = '0';
});

function toggleDolares() {
    const checked = document.getElementById('enDolares').checked;
    document.getElementById('tipoCambioGroup').classList.toggle('d-none', !checked);
    document.getElementById('monedaFlag').value = checked ? 'USD' : 'PEN';
}

tipoSelect.addEventListener('change', populateSelector);
negocioSelect.addEventListener('change', () => { populateSelector(); aplicarFiltros(); });
buscador.addEventListener('input', aplicarFiltros);
filtroCategoria.addEventListener('change', () => { populateSubcategorias(); aplicarFiltros(); });
filtroSubcategoria.addEventListener('change', aplicarFiltros);
filtroMarca.addEventListener('change', aplicarFiltros);

checkTodoFila.addEventListener('change', function() {
    const vis = visibles();
    vis.forEach(row => {
        row.querySelector('.check-producto').checked = this.checked;
    });
    contar();
});

seleccionarTodo.addEventListener('change', function() {
    const vis = visibles();
    vis.forEach(row => {
        row.querySelector('.check-producto').checked = this.checked;
    });
    checkTodoFila.checked = this.checked;
    contar();
});

checks.forEach(c => c.addEventListener('change', contar));

document.addEventListener('DOMContentLoaded', () => {
    populateSelector();
    populateSubcategorias();
    aplicarFiltros();
});
</script>
@endpush