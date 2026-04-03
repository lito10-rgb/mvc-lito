<template>
  <a :href="carritoUrl" class="nav-link position-relative text-warning">
    <i class="bi bi-cart4 fs-4"></i>
    <span
      class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
      v-text="count"
    ></span>
  </a>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const count = ref(0);

// 🚀 Nuevo: obtenemos la URL base desde una meta tag
const carritoUrl = document.querySelector('meta[name="carrito-url"]')?.content || '/carrito';

async function fetchCount() {
  try {
    const { data } = await axios.get("/carrito/count");
    count.value = data.count;
  } catch (error) {
    console.error("Error obteniendo contador del carrito:", error);
  }
}

onMounted(() => {
  fetchCount();
  window.addEventListener("carrito-actualizado", fetchCount);
});
</script>
