@props(['producto'])

@php
    $comentarios = \App\Models\Comentario::where('id_producto', $producto->id)
        ->with('usuario')
        ->orderByDesc('fecha')
        ->get();

    $promedio = $comentarios->avg('calificacion');
    $total = $comentarios->count();
    $miComentario = null;
    if (auth()->check()) {
        $usuarioAuth = \App\Models\Usuario::where('email', auth()->user()->email)->first();
        if ($usuarioAuth) {
            $miComentario = \App\Models\Comentario::where('id_usuario', $usuarioAuth->id)
                ->where('id_producto', $producto->id)
                ->first();
        }
    }
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

    @if(session('error'))
        <div class="alert alert-danger py-2 small">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success py-2 small">{{ session('success') }}</div>
    @endif

    {{-- Formulario para dejar opinión --}}
    @auth
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="fa-solid fa-pen-to-square"></i> {!! $miComentario ? 'Editar tu opinión' : 'Deja tu opinión' !!}</h6>
                <form action="{{ route('producto.comentar', $producto->ruta) }}" method="POST">
                    @csrf
                    <input type="hidden" name="calificacion" id="calificacion-input" value="{{ $miComentario?->calificacion ?? 5 }}">
                    <div class="mb-3">
                        <label class="form-label small text-muted">Tu calificación</label>
                        <div class="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa-solid fa-star rating-star fs-4 {{ $i <= ($miComentario?->calificacion ?? 5) ? 'text-warning' : 'text-muted' }}"
                                   data-valor="{{ $i }}" style="cursor:pointer" onmouseover="pintarEstrellas(this)" onclick="fijarEstrellas(this)"></i>
                            @endfor
                        </div>
                    </div>
                    <div class="mb-3">
                        <textarea name="comentario" rows="3" class="form-control"
                                  placeholder="Cuéntanos tu experiencia con este producto..." required maxlength="2000">{{ $miComentario?->comentario }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-paper-plane me-1"></i>{{ $miComentario ? 'Actualizar opinión' : 'Publicar opinión' }}
                    </button>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-light border small">
            <i class="fa-solid fa-circle-info me-1 text-primary"></i>
            <a href="{{ route('login') }}" class="fw-semibold">Inicia sesión</a> para dejar tu opinión sobre este producto.
        </div>
    @endauth

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
            Aun no hay opiniones para este producto. ¡Sé el primero!
        </div>
    @endif
</div>

@push('scripts')
<script>
function pintarEstrellas(el) {
    const cont = el.closest('.rating-stars');
    const valor = el.dataset.valor;
    cont.querySelectorAll('.rating-star').forEach(s => {
        s.classList.toggle('text-warning', Number(s.dataset.valor) <= Number(valor));
        s.classList.toggle('text-muted', Number(s.dataset.valor) > Number(valor));
    });
}
function fijarEstrellas(el) {
    const cont = el.closest('.rating-stars');
    const valor = el.dataset.valor;
    document.getElementById('calificacion-input').value = valor;
    cont.querySelectorAll('.rating-star').forEach(s => {
        s.classList.toggle('text-warning', Number(s.dataset.valor) <= Number(valor));
        s.classList.toggle('text-muted', Number(s.dataset.valor) > Number(valor));
    });
}
</script>
@endpush
