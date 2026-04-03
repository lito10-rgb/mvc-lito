// resources/js/perfil.js

document.addEventListener('DOMContentLoaded', function () {

    // ----- PAISES → ESTADOS -----
    document.querySelector('#pais').addEventListener('change', function () {
        let id = this.value;

        fetch(`/ubicacion/estados/${id}`)
            .then(res => res.json())
            .then(data => {
                let html = '<option value="">Seleccione</option>';
                data.forEach(e => html += `<option value="${e.id}">${e.nombre}</option>`);
                document.querySelector('#estado').innerHTML = html;

                // limpiar dependencias
                document.querySelector('#provincia').innerHTML = '<option value="">Seleccione</option>';
                document.querySelector('#distrito').innerHTML = '<option value="">Seleccione</option>';
            });
    });

    // ----- ESTADOS → PROVINCIAS -----
    document.querySelector('#estado').addEventListener('change', function () {
        let id = this.value;

        fetch(`/ubicacion/provincias/${id}`)
            .then(res => res.json())
            .then(data => {
                let html = '<option value="">Seleccione</option>';
                data.forEach(e => html += `<option value="${e.id}">${e.nombre}</option>`);
                document.querySelector('#provincia').innerHTML = html;

                document.querySelector('#distrito').innerHTML = '<option value="">Seleccione</option>';
            });
    });

    // ----- PROVINCIAS → DISTRITOS -----
    document.querySelector('#provincia').addEventListener('change', function () {
        let id = this.value;

        fetch(`/ubicacion/distritos/${id}`)
            .then(res => res.json())
            .then(data => {
                let html = '<option value="">Seleccione</option>';
                data.forEach(e => html += `<option value="${e.id}">${e.nombre}</option>`);
                document.querySelector('#distrito').innerHTML = html;
            });
    });

    // ----- CATEGORÍA → SUBCATEGORÍAS -----
    document.querySelector('#categoria').addEventListener('change', function () {
        let id = this.value;

        fetch(`/categorias/subcategorias/${id}`)
            .then(res => res.json())
            .then(data => {
                let html = '<option value="">Seleccione</option>';
                data.forEach(e => html += `<option value="${e.id}">${e.nombre}</option>`);
                document.querySelector('#subcategoria').innerHTML = html;
            });
    });

});
