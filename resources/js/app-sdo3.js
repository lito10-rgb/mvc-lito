// Si usas axios o bootstrap.js, puedes importarlos aquí.
// Importar SCSS también desde aquí funciona, pero ya lo cargamos con @vite arriba.
// import '../scss/app.scss';

// (OPCIONAL) Vue 3 — deja preparado el mount para futuro, pero sin fallar si no hay componentes:
import { createApp } from 'vue';
import axios from 'axios';
import CartCounter from './components/CartCounter.vue';
const el = document.getElementById('app-vue');
if (el) {
  const app = createApp({});
  // Si en el futuro registras componentes:
  // app.component('cart-widget', CartWidget);
  app.mount('#app-vue');
}
