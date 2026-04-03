@extends('layouts.volt')

@section('title', 'Editar Producto')

@section('content')
<div class="container-fluid px-4">
    <div class="card border-0 shadow mb-4">
        <div class="card-header">
            <h5 class="mb-0">Editar Producto</h5>
        </div>

        <div class="card-body">
            @include('admin.productos._form', [
                'route' => route('admin.productos.update', $producto->id),
                'method' => 'PUT',
                'producto' => $producto
            ])
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Cuando se haga clic en el botón de eliminar imagen
        document.querySelectorAll('.btn-remove-image').forEach(button => {
            button.addEventListener('click', function () {
                const container = this.closest('.image-wrapper');
                const imagen = this.dataset.imagen;

                // Elimina el div del DOM
                container.remove();

                // Actualiza el input hidden
                const inputHidden = document.getElementById('imagenes_actuales');
                let imagenes = JSON.parse(inputHidden.value);
                imagenes = imagenes.filter(img => img !== imagen);
                inputHidden.value = JSON.stringify(imagenes);
            });
        });
    });
    // aqui de portada
  document.addEventListener("DOMContentLoaded", function () {
    const removeBtn = document.getElementById("remove-portada");
    const portadaContainer = document.getElementById("current-portada");
    const removeInput = document.getElementById("remove_portada");

    if (removeBtn) {
        removeBtn.addEventListener("click", function () {
            // Ocultar la portada visualmente
            portadaContainer.style.display = "none";
            // Marcar el hidden input para eliminar al guardar
            removeInput.value = "1";
        });
    }
});

</script>
@endsection