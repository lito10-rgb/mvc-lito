// resources/js/carrito-actions.js
// Encargado de capturar clicks en botones "Añadir al carrito",
// hacer la petición POST y disparar el evento global 'carrito-actualizado'.

// Usa fetch nativo (no requiere axios)
document.addEventListener('DOMContentLoaded', () => {
  const tokenMeta = document.querySelector('meta[name="csrf-token"]');
  const csrfToken = tokenMeta ? tokenMeta.getAttribute('content') : '';

  // Helper para construir URL si tu app corre en subcarpeta
  function baseUrl(path = '') {
    if (window.BASE_URL) {
      return window.BASE_URL.replace(/\/$/, '') + '/' + path.replace(/^\//, '');
    }
    return '/' + path.replace(/^\//, '');
  }

  // Selecciona botones con la clase .btn-agregar-carrito
  document.querySelectorAll('.btn-agregar-carrito').forEach(btn => {
    btn.addEventListener('click', async (e) => {
      e.preventDefault();

      // data-url debe contener la URL para POST (route('carrito.agregar', $id))
      const url = btn.dataset.url || baseUrl(`carrito/agregar/${btn.dataset.productId}`);
      const qty = parseInt(btn.dataset.qty || 1, 10) || 1;

      // UI: desactivar botón mientras esté la petición
      btn.disabled = true;
      btn.classList.add('opacity-75');

      try {
        const resp = await fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          },
          body: JSON.stringify({ cantidad: qty })
        });

        // Si respuesta OK y JSON con count -> despachar evento con detalle
        if (resp.ok) {
          const data = await resp.json().catch(()=>null);
          const count = data?.count ?? null;

          // Dispatch: evento global. Incluimos count en detail si existe.
          const event = new CustomEvent('carrito-actualizado', {
            detail: { count }
          });
          window.dispatchEvent(event);

          // Opcional: small visual feedback (puedes editar según tu UI)
          btn.classList.add('btn-success');
          setTimeout(()=> btn.classList.remove('btn-success'), 500);
        } else {
          // manejar error: mostrar mensaje simple
          const err = await resp.json().catch(()=>null);
          console.error('Error agregando al carrito:', err);
          alert(err?.message ?? 'No se pudo agregar al carrito. Intenta de nuevo.');
        }
      } catch (error) {
        console.error('Network error:', error);
        alert('Error de red. Intenta de nuevo.');
      } finally {
        btn.disabled = false;
        btn.classList.remove('opacity-75');
      }
    });
  });
});
