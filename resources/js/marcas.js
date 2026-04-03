document.addEventListener('DOMContentLoaded', () => {
    const checkAll = document.querySelector('#checkAll');
    const btn = document.querySelector('#btnEliminarSeleccionados');

    const items = () => Array.from(document.querySelectorAll('.checkItem'));

    const update = () => {
        btn.disabled = !items().some(i => i.checked);
    };

    checkAll?.addEventListener('change', e => {
        items().forEach(ch => ch.checked = e.target.checked);
        update();
    });

    document.addEventListener('change', e => {
        if (e.target.classList.contains('checkItem')) update();
    });

    btn?.addEventListener('click', async e => {
        e.preventDefault();

        const ids = items().filter(i => i.checked).map(i => i.value);
        if (!ids.length) return;

        if (!confirm(`¿Eliminar ${ids.length} marcas?`)) return;

        const url = document.querySelector('meta[name="marcas-eliminar-url"]').content;
        const token = document.querySelector('meta[name="csrf-token"]').content;

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

            const data = await res.json();
            alert(data.message);
            location.reload();

        } catch (e) {
            console.error(e);
            alert("Error eliminando marcas");
        }
    });
});
