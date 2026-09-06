@props(['producto'])

@if($producto->enOferta && ($producto->etiquetaOfertaVisible || $producto->finOfertaEfectiva))
    <p class="mb-2 d-flex flex-wrap align-items-center gap-2">
        @if($producto->etiquetaOfertaVisible)
        <span style="display:inline-flex; align-items:center; background:linear-gradient(135deg,#ff7a18 0%,#ff416c 60%,#e91e63 100%); color:#fff; font-size:0.82rem; font-weight:600; font-style:italic; letter-spacing:0.02em; padding:0.35rem 0.75rem; border-radius:0.6rem; box-shadow:0 3px 10px rgba(255,65,108,0.35); border:1px solid rgba(255,255,255,0.25);">
            <i class="fa-solid fa-calendar-check me-1"></i>
            {{ $producto->etiquetaOfertaVisible }}
        </span>
        @endif
        @if($producto->finOfertaEfectiva)
            <span class="cuenta-regresiva" data-vencimiento="{{ $producto->finOfertaEfectiva->format('Y-m-d H:i:s') }}"
                  style="display:inline-flex; align-items:center; background:rgba(0,0,0,0.75); color:#fff; font-size:0.82rem; font-weight:600; padding:0.35rem 0.75rem; border-radius:0.6rem; box-shadow:0 3px 10px rgba(0,0,0,0.25);">
                <i class="fa-solid fa-stopwatch me-1" style="color:#ffd700;"></i>
                <span class="cuenta-regresiva-texto">--d --h --m</span>
            </span>
        @endif
    </p>
@endif

@once
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.__crIniciado) return;
        window.__crIniciado = true;

        function pintarTodos() {
            document.querySelectorAll('.cuenta-regresiva').forEach(function (el) {
                var restante = Math.floor((new Date(el.dataset.vencimiento.replace(' ', 'T')).getTime() - Date.now()) / 1000);
                if (restante < 0) restante = 0;
                var d = Math.floor(restante / 86400);
                var h = Math.floor((restante % 86400) / 3600);
                var m = Math.floor((restante % 3600) / 60);
                var s = restante % 60;
                el.querySelector('.cuenta-regresiva-texto').textContent = d + 'd ' + h + 'h ' + m + 'm ' + s + 's';
                if (restante <= 0) { el.style.display = 'none'; }
            });
        }
        pintarTodos();
        setInterval(pintarTodos, 1000);
    });
</script>
@endonce