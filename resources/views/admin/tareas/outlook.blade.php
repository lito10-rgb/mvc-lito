@extends('layouts.admin')

@section('title', 'Proveedores que escribieron (Outlook)')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0"><i class="fas fa-envelope-open-text me-2 text-primary"></i> Proveedores que escribieron</h3>
        <div>
            <a href="{{ route('admin.tareas.outlook') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-sync me-1"></i> Volver a escanear
            </a>
            <a href="{{ route('admin.tareas.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Tareas
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="alert alert-warning small">
        <i class="fas fa-info-circle me-1"></i>
        Leyendo la <strong>Bandeja de entrada</strong> de <strong>todas las cuentas</strong> configuradas en tu Outlook local
        (correos de los <strong>últimos 30 días</strong>). <strong>🔖 = marcado con banderita</strong> (se muestran primero).
        Esto solo funciona en tu computadora (XAMPP local con Outlook abierto).
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-3 align-items-center mb-2">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="toggle-spam" checked>
                    <label class="form-check-label small" for="toggle-spam">Ocultar posible spam</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="toggle-bandera" checked>
                    <label class="form-check-label small" for="toggle-bandera">Solo con banderita 🔖</label>
                </div>
                <span class="badge bg-danger">Con banderita: {{ count(array_filter($correos, fn($c) => $c['bandera'])) }}</span>
                <span class="badge bg-secondary">Total: {{ count($correos) }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>🔖</th>
                            <th>Cuenta</th>
                            <th>Remitente</th>
                            <th>Email</th>
                            <th>Asunto</th>
                            <th>Fecha</th>
                            <th>Adjuntos</th>
                            <th>Contactos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paginados as $c)
                        <tr class="{{ $c['spam'] ? 'tareas-spam' : '' }} {{ $c['bandera'] ? '' : 'tareas-sin-bandera' }}" data-bandera="{{ $c['bandera'] ? 1 : 0 }}">
                            <td>{{ $c['bandera'] ? '🔖' : '' }}</td>
                            <td class="small">{{ $c['cuenta'] }}</td>
                            <td>
                                {{ $c['remitente'] }}
                                @if($c['bandera'])
                                    <span class="badge bg-danger">banderita</span>
                                @endif
                                @if($c['spam'])
                                    <span class="badge bg-warning text-dark">posible spam</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $c['email'] }}</td>
                            <td>{{ Str::limit($c['asunto'], 60) }}</td>
                            <td class="small">{{ $c['fecha'] }}</td>
                            <td>
                                @if(count($c['adjuntos']) > 0)
                                    <span class="badge bg-info text-dark" title="{{ collect($c['adjuntos'])->pluck('nombre')->implode(', ') }}">
                                        {{ count($c['adjuntos']) }} adjunto(s)
                                    </span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @php $numContactos = $porEmail[strtolower(trim($c['email']))] ?? 0; @endphp
                                @if($numContactos > 0)
                                    <span class="badge bg-success" title="Veces que se le escribió/respondió">
                                        <i class="fas fa-reply me-1"></i>{{ $numContactos }}
                                    </span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <form method="POST" action="{{ route('admin.tareas.outlook.inscribir') }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="idx" value="{{ $c['idx'] }}">
                                        <button class="btn btn-sm btn-success" title="Registrar proveedor">
                                            <i class="fas fa-user-plus me-1"></i> Inscribir
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.tareas.outlook.escribir', $c['idx']) }}" class="btn btn-sm btn-primary" title="Escribir mensaje al proveedor">
                                        <i class="fas fa-paper-plane me-1"></i> Escribir
                                    </a>
                                    @if(count($c['adjuntos']) > 0)
                                    <a href="{{ route('admin.tareas.outlook.correo', $c['idx']) }}" class="btn btn-sm btn-warning" title="Ver catálogo">
                                        <i class="fas fa-file-pdf me-1"></i> Catálogo
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No se pudo leer el correo.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($paginados->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $paginados->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const toggleSpam = document.getElementById('toggle-spam');
    const toggleBandera = document.getElementById('toggle-bandera');
    const filas = document.querySelectorAll('table.table tbody tr');

    function actualizarFiltro() {
        const ocultarSpam = toggleSpam.checked;
        const soloBandera = toggleBandera.checked;
        filas.forEach(fila => {
            const esSpam = fila.classList.contains('tareas-spam');
            const conBandera = fila.dataset.bandera === '1';
            let mostrar = true;
            if (ocultarSpam && esSpam) mostrar = false;
            if (soloBandera && !conBandera) mostrar = false;
            fila.style.display = mostrar ? '' : 'none';
        });
    }

    toggleSpam.addEventListener('change', actualizarFiltro);
    toggleBandera.addEventListener('change', actualizarFiltro);
    actualizarFiltro();
</script>
@endpush