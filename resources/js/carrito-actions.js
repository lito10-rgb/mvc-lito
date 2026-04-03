// resources/js/carrito-actions.js
// Parche robusto + logging para agregar al carrito (soporta HTML estático y URLs relativas)

document.addEventListener('DOMContentLoaded', () => {
  const tokenMeta = document.querySelector('meta[name="csrf-token"]');
  const csrfToken = tokenMeta ? tokenMeta.getAttribute('content') : '';
  if (!csrfToken) console.warn('CSRF token no encontrado en meta[name="csrf-token"]');

  // Determinar base URL de la app
  // 1) si se define window.BASE_URL (opcional), usarla
  // 2) si existe <meta name="app-base" content="..."> en layout blade usarla
  // 3) fallback: calcular desde window.location.pathname (quita el segmento final si es archivo)
  function getAppBase() {
    if (window.BASE_URL) return window.BASE_URL.replace(/\/+$/, '');
    const metaBase = document.querySelector('meta[name="app-base"]');
    if (metaBase && metaBase.getAttribute('content')) return metaBase.getAttribute('content').replace(/\/+$/, '');
    // fallback: usar pathname hasta /mvc-lito/public (o al folder donde corre la app)
    // ejemplo: pathname = /mvc-lito/public/alguna/ruta  -> queremos /mvc-lito/public
    const p = window.location.pathname.replace(/\/+$/, '');
    // si la última parte tiene extensión o es file, quitarla; si no, tomar la primera 2 segmentos
    const segments = p.split('/');
    if (segments.length > 2) {
      // intenta preservar /project/public cuando exista 'public' como segmento
      const pubIndex = segments.indexOf('public');
      if (pubIndex > 0) return window.location.origin + '/' + segments.slice(1, pubIndex + 1).join('/');
      // sino, asume que la app corre en dos primeros segmentos (ajusta si tu estructura es distinta)
      return window.location.origin + '/' + segments.slice(1, 2 + 0).join('/'); // keep one segment fallback
    }
    return window.location.origin;
  }

  const APP_BASE = getAppBase();
  console.log('[carrito-actions] APP_BASE =', APP_BASE);

  // helper para formar URL absoluta desde path relativo (sin leading slash)
  function absolutePath(path = '') {
    // path puede venir 'carrito/add' o '/carrito/add' -> normalizamos
    const clean = path.replace(/^\/+/, '');
    return APP_BASE.replace(/\/+$/, '') + '/' + clean;
  }

  function dispatchUpdate(count = null) {
    const ev = new CustomEvent('carrito-actualizado', { detail: { count } });
    window.dispatchEvent(ev);
    console.log('[carrito-actions] evento "carrito-actualizado" disparado, count=', count);
  }

  // buscar botones con varios selectores (más tolerante)
  const botones = document.querySelectorAll('[data-add-cart], .btn-agregar-carrito, .add-to-cart, [data-product-id]');
  console.log('[carrito-actions] botones encontrados:', botones.length);

  botones.forEach(btn => {
    // prevenimos doble attach
    if (btn.dataset.handlerAttached) return;
    btn.addEventListener('click', async (e) => {
      e.preventDefault();
      console.log('[carrito-actions] click detectado en botón', btn);

      const dataUrl = btn.dataset.url;
      const productId = btn.dataset.productId || btn.getAttribute('data-product-id');
      const qty = parseInt(btn.dataset.qty || 1, 10) || 1;

      let url;
      if (dataUrl) {
        // si es absoluta (empieza con http) la tomamos tal cual, si es relativa la convertimos
        url = (/^https?:\/\//i.test(dataUrl) ? dataUrl : absolutePath(dataUrl));
      } else if (productId) {
        // endpoint por defecto: carrito/add o carrito/agregar según tu backend
        // usamos 'carrito/add' como ejemplo; si tu ruta es 'carrito/agregar/{id}' ajusta aquí
        url = absolutePath(`carrito/add`);
      } else {
        console.error('[carrito-actions] ERROR: no se encontró URL (data-url) ni data-product-id en', btn);
        alert('Error: falta configuración en el botón (data-url o data-product-id).');
        return;
      }

      btn.disabled = true;
      btn.classList.add('opacity-75');

      try {
        console.log('[carrito-actions] POST ->', url, 'payload', { product_id: productId, cantidad: qty });

        const resp = await fetch(url, {
          method: 'POST',
          credentials: 'same-origin',          // enviar cookies de sesión
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          },
          body: JSON.stringify({ product_id: productId, cantidad: qty })
        });

        console.log('[carrito-actions] respuesta HTTP:', resp.status, resp.statusText);

        if (resp.ok) {
          // aseguramos que sea JSON válido
          const ct = resp.headers.get('content-type') || '';
          let json = null;
          if (ct.includes('application/json')) {
            json = await resp.json().catch(() => null);
          } else {
            // si no es JSON, intentar parsear texto para debug
            const txt = await resp.text().catch(()=>null);
            console.warn('[carrito-actions] respuesta no JSON:', txt);
          }
          console.log('[carrito-actions] body JSON:', json);

          const countFromResponse = json?.count ?? null;
          if (countFromResponse !== null) {
            dispatchUpdate(countFromResponse);
            return;
          }

          // fallback: solicitar carrito/count relativo
          try {
            const r2 = await fetch(absolutePath('carrito/count'), { credentials: 'same-origin', headers: { 'Accept': 'application/json' }});
            console.log('[carrito-actions] fallback /carrito/count HTTP:', r2.status);
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

        // respuesta no ok -> leer body JSON si existe para mostrar error
        let errJson = null;
        try { errJson = await resp.json(); } catch(e) { errJson = null; }
        console.error('[carrito-actions] respuesta no ok:', resp.status, errJson);
        alert(errJson?.message ?? 'No se pudo agregar al carrito. Revisa consola.');
      } catch (error) {
        console.error('[carrito-actions] EXCEPCION fetch:', error);
        alert('Error de red. Revisa la consola.');
      } finally {
        btn.disabled = false;
        btn.classList.remove('opacity-75');
        btn.dataset.handlerAttached = '1';
      }
    });
  });

});
