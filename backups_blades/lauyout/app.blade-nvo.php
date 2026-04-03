@include('partials.sidebar')
@include('partials.navbar')

<main class="content">
    <div class="container">
        @yield('content')
    </div>
</main>

@include('partials.footer')
