<?php
declare(strict_types=1);
$pageTitle = 'Portfolio & Achievements';
$activeNav = 'portfolio';
$canonicalPath = 'portfolio.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="editorial-section">
  <div class="editorial-container">
    <p class="meta-label">PAGE K / SELECTED WORKS, SKILLS &amp; EDUCATION</p>
    <h1 class="display-lg" style="margin-block: var(--space-3);">Editorial &amp; Technical Portfolio</h1>
    <div class="editorial-grid" style="margin-block: var(--space-6);">
      <?php foreach ($archive['projects'] as $proj): ?>
        <article class="surface-card" style="grid-column: span 4;">
          <img src="<?= htmlspecialchars($proj['cover_image']) ?>" alt="<?= htmlspecialchars($proj['title']) ?>" width="450" height="300" loading="lazy" style="margin-bottom: var(--space-4);">
          <p class="meta-label"><?= htmlspecialchars($proj['category']) ?> · <?= htmlspecialchars($proj['year_label']) ?></p>
          <h2 class="h3" style="margin-block: var(--space-2);"><?= htmlspecialchars($proj['title']) ?></h2>
          <p style="color: var(--text-secondary); font-size: var(--type-small);"><?= htmlspecialchars($proj['summary']) ?></p>
          <p class="meta-label" style="margin-top: var(--space-3);">ROLE: <?= htmlspecialchars($proj['role']) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
