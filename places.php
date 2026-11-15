<?php
declare(strict_types=1);
$pageTitle = 'Places & Travel';
$activeNav = 'places';
$canonicalPath = 'places.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="editorial-section">
  <div class="editorial-container">
    <p class="meta-label">PAGE J / GEOGRAPHY &amp; FIELD COORDINATES</p>
    <h1 class="display-lg" style="margin-block: var(--space-3);">Places Visited &amp; Lived In</h1>
    <p style="color: var(--text-secondary); max-width: 640px; margin-bottom: var(--space-7);">
      Displaying owner-approved approximate coordinates across West Bengal, Haryana, and Indian Railway corridors.
    </p>
    <div class="editorial-grid">
      <?php foreach ($archive['places'] as $place): ?>
        <article class="surface-card" style="grid-column: span 4;">
          <img src="<?= htmlspecialchars($place['photograph_url']) ?>" alt="<?= htmlspecialchars($place['place_name']) ?>" width="450" height="300" loading="lazy" style="margin-bottom: var(--space-4);">
          <p class="meta-label"><?= htmlspecialchars($place['region_state']) ?> · <?= htmlspecialchars($place['approx_coordinates']) ?></p>
          <h2 class="h3" style="margin-block: var(--space-2);"><?= htmlspecialchars($place['place_name']) ?></h2>
          <p class="meta-label" style="margin-bottom: var(--space-2);"><?= htmlspecialchars($place['visit_period']) ?></p>
          <p style="color: var(--text-secondary); font-size: var(--type-small);"><?= htmlspecialchars($place['full_notes']) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
