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
import { ref, onMounted } from "vue";
import axios from "axios";

const count = ref(0);

// Función para obtener el número de ítems del carrito
async function fetchCount() {
  try {
    //const { data } = await axios.get("/carrito/contador");
    const { data } = await axios.get("/carrito/count");
    count.value = data.count;
  } catch (error) {
    console.error("Error obteniendo contador del carrito:", error);
  }
}

// Al montar el componente, traer el contador actual
onMounted(() => {
  fetchCount();

  // Escuchar evento global para actualizar cuando se agregue un producto
  window.addEventListener("carrito-actualizado", fetchCount);
});
</script>
