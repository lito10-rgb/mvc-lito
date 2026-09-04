@extends('layouts.admin')
@section('title', 'Concurso: ' . $concurso->nombre)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">{{ $concurso->nombre }}</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.concursos.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Volver</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-3">Datos del Concurso</h6>
                    <table class="table table-borderless mb-0">
                        <tr><th>Premio</th><td>{{ $concurso->premio ?? '—' }}</td></tr>
                        <tr><th>Fecha Sorteo</th><td>{{ $concurso->fecha_sorteo->format('d/m/Y') }}</td></tr>
                        <tr>
                            <th>Estado</th>
                            <td>
                                @php $badge = ['borrador' => 'secondary', 'activo' => 'success', 'finalizado' => 'dark']; @endphp
                                <span class="badge bg-{{ $badge[$concurso->estado] }}">{{ ucfirst($concurso->estado) }}</span>
                            </td>
                        </tr>
                        <tr><th>Participantes</th><td><strong>{{ $concurso->participantes->count() }}</strong></td></tr>
                        <tr><th>Emails enviados</th><td>{{ $concurso->participantes->where('email_enviado', true)->count() }}</td></tr>
                        @if($concurso->ganador)
                        <tr>
                            <th>Ganador</th>
                            <td><span class="badge bg-warning text-dark">{{ $concurso->ganador->user->nombre }} - {{ $concurso->ganador->codigo }}</span></td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            @if(in_array($concurso->estado, ['borrador', 'activo']))
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Agregar Participantes</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.concursos.generarParticipantes', $concurso) }}" method="POST">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label">Alcance</label>
                                <select name="alcance" class="form-select" id="alcanceSelect">
                                    <option value="compradores">Solo compradores</option>
                                    <option value="todos">Todos los clientes</option>
                                    <option value="seleccionados">Selección manual</option>
                                </select>
                            </div>
                            <div class="col-md-5" id="busquedaContainer" style="display:none;">
                                <label class="form-label">Buscar clientes</label>
                                <input type="text" id="buscarClientes" class="form-control" placeholder="Nombre o email...">
                                <div id="clientesList" class="mt-2" style="max-height:200px;overflow-y:auto;"></div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-plus me-1"></i> Agregar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            @if($concurso->estado !== 'borrador' && $pendientes > 0)
            <div class="mb-4">
                <form action="{{ route('admin.concursos.enviarCorreos', $concurso) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-lg" onclick="return confirm('¿Enviar correos a {{ $pendientes }} participante(s) pendientes?')">
                        <i class="fas fa-envelope me-2"></i> Enviar {{ $pendientes }} correo(s) pendiente(s)
                    </button>
                </form>
            </div>
            @endif

            @if($concurso->estado !== 'borrador' && $concurso->participantes->count() > 0)
            <div class="mb-4">
                <form action="{{ route('admin.concursos.reenviarCorreos', $concurso) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-info text-white btn-lg" onclick="return confirm('¿Reenviar recordatorio a TODOS los {{ $concurso->participantes->count() }} participante(s)?')">
                        <i class="fas fa-rotate me-2"></i> Reenviar recordatorio a todos
                    </button>
                </form>
                @if(!$concurso->ganador)
                <button class="btn btn-danger btn-lg ms-2" id="btnSorteoAutomatico" onclick="sorteoAutomatico()">
                    <i class="fas fa-dice me-2"></i> SORTEO AUTOMÁTICO
                </button>
                @endif
            </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Participantes ({{ $concurso->participantes->count() }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height:400px;overflow-y:auto;">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th style="width:40px;">#</th>
                                    <th>Cliente</th>
                                    <th>Email</th>
                                    <th style="width:100px;">Código</th>
                                    <th style="width:80px;">Email</th>
                                    <th style="width:60px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($concurso->participantes as $idx => $p)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td>{{ $p->user->nombre }} {{ $p->user->apellidos }}</td>
                                    <td>{{ $p->user->email ?? '—' }}</td>
                                    <td><code class="fs-6">{{ $p->codigo }}</code></td>
                                    <td>
                                        @if($p->email_enviado)
                                            <i class="fas fa-check-circle text-success"></i>
                                        @else
                                            <i class="fas fa-clock text-muted"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if($concurso->estado === 'activo' && !$p->ganador)
                                        <button class="btn btn-sm btn-warning btn-ganador"
                                                data-id="{{ $p->id }}"
                                                data-nombre="{{ $p->user->nombre }} {{ $p->user->apellidos }}"
                                                data-codigo="{{ $p->codigo }}"
                                                title="Declarar ganador">
                                            <i class="fas fa-trophy"></i>
                                        </button>
                                        @endif
                                        @if($p->ganador)
                                            <span class="badge bg-warning text-dark"><i class="fas fa-trophy"></i> Ganador</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('alcanceSelect')?.addEventListener('change', function() {
    const container = document.getElementById('busquedaContainer');
    container.style.display = this.value === 'seleccionados' ? '' : 'none';
});

let searchTimeout;
document.getElementById('buscarClientes')?.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const q = this.value.trim();
    if (q.length < 2) return;
    searchTimeout = setTimeout(async () => {
        const res = await fetch("{{ url('/admin/usuarios/buscar') }}?q=${encodeURIComponent(q)}");
        const data = await res.json();
        const list = document.getElementById('clientesList');
        list.innerHTML = data.map(u => `
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="seleccionados[]" value="${u.id}" id="sel_${u.id}">
                <label class="form-check-label" for="sel_${u.id}">${u.nombre} ${u.apellidos} (${u.email || 'sin email'})</label>
            </div>
        `).join('');
    }, 300);
});

document.querySelectorAll('.btn-ganador').forEach(btn => {
    btn.addEventListener('click', async function() {
        const id = this.dataset.id;
        const nombre = this.dataset.nombre;
        const codigo = this.dataset.codigo;
        if (!confirm(`¿Declarar ganador a ${nombre} (${codigo})?`)) return;

        const token = document.querySelector('meta[name="csrf-token"]').content;
        const res = await fetch('{{ route("admin.concursos.declararGanador", [$concurso, "__PARTICIPANTE__"]) }}'.replace('__PARTICIPANTE__', id), {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
            body: JSON.stringify({})
        });
        const data = await res.json();
        if (data.success) {
            alert(`Ganador: ${data.nombre} - Código: ${data.codigo}`);
            location.reload();
        } else {
            alert('Error al declarar ganador');
        }
    });
});

async function sorteoAutomatico() {
    if (!confirm('¿Elegir ganador automáticamente entre TODOS los participantes?')) return;
    const btn = document.getElementById('btnSorteoAutomatico');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Sorteando...';

    const token = document.querySelector('meta[name="csrf-token"]').content;
    const res = await fetch('{{ route("admin.concursos.sorteoAutomatico", $concurso) }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
        body: JSON.stringify({})
    });
    const data = await res.json();
    if (data.success) {
        alert(`GANADOR: ${data.nombre} - Código: ${data.codigo}`);
        location.reload();
    } else {
        alert(data.message || 'Error al sortear');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-dice me-2"></i> SORTEO AUTOMÁTICO';
    }
}
</script>
@endpush
