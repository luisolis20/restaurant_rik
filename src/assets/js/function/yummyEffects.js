export function initYummyEffects() {
  "use strict";

  // --- Helper para selectores ---
  const select = (el, all = false) => {
    el = el.trim();
    if (all) return [...document.querySelectorAll(el)];
    return document.querySelector(el);
  };

  // --- Scrolled Header ---
  const toggleScrolled = () => {
    const selectBody = select('body');
    const selectHeader = select('#header');
    if (!selectHeader) return;
    if (!selectHeader.classList.contains('scroll-up-sticky') && !selectHeader.classList.contains('sticky-top') && !selectHeader.classList.contains('fixed-top')) return;
    window.scrollY > 100 ? selectBody.classList.add('scrolled') : selectBody.classList.remove('scrolled');
  };

  // --- Scroll Top ---
  const toggleScrollTop = () => {
    const scrollTop = select('.scroll-top');
    if (scrollTop) {
      window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
    }
  };

  // --- Navmenu Scrollspy ---
  const navmenuScrollspy = () => {
    const navmenulinks = select('.navmenu a', true);
    navmenulinks.forEach(navmenulink => {
      if (!navmenulink.hash) return;
      const section = select(navmenulink.hash);
      if (!section) return;
      let position = window.scrollY + 200;
      if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
        select('.navmenu a.active', true).forEach(link => link.classList.remove('active'));
        navmenulink.classList.add('active');
      } else {
        navmenulink.classList.remove('active');
      }
    });
  };

  // --- Event Listeners Globales ---
  document.addEventListener('scroll', () => {
    toggleScrolled();
    toggleScrollTop();
    navmenuScrollspy();
  });

  // --- Mobile Nav Toggle ---
  const mobileNavToggleBtn = select('.mobile-nav-toggle');
  if (mobileNavToggleBtn) {
    mobileNavToggleBtn.addEventListener('click', () => {
      select('body').classList.toggle('mobile-nav-active');
      mobileNavToggleBtn.classList.toggle('bi-list');
      mobileNavToggleBtn.classList.toggle('bi-x');
    });
  }

  // --- Animaciones AOS y librerías ---
  if (typeof AOS !== 'undefined') {
    AOS.init({ duration: 600, easing: 'ease-in-out', once: true });
  }

  if (typeof GLightbox !== 'undefined') {
    GLightbox({ selector: '.glightbox' });
  }

  if (typeof PureCounter !== 'undefined') {
    new PureCounter();
  }

  // --- Inicialización de Swiper ---
  if (typeof Swiper !== 'undefined') {
    select(".init-swiper", true).forEach(swiperElement => {
      let config = JSON.parse(swiperElement.querySelector(".swiper-config").innerHTML.trim());
      new Swiper(swiperElement, config);
    });
  }

  // Ejecución inicial
  toggleScrolled();
  toggleScrollTop();
  navmenuScrollspy();
}