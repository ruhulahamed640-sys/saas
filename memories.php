<?php
declare(strict_types=1);
$pageTitle = 'Memories Feed';
$activeNav = 'memories';
$canonicalPath = 'memories.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="editorial-section">
  <div class="editorial-container">
    <p class="meta-label">PAGE G / CHRONOLOGICAL MEMORY ARCHIVE</p>
    <h1 class="display-lg" style="margin-block: var(--space-3);">Collected Moments (2024 – 2026)</h1>
    <div class="editorial-grid" style="margin-top: var(--space-6);">
      <?php foreach ($archive['memories'] as $mem): ?>
        <article class="surface-card" style="grid-column: span 6;">
          <img src="<?= htmlspecialchars($mem['photograph_url']) ?>" alt="<?= htmlspecialchars($mem['title']) ?>" width="600" height="400" loading="lazy" style="margin-bottom: var(--space-4);">
          <p class="meta-label"><?= htmlspecialchars($mem['date_label']) ?> · <?= htmlspecialchars($mem['category']) ?> · <?= htmlspecialchars($mem['location_approx']) ?></p>
          <h2 class="h3" style="margin-block: var(--space-2);"><?= htmlspecialchars($mem['title']) ?></h2>
          <p style="color: var(--text-secondary);"><?= htmlspecialchars($mem['description']) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
