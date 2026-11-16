(() => {
  const layer = document.querySelector('[data-cursor-orbits]');
  if (!layer) return;
  const rings = [...layer.querySelectorAll('.cursor-orbits__ring')];
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const finePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
  if (reduced || !finePointer) return;

  let targetX = 0, targetY = 0, currentX = 0, currentY = 0, raf = 0;
  const onMove = (event) => {
    targetX = (event.clientX / window.innerWidth - .5) * 2;
    targetY = (event.clientY / window.innerHeight - .5) * 2;
    if (!raf) raf = requestAnimationFrame(render);
  };
  function render() {
    currentX += (targetX - currentX) * .075;
    currentY += (targetY - currentY) * .075;
    rings.forEach((ring, index) => {
      const depth = (index + 1) * 8;
      const x = currentX * depth;
      const y = currentY * depth;
      const rotate = currentX * (index % 2 ? -2.2 : 2.2);
      ring.style.transform = `translate3d(${x}px, ${y}px, 0) rotate(${rotate}deg)`;
    });
    if (Math.abs(targetX-currentX) > .001 || Math.abs(targetY-currentY) > .001) {
      raf = requestAnimationFrame(render);
    } else raf = 0;
  }
  window.addEventListener('pointermove', onMove, { passive: true });
  window.addEventListener('blur', () => { targetX = 0; targetY = 0; }, { passive: true });
})();
