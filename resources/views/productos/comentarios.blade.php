@props(['producto'])

@php
    $comentarios = \App\Models\Comentario::where('id_producto', $producto->id)
        ->with('usuario')
        ->orderByDesc('fecha')
        ->get();

    $promedio = $comentarios->avg('calificacion');
    $total = $comentarios->count();
@endphp

<div class="comentarios-section mt-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="mb-0 fw-bold">
            <i class="fa-solid fa-comments"></i> Opiniones de clientes
            @if($total > 0)
                <span class="badge bg-warning text-dark ms-2">{{ number_format($promedio, 1) }} <i class="fa-solid fa-star"></i></span>
                <span class="text-muted ms-1 fs-6">({{ $total }} {{ Str::plural('opinión', $total) }})</span>
            @endif
        </h5>
    </div>

    @if($total > 0)
        {{-- Resumen de calificaciones --}}
        <div class="bg-light rounded p-3 mb-4">
            @for($i = 5; $i >= 1; $i--)
                @php
                    $count = $comentarios->where('calificacion', $i)->count();
                    $pct = $total > 0 ? round(($count / $total) * 100) : 0;
                @endphp
                <div class="d-flex align-items-center mb-1">
                    <span class="me-2 text-muted" style="width:20px">{{ $i }}</span>
                    <i class="fa-solid fa-star text-warning me-2" style="font-size:0.7rem"></i>
                    <div class="progress flex-grow-1 me-2" style="height:8px; max-width:200px">
                        <div class="progress-bar bg-warning" style="width:{{ $pct }}%"></div>
                    </div>
                    <small class="text-muted" style="width:35px">{{ $count }}</small>
                </div>
            @endfor
        </div>

        {{-- Lista de comentarios --}}
        @foreach($comentarios as $com)
            <div class="border-bottom pb-3 mb-3">
                <div class="d-flex align-items-center mb-2">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                         style="width:36px;height:36px;font-size:0.85rem;font-weight:600">
                        {{ strtoupper(substr($com->usuario->nombre ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <strong class="d-block" style="font-size:0.9rem">{{ $com->usuario->nombre ?? 'Cliente' }}</strong>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($com->fecha)->format('d/m/Y') }}</small>
                    </div>
                    <div class="ms-auto">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa-solid fa-star {{ $i <= $com->calificacion ? 'text-warning' : 'text-muted' }}"
                               style="font-size:0.7rem"></i>
                        @endfor
                    </div>
                </div>
                <p class="mb-0 text-secondary" style="font-size:0.92rem; line-height:1.5">{{ $com->comentario }}</p>
            </div>
        @endforeach
    @else
        <div class="text-center text-muted py-4">
            <i class="fa-regular fa-comment-dots fa-2x mb-2 d-block"></i>
            Aun no hay opiniones para este producto.
        </div>
    @endif
</div>
