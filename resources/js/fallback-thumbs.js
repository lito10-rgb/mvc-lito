// resources/js/fallback-thumbs.js
(function(){
  // Selectores candidatos para miniaturas y selector para la imagen grande
  const THUMB_SELECTORS = '[data-thumb-src], [data-src], .thumb, .product-thumb, .gallery-thumb, .mySwiper .swiper-slide img';
  const MAIN_IMG_SELECTORS = '.product-main-image img, #product-main-img, .main-image img, .product-gallery-main img, .product-main-img, .mySwiper2 .swiper-slide-active img, .mySwiper2 .swiper-slide img';

  function findMainImage() {
    return document.querySelector(MAIN_IMG_SELECTORS);
  }

  function getSrcFromThumb(el) {
    if (!el) return null;
    return el.dataset.thumbSrc || el.dataset.src || el.getAttribute('href') || el.src || el.getAttribute('data-large') || null;
  }

  function swapMainImage(newSrc) {
    const main = findMainImage();
    if (!main) return console.warn('[fallback-thumbs] no se encontró imagen principal para swap');
    if (main.tagName.toLowerCase() === 'img') {
      main.src = newSrc;
    } else {
      main.style.backgroundImage = `url("${newSrc}")`;
    }
  }

  // Si existe Swiper principal, hacer slideTo(index) en lugar de reemplazar src
  function slideMainToIndex(index) {
    try {
      if (window.swiperMain && typeof window.swiperMain.slideTo === 'function') {
        window.swiperMain.slideTo(index);
        return true;
      }
    } catch (e) {
      console.warn('[fallback-thumbs] error al usar swiperMain.slideTo', e);
    }
    return false;
  }

  // Hover: agrandar via clase (CSS). Usamos throttling ligero para no saturar.
  let hoverTimer = null;
  function handleMouseOver(e) {
    const t = e.target.closest(THUMB_SELECTORS);
    if (!t) return;
    const src = getSrcFromThumb(t);
    if (!src) return;

    // Si la miniatura está dentro de un swiper .mySwiper, intentamos previsualizar la slide correspondiente
    const slideEl = t.closest('.mySwiper .swiper-slide');
    if (slideEl) {
      const slides = Array.from(slideEl.parentElement.children);
      const idx = slides.indexOf(slideEl);
      if (idx >= 0) {
        // si hay swiperMain mostramos la slide (previsual)
        if (slideMainToIndex(idx)) return;
      }
    }

    // fallback: swap pequeño para previsualizar al hover (para galerías no-swiper)
    if (hoverTimer) clearTimeout(hoverTimer);
    hoverTimer = setTimeout(() => swapMainImage(src), 80);
  }
  function handleMouseOut(e) {
    if (hoverTimer) { clearTimeout(hoverTimer); hoverTimer = null; }
    // opcional: podríamos volver a la slide original, pero mantenemos estado actual
  }

  // Click: swap permanentemente o mover swiper + opcional abrir modal cuando SHIFT o Ctrl presionado
  function handleClick(e) {
    const t = e.target.closest(THUMB_SELECTORS);
    if (!t) return;
    const src = getSrcFromThumb(t);
    if (!src) return;
    e.preventDefault();

    const slideEl = t.closest('.mySwiper .swiper-slide');
    if (slideEl) {
      const slides = Array.from(slideEl.parentElement.children);
      const idx = slides.indexOf(slideEl);
      if (idx >= 0) {
        // si hay swiperMain hacemos slideTo(index) y retornamos
        if (slideMainToIndex(idx)) {
          // si shift/ctrl -> abrir modal con imagen grande actual
          if (e.shiftKey || e.ctrlKey || e.metaKey) {
            openImageModal(src);
          }
          return;
        }
      }
    }

    // fallback: cambiar src directamente
    swapMainImage(src);

    // si usuario presiona shift o ctrl, abrir en modal (opcional)
    if (e.shiftKey || e.ctrlKey || e.metaKey) {
      openImageModal(src);
    }
  }

  // Modal ligero (crea DOM al vuelo)
  function openImageModal(src) {
    // si ya existe, sólo actualizar
    let modal = document.getElementById('fallback-image-modal');
    if (!modal) {
      modal = document.createElement('div');
      modal.id = 'fallback-image-modal';
      modal.style.position = 'fixed';
      modal.style.inset = '0';
      modal.style.background = 'rgba(0,0,0,0.75)';
      modal.style.display = 'flex';
      modal.style.alignItems = 'center';
      modal.style.justifyContent = 'center';
      modal.style.zIndex = '20000';
      modal.innerHTML = '<img style="max-width:90%; max-height:90%; box-shadow:0 6px 30px rgba(0,0,0,0.5); border-radius:8px;" src="" />';
      modal.addEventListener('click', () => { modal.remove(); });
      document.body.appendChild(modal);
    }
    const img = modal.querySelector('img');
    img.src = src;
  }

  // Delegación y observador para cambios dinamicos
  function attachDelegation() {
    document.removeEventListener('mouseover', handleMouseOver);
    document.removeEventListener('mouseout', handleMouseOut);
    document.removeEventListener('click', handleClick);

    document.addEventListener('mouseover', handleMouseOver, { capture: true });
    document.addEventListener('mouseout', handleMouseOut, { capture: true });
    document.addEventListener('click', handleClick, { capture: true });

    // observar DOM para logs/debug si es necesario
    console.log('[fallback-thumbs] delegación activa para selectores:', THUMB_SELECTORS);
  }

  // init: re-intentar varias veces si la galería se renderiza tarde
  function init() {
    attachDelegation();
    // también intentar swap inicial si hay thumb activo
    const initial = document.querySelector(THUMB_SELECTORS);
    if (initial) {
      const src = getSrcFromThumb(initial);
      if (src) {
        // si hay swiper, mostrar la slide 0 (o la correspondiente)
        const slideEl = initial.closest('.mySwiper .swiper-slide');
        if (slideEl) {
          const slides = Array.from(slideEl.parentElement.children);
          const idx = slides.indexOf(slideEl);
          if (idx >= 0) {
            slideMainToIndex(idx);
            return;
          }
        }
        swapMainImage(src);
      }
    }
  }

  // arrancar
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
})();
