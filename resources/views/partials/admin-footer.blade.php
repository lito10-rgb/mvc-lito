<footer class="bg-dark mt-4 py-3">
    <div class="text-center small text-white">
        &copy; {{ date('Y') }} {{ config('theme.site_name') }}. Panel de Administración.
        <a href="{{ url('/') }}" class="text-decoration-none ms-2 text-warning" target="_blank">
            <i class="fa-solid fa-up-right-from-square"></i> Ir al sitio
        </a>
    </div>
</footer>
