// resources/js/fallback-thumbs.js
// Script temporal: fallback para miniaturas o botones de productos

console.log('[fallback-thumbs] cargado correctamente');

// Puedes añadir aquí el código que maneje clicks en miniaturas o eventos de productos
// Ejemplo:
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-add-to-cart]').forEach(btn => {
    btn.addEventListener('click', e => {
      e.preventDefault();
      const id = btn.dataset.productId;
      if (!id) return;

      // Si tienes tu acción addToCart ya definida:
      if (window.addToCart) {
        window.addToCart(id);
      } else {
        console.warn('addToCart no está definido');
      }
    });
  });
});
