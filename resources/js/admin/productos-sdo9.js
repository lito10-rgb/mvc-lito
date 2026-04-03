document.addEventListener('DOMContentLoaded', () => {
    const btnEliminar = document.getElementById('btnEliminarSeleccionados');

    if (!btnEliminar) return;

    btnEliminar.addEventListener('click', async (e) => {
        e.preventDefault();

        const checkboxes = Array.from(document.querySelectorAll('.checkItem'));
        const ids = checkboxes.filter(c => c.checked).map(c => c.value);

        if (!ids.length) return alert('No hay productos seleccionados.');
        if (!confirm(`Eliminar ${ids.length} productos?`)) return;

        const url = document.querySelector('meta[name="route-eliminar"]').content;
        const token = document.querySelector('meta[name="csrf-token"]').content;

        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ ids })
            });

            const data = await res.json();
            console.log('Respuesta del servidor:', data);

            alert(data.message || 'Operación finalizada.');
            window.location.reload();

        } catch (err) {
            console.error('Error en fetch:', err);
            alert('Error en la petición. Revisa la consola.');
        }
    });
});
