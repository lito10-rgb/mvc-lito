@extends('layouts.admin')

@section('title', 'Capturar productos del correo')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0"><i class="fas fa-file-pdf me-2 text-warning"></i> Capturar productos del correo</h3>
        <a href="{{ route('admin.tareas.outlook') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Volver a correos
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <h5>De: <strong>{{ $correo['remitente'] }}</strong> &lt;{{ $correo['email'] }}&gt;</h5>
            <div class="text-muted small">Asunto: {{ $correo['asunto'] }} · {{ $correo['fecha'] }}</div>
        </div>
    </div>

    <div class="mb-3">
        <div class="row g-2">
            <div class="col-md-4">
                <label class="form-label small fw-bold">Categoría</label>
                <select class="form-select" id="cap-categoria">
                    <option value="">-- Elegir --</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->categoria }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold">Marca</label>
                <select class="form-select" id="cap-marca">
                    <option value="">-- Opcional --</option>
                    @foreach($marcas as $marca)
                        <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold">Proveedor (origen)</label>
                <select class="form-select" id="cap-proveedor" name="proveedor_id">
                    <option value="">-- Elegir o crear --</option>
                    @foreach($proveedores as $prov)
                        <option value="{{ $prov->id }}">{{ $prov->nombre }}</option>
                    @endforeach
                    <option value="__nuevo__">➕ Registrar proveedor nuevo</option>
                </select>
            </div>

            <div class="col-12 d-none" id="cap-nuevo-prov">
                <div class="border rounded p-2 bg-light d-flex gap-2">
                    <input type="text" class="form-control" id="cap-prov-nombre" placeholder="Nombre del proveedor">
                    <input type="email" class="form-control" id="cap-prov-email" placeholder="Email (opcional)">
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.tareas.outlook.capturar') }}">
        @csrf
        <input type="hidden" name="categoria_id" id="cap-categoria-input">
        <input type="hidden" name="marca_id" id="cap-marca-input">
        <input type="hidden" name="proveedor_id" id="cap-proveedor-input">
        <input type="hidden" name="crear_proveedor" id="cap-crear-proveedor-input" value="0">
        <input type="hidden" name="nombre_proveedor" id="cap-prov-nombre-input">
        <input type="hidden" name="email_proveedor" id="cap-prov-email-input">

        <div class="row g-3">
            {{-- Cuerpo del correo --}}
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light fw-bold">Líneas detectadas en el cuerpo del correo</div>
                    <div class="card-body" style="max-height:600px;overflow-y:auto;">
                        @if(count($lineasCandidatas) > 0)
                            @foreach($lineasCandidatas as $i => $linea)
                                <div class="form-check">
                                    <input class="form-check-input cap-linea" type="checkbox" name="lineas[]" value="{{ $linea }}" id="cap-linea-{{ $i }}">
                                    <label class="form-check-label small" for="cap-linea-{{ $i }}">{{ $linea }}</label>
                                </div>
                            @endforeach
                        @else
                            <div class="text-muted small">No se detectaron líneas candidatas en el cuerpo.</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- PDFs adjuntos --}}
            @foreach($pdfTextos as $pdfNombre => $lineasPdf)
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light fw-bold">
                        <i class="fas fa-file-pdf me-1 text-danger"></i> {{ $pdfNombre }}
                        <span class="badge bg-secondary ms-1">{{ count($lineasPdf) }} líneas</span>
                    </div>
                    <div class="card-body" style="max-height:600px;overflow-y:auto;">
                        @if(count($lineasPdf) > 0)
                            @foreach($lineasPdf as $i => $linea)
                                <div class="form-check">
                                    <input class="form-check-input cap-linea" type="checkbox" name="lineas[]" value="{{ $linea }}" id="cap-pdf-{{ $pdfNombre }}-{{ $i }}">
                                    <label class="form-check-label small" for="cap-pdf-{{ $pdfNombre }}-{{ $i }}">{{ $linea }}</label>
                                </div>
                            @endforeach
                        @else
                            <div class="text-muted small">No se pudo extraer texto del PDF (imágenes) o no es válido.</div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-success mt-3" id="btn-capturar">
            <i class="fas fa-download me-1"></i> Capturar productos seleccionados
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function sincronizarCampos() {
        document.getElementById('cap-categoria-input').value = document.getElementById('cap-categoria').value;
        document.getElementById('cap-marca-input').value = document.getElementById('cap-marca').value;

        const provSel = document.getElementById('cap-proveedor');
        const valor = provSel.value;
        document.getElementById('cap-nuevo-prov').classList.toggle('d-none', valor !== '__nuevo__');

        if (valor === '__nuevo__') {
            document.getElementById('cap-proveedor-input').value = '';
            document.getElementById('cap-crear-proveedor-input').value = '1';
            document.getElementById('cap-prov-nombre-input').value = document.getElementById('cap-prov-nombre').value;
            document.getElementById('cap-prov-email-input').value = document.getElementById('cap-prov-email').value;
        } else {
            document.getElementById('cap-proveedor-input').value = valor;
            document.getElementById('cap-crear-proveedor-input').value = '0';
        }
        if (document.getElementById('cap-crear-proveedor-input').value === '1'
            && !document.getElementById('cap-prov-nombre-input').value) {
            document.getElementById('btn-capturar').disabled = true;
        } else {
            document.getElementById('btn-capturar').disabled = false;
        }
    }

    ['cap-categoria', 'cap-marca', 'cap-proveedor'].forEach(id => {
        document.getElementById(id).addEventListener('change', sincronizarCampos);
    });
    ['cap-prov-nombre', 'cap-prov-email'].forEach(id => {
        document.getElementById(id).addEventListener('input', sincronizarCampos);
    });
    sincronizarCampos();
</script>
@endpush