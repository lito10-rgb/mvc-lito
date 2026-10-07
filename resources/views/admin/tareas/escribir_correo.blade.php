@extends('layouts.admin')

@section('title', 'Escribir mensaje al proveedor')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0"><i class="fas fa-paper-plane me-2 text-primary"></i> Escribir mensaje</h3>
        <a href="{{ route('admin.tareas.outlook') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Volver a correos
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <h5>De: <strong>{{ $correo['remitente'] }}</strong> &lt;{{ $correo['email'] }}&gt;</h5>
            <div class="text-muted small">Asunto del correo recibido: {{ $correo['asunto'] }} · {{ $correo['fecha'] }}
                · Cuenta: <span class="badge bg-dark">{{ $correo['cuenta'] }}</span></div>
            @if($proveedor)
                <span class="badge bg-success mt-1">Este remitente ya está registrado como proveedor</span>
            @else
                <span class="badge bg-warning text-dark mt-1">Este remitente aún no está registrado como proveedor</span>
            @endif
        </div>
    </div>

    <form method="POST" action="{{ route('admin.tareas.outlook.enviar') }}">
        @csrf
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Para (email del proveedor)</label>
                        <input type="email" name="para" class="form-control" value="{{ $correo['email'] }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Nombre del proveedor</label>
                        <input type="text" name="nombre" class="form-control" value="{{ $correo['remitente'] }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Negocio (marca del mensaje)</label>
                        <select name="negocio_id" id="negocio-select" class="form-select">
                            @foreach($negocios as $neg)
                                <option value="{{ $neg->id }}" {{ $negocioSeleccionado && $negocioSeleccionado->id == $neg->id ? 'selected' : '' }}>
                                    {{ $neg->nombre }} ({{ $neg->dominio }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Enviar desde (correo)</label>
                        <select name="desde" id="desde-select" class="form-select">
                            @foreach($cuentasFrom as $cta)
                                <option value="{{ $cta }}" {{ $cta == $desde ? 'selected' : '' }}>{{ $cta }}</option>
                            @endforeach
                            @if($negocios->count() > 0 && !in_array($desde, array_values($cuentasFrom)))
                                <option value="{{ $desde }}" selected>{{ $desde }}</option>
                            @endif
                        </select>
                        <div class="form-text">Se prestablece la cuenta que recibió este correo.</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold">Asunto</label>
                        <input type="text" name="asunto" class="form-control" value="{{ $asuntoSugerido }}" required>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label small fw-bold mb-0">Mensaje</label>
                            <span class="text-muted small">Plantilla editable: adáptala según lo que pida el proveedor.</span>
                        </div>
                        <textarea name="mensaje" rows="14" class="form-control" required>{{ $plantilla }}</textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="button" class="btn btn-outline-secondary me-2" onclick="restaurarPlantilla()">
                    <i class="fas fa-undo me-1"></i> Restaurar plantilla del negocio
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-1"></i> Enviar mensaje
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const plantillasPorNegocio = {!! json_encode($plantillasPorNegocio) !!};
    const negocios = {!! $negocios->map(fn($n) => ['id' => $n->id, 'dominio' => $n->dominio])->toJson() !!};
    const cuentasDisponibles = {!! json_encode(array_values($cuentasFrom)) !!};

    const negocioSelect = document.getElementById('negocio-select');
    const desdeSelect = document.getElementById('desde-select');
    const ta = document.querySelector('textarea[name="mensaje"]');

    function sugerirDesde(negocioId) {
        const neg = negocios.find(n => n.id == negocioId);
        if (!neg) return;
        // Cuenta de ese negocio en las disponibles, si no, informes@dominio
        const match = cuentasDisponibles.find(c => c.endsWith('@' + neg.dominio));
        const sugerido = match || 'informes@' + neg.dominio;
        if ([...desdeSelect.options].some(o => o.value === sugerido)) {
            desdeSelect.value = sugerido;
        } else {
            const opt = new Option(sugerido, sugerido, true, true);
            desdeSelect.appendChild(opt);
            desdeSelect.value = sugerido;
        }
    }

    negocioSelect.addEventListener('change', function () {
        if (plantillasPorNegocio[this.value]) {
            ta.value = plantillasPorNegocio[this.value];
        }
        sugerirDesde(this.value);
    });

    function restaurarPlantilla() {
        if (plantillasPorNegocio[negocioSelect.value]) {
            ta.value = plantillasPorNegocio[negocioSelect.value];
        }
        sugerirDesde(negocioSelect.value);
    }
</script>
@endpush