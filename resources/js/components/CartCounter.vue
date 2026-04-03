<template>
  <a :href="carritoUrl" class="nav-link position-relative text-warning" title="Ver carrito">
    <i class="bi bi-cart3 fs-4"></i>
    <span
      class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
      v-text="count"
    ></span>
  </a>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import axios from 'axios';

const count = ref(0);

// Construir carritoUrl y url para obtener el count de forma robusta
function getCarritoUrl() {
  // 1) meta carrito-url inyectada por Blade (preferida)
  const metaCarrito = document.querySelector('meta[name="carrito-url"]')?.getAttribute('content');
  if (metaCarrito) return metaCarrito.replace(/\/$/, '');

  // 2) si existe window.APP_BASE (inyectado en Blade o calculado en app.js)
  if (window.APP_BASE) return (window.APP_BASE + '/carrito').replace(/\/+$/, '');

  // 3) si axios tiene baseURL usable
  if (axios?.defaults?.baseURL) return axios.defaults.baseURL.replace(/\/$/, '') + '/carrito';

  // 4) fallback relativo al origin + posible subcarpeta (ajusta si tu app corre en /mvc-lito/public)
  const origin = window.location.origin.replace(/\/$/, '');
  const maybePublic = window.location.pathname.includes('/public') ? '' : '/mvc-lito/public';
  return (origin + maybePublic + '/carrito').replace(/\/+/, '/');
}

function getCountEndpoint() {
  // similar a arriba pero apuntando a /carrito/count
  const carritoBase = getCarritoUrl();
  return carritoBase + '/count';
}

const carritoUrl = getCarritoUrl();
const countEndpoint = getCountEndpoint();

console.log('[CartCounter] carritoUrl =', carritoUrl);
console.log('[CartCounter] countEndpoint =', countEndpoint);
console.log('[CartCounter] axios.baseURL =', axios?.defaults?.baseURL);

// intenta obtener contador con manejo detallado de errores
async function fetchCount() {
  try {
    console.log('[CartCounter] fetchCount ->', countEndpoint);

    // usamos fetch directo (podemos usar axios también, pero fetch da más info a veces)
    const resp = await fetch(countEndpoint, {
      method: 'GET',
      credentials: 'same-origin',
      headers: { 'Accept': 'application/json' }
    });

    console.log('[CartCounter] HTTP status', resp.status);

    // si recibimos JSON con count
    if (resp.ok) {
      const data = await resp.json().catch(() => null);
      console.log('[CartCounter] body', data);
      count.value = Number(data?.count ?? 0);
      return;
    }

    // si no ok, intentar leer body para debug
    const text = await resp.text().catch(()=>null);
    console.warn('[CartCounter] fetchCount no ok. status=', resp.status, 'body=', text);

    // si es 401/419 o HTML de login => probablemente middleware de auth
    if ([401, 419].includes(resp.status) || (typeof text === 'string' && text.includes('<form') && text.toLowerCase().includes('login'))) {
      console.warn('[CartCounter] posible redirección a login o falta de sesión/csrf. Status', resp.status);
      count.value = 0;
      return;
    }

    // si 404 -> la ruta no existe en ese origin
    if (resp.status === 404) {
      console.error('[CartCounter] Ruta no encontrada (404). Verifica que exista Route GET /carrito/count en el backend para este origin.');
      count.value = 0;
      return;
    }

    // fallback
    count.value = 0;
  } catch (err) {
    console.error('[CartCounter] Error obteniendo contador del carrito:', err);
    count.value = 0;
  }
}

// handler del evento global
function handleCarritoActualizado(e) {
  const newCount = e?.detail?.count;
  if (typeof newCount !== 'undefined' && newCount !== null) {
    count.value = Number(newCount);
  } else {
    // si el evento no tiene count, hacemos fetch
    fetchCount();
  }
}

onMounted(() => {
  fetchCount();
  window.addEventListener('carrito-actualizado', handleCarritoActualizado);
});

onBeforeUnmount(() => {
  window.removeEventListener('carrito-actualizado', handleCarritoActualizado);
});
</script>

<style scoped>
/* si quieres, ajusta estilo aquí */
</style>
