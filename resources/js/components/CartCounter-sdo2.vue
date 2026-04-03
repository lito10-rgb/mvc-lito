<template>
  <a href="/carrito" class="nav-link position-relative text-warning">
    <i class="bi bi-cart4 fs-4"></i>
    <span
      class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
      v-text="count"
    ></span>
  </a>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import axios from "axios";

const count = ref(0);

async function fetchCount() {
  try {
    const { data } = await axios.get("carrito/count");
    console.log("Respuesta del backend:", data);
    count.value = data.count ?? 0;
  } catch (error) {
    console.error("Error obteniendo contador del carrito:", error.message);
    if (error.response) {
      console.error("Status:", error.response.status);
      console.error("Data:", error.response.data);
    }
  }
}

onMounted(() => {
  fetchCount();
  window.addEventListener("carrito-actualizado", fetchCount);
});

onBeforeUnmount(() => {
  window.removeEventListener("carrito-actualizado", fetchCount);
});
</script>
