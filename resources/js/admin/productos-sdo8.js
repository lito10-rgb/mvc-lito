
document.addEventListener('DOMContentLoaded', () => {
  const checkAll = document.getElementById('checkAll');
  const btnEliminar = document.getElementById('btnEliminarSeleccionados');

  // helper para obtener items actuales
  const getItems = () => Array.from(document.querySelectorAll('.checkItem'));

  function actualizarBoton() {
    const alguno = getItems().some(i => i.checked);
    if (btnEliminar) btnEliminar.disabled = !alguno;
  }

  // Delegación: escucha cambios en elementos .checkItem
  document.addEventListener('change', (e) => {
    if (e.target && e.target.matches('.checkItem')) {
      const items = getItems();
      if (!e.target.checked && checkAll) checkAll.checked = false;
      if (items.length && checkAll) checkAll.checked = items.every(i => i.checked);
      actualizarBoton();
    }
  });

  // Seleccionar / deseleccionar todos
  if (checkAll) {
    checkAll.addEventListener('change', () => {
      const checked = checkAll.checked;
      getItems().forEach(ch => ch.checked = checked);
      actualizarBoton();
    });
  }

  // Click en botón eliminar
  if (btnEliminar) {
    btnEliminar.addEventListener('click', async (ev) => {
      ev.preventDefault();

      // Obtén los ids AQUÍ, dentro del handler
      const ids = getItems().filter(i => i.checked).map(i => i.value);

      console.log('IDS seleccionados (en el handler):', ids);

      if (!ids.length) return alert('No hay productos seleccionados.');

      if (!confirm(`¿Eliminar ${ids.length} productos seleccionados?`)) return;

      try {
        // Asegúrate de tener <meta name="csrf-token" content="{{ csrf_token() }}">
        const token = document.querySelector('meta[name="csrf-token"]')?.content || '';

        btnEliminar.disabled = true; // prevenir dobles clicks
        btnEliminar.innerHTML = 'Eliminando...';

        const res = await fetch('/admin/productos/eliminar-multiple', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
          },
          body: JSON.stringify({ ids })
        });

        if (!res.ok) {
          const text = await res.text();
          console.error('Respuesta no OK:', res.status, text);
          alert('Error en el servidor. Revisa la consola.');
          return;
        }

        const data = await res.json();
        console.log('Respuesta del servidor:', data);

        alert(data.message || 'Productos eliminados correctamente');
        window.location.reload();

      } catch (err) {
        console.error('Error en fetch:', err);
        alert('Error en la petición. Revisa la consola.');
      } finally {
        // restaurar texto si no recargó
        if (btnEliminar) {
          btnEliminar.disabled = false;
          btnEliminar.innerHTML = '<i class="bi bi-trash"></i> Eliminar seleccionados';
        }
      }
    });
  }

  // Inicial
  actualizarBoton();
});
