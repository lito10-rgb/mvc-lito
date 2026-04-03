// resources/js/app.js (versión con imports estáticos)
import { createApp } from 'vue';
import axios from 'axios';
import './carrito-actions';
import './fallback-counter';
import './fallback-thumbs';
import './admin/productos';
import CartCounter from './components/CartCounter.vue';
import AddToCart from './components/AddToCart.vue';

// Exponer axios globalmente para scripts no-modulares (carrito-actions, etc.)
window.axios = axios;

/* ---------------------------
   CSRF + headers básicos
   --------------------------- */
const tokenMeta = document.querySelector('meta[name="csrf-token"]');
if (tokenMeta) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = tokenMeta.getAttribute('content');
} else {
  console.error('CSRF token meta no encontrado en <head>');
}

axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/* ---------------------------------------------------
   Configuración segura de baseURL (compatible local/ngrok)
   --------------------------------------------------- */
(function configureBase() {
  const baseMeta = document.querySelector('meta[name="app-base"]');

  if (baseMeta) {
    try {
      const metaVal = baseMeta.getAttribute('content').replace(/\/$/, '');
      const metaOrigin = new URL(metaVal).origin;

      if (metaOrigin === window.location.origin) {
        axios.defaults.baseURL = metaVal;
        window.APP_BASE = metaVal;
        console.log('[axios] baseURL (meta -> same origin) =', axios.defaults.baseURL);
      } else {
        axios.defaults.baseURL = '';
        const pathParts = window.location.pathname.replace(/\/$/, '').split('/');
        let rootPath = '/';
        const pubIndex = pathParts.indexOf('public');
        if (pubIndex >= 1) {
          rootPath = pathParts.slice(0, pubIndex + 1).join('/');
        } else {
          rootPath = pathParts.length > 1 ? '/' + pathParts[1] : '/';
        }
        window.APP_BASE = (window.location.origin + rootPath).replace(/\/$/, '');
        console.log('[axios] baseURL not set (different origin). Using relative requests. APP_BASE =', window.APP_BASE);
      }
    } catch (e) {
      axios.defaults.baseURL = '';
      window.APP_BASE = window.location.origin;
      console.warn('[axios] baseURL fallback, error parsing meta:', e);
    }
  } else {
    axios.defaults.baseURL = '';
    window.APP_BASE = window.location.origin;
    console.log('[axios] baseURL not set (no meta). APP_BASE =', window.APP_BASE);
  }
})();

/* ---------------------------------------------------
   Montar Vue (registrar componentes y mount)
   --------------------------------------------------- */
const el = document.getElementById('app-vue');
if (el) {
  const app = createApp({});
  app.component('cart-counter', CartCounter);
  app.component('add-to-cart', AddToCart);
  app.mount('#app-vue');
  console.log('[app.js] Vue montado en #app-vue');
} else {
  console.warn('[app.js] No se encontró #app-vue en el DOM');
}
//////lito

document.addEventListener('DOMContentLoaded', function () {

    console.log('CheckAll script cargado'); // <-- prueba

    const checkAll = document.getElementById('checkAll');

    if (!checkAll) {
        console.warn('No existe #checkAll');
        return;
    }

    checkAll.addEventListener('change', function () {
        document.querySelectorAll('.checkItem')
            .forEach(ch => ch.checked = this.checked);
    });

});
