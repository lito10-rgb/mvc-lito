// resources/js/fallback-counter.js
// Fallback simple: escucha eventos custom y actualiza el contador del DOM
// Asegúrate que el elemento tenga id="cart-counter-fallback" o data-counter

(function(){
  // Busca element(s) que mostrarán el contador
  const el = document.querySelector('#cart-counter-fallback') || document.querySelector('[data-cart-counter]');

  function setCount(n) {
    if (!el) return;
    el.textContent = Number(n) || 0;
    // añade clase visible / animación si quieres
    el.classList.remove('hidden');
  }

  // escucha evento custom cuando carrito cambia
  window.addEventListener('cart:updated', (ev) => {
    const count = ev.detail && ev.detail.count !== undefined ? ev.detail.count : ev.detail;
    setCount(count);
  });

  // si el sitio dispara 'cart:counter:updated'
  window.addEventListener('cart:counter:updated', (ev) => {
    setCount(ev.detail && ev.detail.count !== undefined ? ev.detail.count : ev.detail);
  });

  // intento inicial: si existe una API global para obtener contador
  if (window.__INITIAL_CART_COUNT__ !== undefined) {
    setCount(window.__INITIAL_CART_COUNT__);
  }

})();
