document.addEventListener('DOMContentLoaded', () => {
    const checkAll = document.getElementById('checkAll');
    const btnEliminar = document.getElementById('btnEliminarSeleccionados');

    const getItems = () => Array.from(document.querySelectorAll('.checkItem'));

    const actualizarBoton = () => {
        btnEliminar.disabled = !getItems().some(i => i.checked);
    };

    // Seleccionar/deseleccionar individual
    document.addEventListener('change', e => {
        if (e.target.matches('.checkItem')) {
            const items = getItems();
            if (!e.target.checked) checkAll.checked = false;
            checkAll.checked = items.every(i => i.checked);
            actualizarBoton();
        }
    });

    // Seleccionar todos
    if (checkAll) {
        checkAll.addEventListener('change', () => {
            const checked = checkAll.checked;
            getItems().forEach(ch => ch.checked = checked);
            actualizarBoton();
        });
    }

    // Eliminar seleccionados
    if (btnEliminar) {
        btnEliminar.addEventListener('click', async () => {

            const ids = getItems().filter(i => i.checked).map(i => i.value);

            if (!ids.length) return alert("No seleccionaste nada.");
            if (!confirm("¿Eliminar los seleccionados?")) return;

            const url   = document.querySelector('meta[name=route-eliminar]').content;
            const token = document.querySelector('meta[name=csrf-token]').content;

            try {
                const res = await fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": token,
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({ ids })
                });

                if (!res.ok) {
                    console.error(await res.text());
                    alert("Error en el servidor.");
                    return;
                }

                const data = await res.json();
                alert(data.message);
                location.reload();

            } catch (e) {
                console.error(e);
                alert("Error en la petición.");
            }
        });
    }

    actualizarBoton();
});
