@props(['producto'])

@if($producto->enOferta && $producto->etiquetaOfertaVisible)
    <p class="mb-2">
        <span style="display:inline-flex; align-items:center; background:linear-gradient(135deg,#ff7a18 0%,#ff416c 60%,#e91e63 100%); color:#fff; font-size:0.82rem; font-weight:600; font-style:italic; letter-spacing:0.02em; padding:0.35rem 0.75rem; border-radius:0.6rem; box-shadow:0 3px 10px rgba(255,65,108,0.35); border:1px solid rgba(255,255,255,0.25);">
            <i class="fa-solid fa-calendar-check me-1"></i>
            {{ $producto->etiquetaOfertaVisible }}
        </span>
    </p>
@endif