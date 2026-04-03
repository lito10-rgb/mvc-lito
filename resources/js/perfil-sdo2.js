document.addEventListener("DOMContentLoaded", function () {

    const paisSelect = document.getElementById("pais");
    const estadoSelect = document.getElementById("estado");
    const provinciaSelect = document.getElementById("provincia");
    const distritoSelect = document.getElementById("distrito");

    // Debug simple
    function log(msg) {
        console.log("DEBUG UBICACIÓN → " + msg);
    }

    function limpiar(select, placeholder = "Seleccione") {
        log(`LIMPIAR select #${select.id}`);
        select.innerHTML = `<option value="">${placeholder}</option>`;
    }

    function cargarOpciones(url, selectDestino) {
        log(`CARGANDO: ${url}`);

        fetch(url)
            .then(response => {
                log(`RESPUESTA status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                log(`DATOS RECIBIDOS (${selectDestino.id}): `);
                console.log(data);

                limpiar(selectDestino);

                if (!Array.isArray(data) || data.length === 0) {
                    log(`⚠ SIN DATOS PARA ${selectDestino.id}`);
                    return;
                }

                data.forEach(item => {
                    selectDestino.innerHTML += 
                        `<option value="${item.id}">${item.nombre}</option>`;
                });

                log(`✔ OPCIONES CARGADAS EN #${selectDestino.id}`);
            })
            .catch(error => {
                log("❌ ERROR FETCH:");
                console.error(error);
            });
    }

    // EVENTOS
    paisSelect?.addEventListener("change", function () {
        log(`PAÍS seleccionado: ${this.value}`);

        limpiar(estadoSelect);
        limpiar(provinciaSelect);
        limpiar(distritoSelect);

        if (this.value) {
            cargarOpciones(`/ubicacion/estados/${this.value}`, estadoSelect);
        }
    });

    estadoSelect?.addEventListener("change", function () {
        log(`ESTADO seleccionado: ${this.value}`);

        limpiar(provinciaSelect);
        limpiar(distritoSelect);

        if (this.value) {
            cargarOpciones(`/ubicacion/provincias/${this.value}`, provinciaSelect);
        }
    });

    provinciaSelect?.addEventListener("change", function () {
        log(`PROVINCIA seleccionada: ${this.value}`);

        limpiar(distritoSelect);

        if (this.value) {
            cargarOpciones(`/ubicacion/distritos/${this.value}`, distritoSelect);
        }
    });
});
