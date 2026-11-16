(() => {
  const toggle = document.querySelector('[data-menu-toggle]');
  const nav = document.getElementById('primary-navigation');
  if (!toggle || !nav) return;

  const closeMenu = () => {
    toggle.setAttribute('aria-expanded', 'false');
    nav.classList.remove('is-open');
  };
  const openMenu = () => {
    toggle.setAttribute('aria-expanded', 'true');
    nav.classList.add('is-open');
  };

  toggle.addEventListener('click', () => {
    const isOpen = toggle.getAttribute('aria-expanded') === 'true';
    if (isOpen) closeMenu();
    else openMenu();
  });

  nav.addEventListener('click', event => {
    if (event.target.closest('a')) closeMenu();
  });

  document.addEventListener('keydown', event => {
    if (event.key === 'Escape') {
      closeMenu();
      toggle.focus();
    }
  });

  document.addEventListener('click', event => {
    if (!nav.contains(event.target) && !toggle.contains(event.target)) closeMenu();
  });

  window.addEventListener('resize', () => {
    if (window.innerWidth > 1024) closeMenu();
  }, { passive: true });
})();
