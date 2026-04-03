@extends('layouts.volt')

@section('content')
<h1>Asignar Roles y Rubros</h1>

<form method="POST" action="{{ route('admin.usuarios.asignar.store') }}">
    @csrf

    {{-- Panel de asignación --}}
    @include('admin.usuarios._panel_asignacion')

    {{-- Tabla --}}
    @include('admin.usuarios._tabla_seleccion')

    <div class="mt-3">
        {{ $users->links() }}
    </div>
</form>
@endsection
<script>
document.getElementById('check-all')?.addEventListener('change', function () {
    document.querySelectorAll('input[name="users[]"]')
        .forEach(cb => cb.checked = this.checked);
});
</script>
