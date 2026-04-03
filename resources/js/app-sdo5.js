// resources/js/app.js
import { createApp } from 'vue';
import axios from 'axios';

import './carrito-actions';  
import './fallback-counter';
import './fallback-thumbs';          // script que hace POST y dispatch evento
import CartCounter from './components/CartCounter.vue';
import AddToCart from './components/AddToCart.vue';

// Exponer axios globalmente para scripts no-modulares (carrito-actions, etc.)
window.axios = axios;

// Configurar axios para incluir el CSRF token (usa el meta que tienes en head)
const tokenMeta = document.querySelector('meta[name="csrf-token"]');
if (tokenMeta) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = tokenMeta.getAttribute('content');
} else {
  console.error('CSRF token meta no encontrado en <head>');
}

axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// después de configurar axios y token...
const baseMeta = document.querySelector('meta[name="app-base"]');
if (baseMeta) {
  axios.defaults.baseURL = baseMeta.getAttribute('content').replace(/\/$/, '');
  console.log('[axios] baseURL =', axios.defaults.baseURL);
} else {
  // fallback: deducir base desde location (solo para dev)
  axios.defaults.baseURL = window.location.origin + (window.location.pathname.includes('/public') ? '' : '/mvc-lito/public');
  console.log('[axios] baseURL (fallback) =', axios.defaults.baseURL);
}

// Montaje de Vue en #app-vue
const el = document.getElementById('app-vue');
if (el) {
  const app = createApp({});

  // Registrar componentes (kebab-case en el HTML)
  app.component('cart-counter', CartCounter);
  app.component('add-to-cart', AddToCart);
  app.mount('#app-vue');
}
