// resources/js/carrito-actions.js
// parche robusto + logging para depurar por qué no funciona el botón

document.addEventListener('DOMContentLoaded', () => {
  const tokenMeta = document.querySelector('meta[name="csrf-token"]');
  const csrfToken = tokenMeta ? tokenMeta.getAttribute('content') : '';
  if (!csrfToken) console.warn('CSRF token no encontrado en meta[name="csrf-token"]');

  function baseUrl(path = '') {
    if (window.BASE_URL) return window.BASE_URL.replace(/\/$/, '') + '/' + path.replace(/^\//, '');
    return '/' + path.replace(/^\//, '');
  }

  // helper para lanzar evento con detalle opcional
  function dispatchUpdate(count = null) {
    const ev = new CustomEvent('carrito-actualizado', { detail: { count } });
    window.dispatchEvent(ev);
    console.log('[carrito-actions] evento "carrito-actualizado" disparado, count=', count);
  }

  // attach listeners
  const botones = document.querySelectorAll('.btn-agregar-carrito');
  console.log('[carrito-actions] botones encontrados:', botones.length);

  botones.forEach(btn => {
    btn.addEventListener('click', async (e) => {
      e.preventDefault();
      console.log('[carrito-actions] click detectado en botón', btn);

      const url = btn.dataset.url || baseUrl(`carrito/agregar/${btn.dataset.productId}`);
      const qty = parseInt(btn.dataset.qty || 1, 10) || 1;

      if (!url) {
        console.error('[carrito-actions] ERROR: no se encontró URL (data-url o data-product-id faltante) en', btn);
        alert('Error: falta data-url en el botón.');
        return;
      }

      btn.disabled = true;
      btn.classList.add('opacity-75');

      try {
        console.log('[carrito-actions] POST ->', url, 'payload', { cantidad: qty });

        const resp = await fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          },
          body: JSON.stringify({ cantidad: qty })
        });

        console.log('[carrito-actions] respuesta HTTP:', resp.status, resp.statusText);

        // si la respuesta es JSON con count, lo usamos
        if (resp.ok) {
          const json = await resp.json().catch(() => null);
          console.log('[carrito-actions] body JSON:', json);

          const countFromResponse = json?.count ?? null;
          if (countFromResponse !== null) {
            dispatchUpdate(countFromResponse);
            return;
          }

          // fallback: solicitar /carrito/count si el servidor no devolvió count
          try {
            const r2 = await fetch(baseUrl('carrito/count'), { headers: { 'Accept': 'application/json' }});
            if (r2.ok) {
              const j2 = await r2.json();
              console.log('[carrito-actions] fallback /carrito/count ->', j2);
              dispatchUpdate(j2.count ?? null);
              return;
            } else {
              console.warn('[carrito-actions] fallback /carrito/count no OK', r2.status);
            }
          } catch (err) {
            console.error('[carrito-actions] error al pedir /carrito/count fallback', err);
          }

          // si todo falla, igual disparamos evento (para que el componente haga fetch)
          dispatchUpdate(null);
          return;
        }

        // no ok
        const errJson = await resp.json().catch(()=>null);
        console.error('[carrito-actions] respuesta no ok:', resp.status, errJson);
        alert(errJson?.message ?? 'No se pudo agregar al carrito. Revisa consola.');
      } catch (error) {
        console.error('[carrito-actions] EXCEPCION fetch:', error);
        alert('Error de red. Revisa la consola.');
      } finally {
        btn.disabled = false;
        btn.classList.remove('opacity-75');
      }
    });
  });
});
