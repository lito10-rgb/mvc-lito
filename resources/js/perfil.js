document.addEventListener("DOMContentLoaded", function () {

    // Detecta automáticamente la URL base correcta
    const base = window.location.origin + "/mvc-lito/public";

    // --- SELECTS UBICACIÓN ---
    const paisSelect = document.getElementById("pais");
    const estadoSelect = document.getElementById("estado");
    const provinciaSelect = document.getElementById("provincia");
    const distritoSelect = document.getElementById("distrito");

    // --- SELECTS CATEGORÍA ---
    const categoriaEl = document.getElementById('categoria');
    const subcategoriaEl = document.getElementById('subcategoria');

    // --- FUNCIONES AUXILIARES UNIVERSALES ---
    function limpiar(el, placeholder = "Seleccione") {
        el.innerHTML = `<option value="">${placeholder}</option>`;
    }

    function llenarSubcategorias(el, data) {
        limpiar(el);
        data.forEach(item => {
            el.insertAdjacentHTML(
                'beforeend',
                `<option value="${item.id}">${item.subcategoria}</option>`
            );
        });
    }

    function cargarOpciones(url, selectDestino) {
        console.log("Llamando a:", url);

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Respuesta no OK: " + response.status);
                }
                return response.json();
            })
            .then(data => {
                limpiar(selectDestino);
                data.forEach(item => {
                    selectDestino.innerHTML +=
                        `<option value="${item.id}">${item.nombre}</option>`;
                });
            })
            .catch(error => console.error("Error cargando datos:", error));
    }

    // ---------------------------------------------------------------
    // 🔥 SISTEMA DE CATEGORÍAS → SUBCATEGORÍAS
    // ---------------------------------------------------------------

    categoriaEl?.addEventListener('change', async function () {
        const id = this.value;
        limpiar(subcategoriaEl);

        if (!id) return;

        try {
            const res = await fetch(`${base}/categoria/${id}/subcategorias`);
            const data = await res.json();
            llenarSubcategorias(subcategoriaEl, data);

            // Autoselect si ya existe valor anterior
            const sel = subcategoriaEl.getAttribute('data-selected');
            if (sel) subcategoriaEl.value = sel;

        } catch (error) {
            console.error('Error cargando subcategorías', error);
        }
    });

    // Auto carga si el usuario ya tiene categoría guardada
    (function autoloadCategorias() {
        const selectedCat = categoriaEl?.getAttribute('data-selected');
        if (selectedCat) {
            categoriaEl.value = selectedCat;
            categoriaEl.dispatchEvent(new Event('change'));
        }
    })();

    // ---------------------------------------------------------------
    // 🔥 SISTEMA UBICACIÓN: País → Estado → Provincia → Distrito
    // ---------------------------------------------------------------

    paisSelect?.addEventListener("change", function () {
        const paisID = this.value;

        limpiar(estadoSelect);
        limpiar(provinciaSelect);
        limpiar(distritoSelect);

        if (paisID) {
            cargarOpciones(`${base}/ubicacion/estados/${paisID}`, estadoSelect);
        }
    });

    estadoSelect?.addEventListener("change", function () {
        const estadoID = this.value;

        limpiar(provinciaSelect);
        limpiar(distritoSelect);

        if (estadoID) {
            cargarOpciones(`${base}/ubicacion/provincias/${estadoID}`, provinciaSelect);
        }
    });

    provinciaSelect?.addEventListener("change", function () {
        const provinciaID = this.value;

        limpiar(distritoSelect);

        if (provinciaID) {
            cargarOpciones(`${base}/ubicacion/distritos/${provinciaID}`, distritoSelect);
        }
    });

});
