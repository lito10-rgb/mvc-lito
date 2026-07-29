@extends('layouts.admin')
@section('title', 'Concursos y Sorteos')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Concursos y Sorteos</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.concursos.sorteo') }}" class="btn btn-warning" target="_blank">
                <i class="fas fa-dice me-1"></i> Sorteo en Vivo
            </a>
            <a href="{{ route('admin.concursos.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Nuevo Concurso
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Premio</th>
                            <th>Fecha Sorteo</th>
                            <th>Participantes</th>
                            <th>Estado</th>
                            <th>Ganador</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($concursos as $c)
                        <tr>
                            <td>{{ $c->id }}</td>
                            <td><strong>{{ $c->nombre }}</strong></td>
                            <td>{{ $c->premio ?? '—' }}</td>
                            <td>{{ $c->fecha_sorteo->format('d/m/Y') }}</td>
                            <td><span class="badge bg-primary">{{ $c->participantes_count }}</span></td>
                            <td>
                                @php
                                    $badge = ['borrador' => 'secondary', 'activo' => 'success', 'finalizado' => 'dark'];
                                @endphp
                                <span class="badge bg-{{ $badge[$c->estado] }}">{{ ucfirst($c->estado) }}</span>
                            </td>
                            <td>
                                @if($c->ganador)
                                    <span class="badge bg-warning text-dark">
                                        {{ $c->ganador->user->nombre }} ({{ $c->ganador->codigo }})
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.concursos.show', $c) }}" class="btn btn-info btn-sm text-white" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($c->estado === 'borrador')
                                <form action="{{ route('admin.concursos.activar', $c) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-success btn-sm" title="Activar"><i class="fas fa-play"></i></button>
                                </form>
                                @endif
                                @if($c->estado !== 'finalizado' && $c->participantes_count > 0)
                                <form action="{{ route('admin.concursos.finalizar', $c) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-dark btn-sm" title="Finalizar"><i class="fas fa-stop"></i></button>
                                </form>
                                @endif
                                <form action="{{ route('admin.concursos.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar concurso?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">No hay concursos creados</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">{{ $concursos->links() }}</div>
</div>
@endsection
