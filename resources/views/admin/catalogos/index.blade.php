@extends('layouts.admin')

@section('title', 'Catálogo')

@section('content')
<div class="container">
    <h2 class="mb-4">Generar Catálogo</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.catalogos.print') }}" target="_blank">
                <div class="row g-3">
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
                        <select name="tipo" id="tipo" class="form-select" required>
                            <option value="">— Seleccione —</option>
                            <option value="categoria">Por Categoría</option>
                            <option value="marca">Por Marca</option>
                            <option value="todo">Todo</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Seleccionar</label>
                        <select name="id" id="selector" class="form-select" required disabled>
                            <option value="">— Primero seleccione tipo —</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Opciones</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="sin_precio" value="1" id="sinPrecio">
                            <label class="form-check-label" for="sinPrecio">Generar sin precios</label>
                        </div>
                    </div>
                    <div class="col-md-8 text-end">
                        <button type="submit" class="btn btn-theme-accent btn-lg" disabled id="btnGenerar">
                            <i class="fas fa-file-pdf me-2"></i>Generar Catálogo
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <h5 class="card-title">Vista previa</h5>
            <p class="text-muted mb-0">Selecciona una categoría o marca y presiona "Generar Catálogo" para verlo en una nueva pestaña. Desde allí puedes imprimir o guardar como PDF.</p>
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

const categorias = @json($categorias->map(fn($c) => ['id' => $c->id, 'nombre' => $c->nombre, 'negocios' => $c->negocios->pluck('id')]));
const marcas = @json($marcas->map(fn($m) => ['id' => $m->id, 'nombre' => $m->nombre, 'negocios' => $m->negocios->pluck('id')]));

function populateSelector() {
    const tipo = tipoSelect.value;
    const negId = negocioSelect.value;

    selector.innerHTML = '<option value="">— Seleccione —</option>';

    if (tipo === 'todo') {
        selector.disabled = true;
        btn.disabled = false;
        return;
    }
    if (!tipo) {
        selector.disabled = true;
        btn.disabled = true;
        return;
    }

    const data = tipo === 'categoria' ? categorias : marcas;
    const filtered = negId ? data.filter(item => item.negocios.includes(parseInt(negId))) : data;

    if (filtered.length === 0) {
        selector.innerHTML = '<option value="">— Sin opciones para este negocio —</option>';
        selector.disabled = true;
        btn.disabled = true;
        return;
    }

    filtered.forEach(item => {
        const opt = document.createElement('option');
        opt.value = item.id;
        opt.textContent = item.nombre;
        selector.appendChild(opt);
    });
    selector.disabled = false;
    btn.disabled = true;
}

tipoSelect.addEventListener('change', populateSelector);
negocioSelect.addEventListener('change', populateSelector);

selector.addEventListener('change', function () {
    btn.disabled = !this.value;
});
</script>
@endpush
