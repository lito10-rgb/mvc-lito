document.addEventListener('DOMContentLoaded', () => {

    const checkAll = document.querySelector('#checkAll');
    const btn = document.querySelector('#btnEliminarSeleccionados');

    // Obtener todos los checkboxes que representan cada fila
    const items = () => Array.from(document.querySelectorAll('.checkItem'));

    // Habilitar o deshabilitar botón según selección
    const updateButton = () => {
        btn.disabled = !items().some(i => i.checked);
    };

    // Seleccionar/deseleccionar todos
    checkAll?.addEventListener('change', e => {
        items().forEach(ch => ch.checked = e.target.checked);
        updateButton();
    });

    // Evento para cada checkbox individual
    document.addEventListener('change', e => {
        if (e.target.classList.contains('checkItem')) {
            // Si se desmarca uno, desmarca el "Check All"
            if (!e.target.checked) checkAll.checked = false;

            // Si todos están marcados, activa checkAll
            if (items().every(i => i.checked)) checkAll.checked = true;

            updateButton();
        }
    });

    // Botón de eliminar seleccionados
    btn?.addEventListener('click', async (e) => {
        e.preventDefault();

        const ids = items().filter(i => i.checked).map(i => i.value);
        if (!ids.length) return alert('No hay subcategorías seleccionadas.');

        if (!confirm(`¿Eliminar ${ids.length} subcategorías?`)) return;

        const url   = document.querySelector('meta[name="subcategorias-eliminar-url"]').content;
        const token = document.querySelector('meta[name="csrf-token"]').content;

        btn.disabled = true;
        btn.innerHTML = 'Eliminando...';

        try {
            const res = await fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token,
                    "Accept": "application/json",
                },
                body: JSON.stringify({ ids })
            });

            if (!res.ok) {
                console.error(await res.text());
                alert("Error eliminando subcategorías.");
                return;
            }

            const data = await res.json();
            alert(data.message || 'Operación realizada correctamente.');
            location.reload();

        } catch (error) {
            console.error(error);
            alert("Error en la petición. Revisa la consola.");

        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-trash"></i> Eliminar seleccionados';
        }
    });

    updateButton();
});
