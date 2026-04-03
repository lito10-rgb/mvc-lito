<template>
  <button 
    class="btn btn-primary"
    :disabled="loading"
    @click="addToCart"
  >
    <span v-if="loading">Agregando...</span>
    <span v-else>🛒 Agregar al carrito</span>
  </button>
</template>

<script setup>
import { ref } from 'vue'

// Recibe el ID del producto como propiedad
const props = defineProps({
  productId: {
    type: [String, Number],
    required: true
  },
  qty: {
    type: Number,
    default: 1
  }
})

const loading = ref(false)

async function addToCart() {
  loading.value = true
  try {
    const res = await axios.post('/cart/add', {
      product_id: props.productId,
      qty: props.qty
    })

    if (res && res.data && res.data.success) {
      // Dispara evento global para que CartCounter.vue se actualice
      const newCount = res.data.count ?? null
      window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: newCount } }))
    } else {
      console.warn('Respuesta inesperada:', res.data)
      // Igual notificamos el evento para que actualice por si acaso
      window.dispatchEvent(new CustomEvent('cart-updated'))
    }
  } catch (error) {
    console.error('Error al agregar al carrito:', error)
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.btn {
  font-weight: 500;
  border-radius: 8px;
  transition: background 0.3s ease;
}
.btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}
</style>
