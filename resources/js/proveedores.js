document.addEventListener('DOMContentLoaded', () => {
  const url = document.querySelector('meta[name="proveedores-eliminar-url"]')?.content;
  if (!url) return;

  const btn = document.getElementById('btnEliminarProveedores');
  const checkAll = document.getElementById('checkAllProveedores');

  const getItems = () => Array.from(document.querySelectorAll('.checkProveedor'));

  const actualizarBoton = () => {
    if (!btn) return;
    btn.disabled = getItems().filter(i => i.checked).length === 0;
  };

  document.addEventListener('change', e => {
    if (e.target && e.target.matches('.checkProveedor')) {
      const items = getItems();
      if (!e.target.checked && checkAll) checkAll.checked = false;
      if (checkAll) checkAll.checked = items.every(i => i.checked);
      actualizarBoton();
    }
  });

  checkAll?.addEventListener('change', () => {
    const checked = checkAll.checked;
    getItems().forEach(i => i.checked = checked);
    actualizarBoton();
  });

  btn?.addEventListener('click', async () => {
    const ids = getItems().filter(i => i.checked).map(i => i.value);
    if (!ids.length) return alert('No hay proveedores seleccionados.');
    if (!confirm(`Eliminar ${ids.length} proveedores?`)) return;

    const token = document.querySelector('meta[name="csrf-token"]')?.content || '';

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

      if (!res.ok) {
        const text = await res.text();
        console.error('Error server:', res.status, text);
        alert('Error en el servidor');
        return;
      }

      const data = await res.json();
      alert(data.message || 'Operación finalizada');
      location.reload();

    } catch (err) {
      console.error('Fetch error:', err);
      alert('Error en la petición');
    }
  });

  actualizarBoton();
});
