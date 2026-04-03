import { createApp } from 'vue';
import axios from 'axios';
import './carrito-actions';            //  <-- importa el script que hace POST y dispatch evento
import CartCounter from './components/CartCounter.vue';
import AddToCart from './components/AddToCart.vue'

// Configurar axios para incluir el CSRF token (usa el meta que tienes en head)
const tokenMeta = document.querySelector('meta[name="csrf-token"]');
if (tokenMeta) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = tokenMeta.getAttribute('content');
}
axios.defaults.headers.common['Accept'] = 'application/json';

const el = document.getElementById('app-vue');
if (el) {
  const app = createApp({});
  // Registrar el componente y montarlo
  app.component('cart-counter', CartCounter);
  app.mount('#app-vue');
}
