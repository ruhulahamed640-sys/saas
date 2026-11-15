/**
 * AHAMED DRAFTED — Persistent Editorial Light/Dark Theme Controller
 */
(function initThemeController() {
  const STORAGE_KEY = 'ahamed_drafted_theme';
  const root = document.documentElement;

  function getInitialTheme() {
    try {
      const saved = localStorage.getItem(STORAGE_KEY);
      if (saved === 'dark' || saved === 'light') return saved;
    } catch (_) {}
    return 'light'; // Strict requirement: Light mode default (#F6F5F1 Paper)
  }

  function applyTheme(theme) {
    root.setAttribute('data-theme', theme);
    try { localStorage.setItem(STORAGE_KEY, theme); } catch (_) {}
    document.querySelectorAll('[data-theme-toggle]').forEach(btn => {
      btn.setAttribute('aria-label', `Switch to ${theme === 'dark' ? 'light' : 'dark'} mode`);
      const labelEl = btn.querySelector('[data-theme-label]');
      if (labelEl) labelEl.textContent = theme === 'dark' ? 'Paper Light' : 'Deep Ink';
    });
  }

  applyTheme(getInitialTheme());

  document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-theme-toggle]');
    if (!btn) return;
    const next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    applyTheme(next);
  });
})();
