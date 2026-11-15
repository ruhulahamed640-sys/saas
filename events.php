<?php
declare(strict_types=1);
$pageTitle = 'Birthday & Events';
$activeNav = 'events';
$canonicalPath = 'events.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="editorial-section">
  <div class="editorial-container">
    <p class="meta-label">PAGE I / BIRTHDAY &amp; CELEBRATION ARCHIVE</p>
    <h1 class="display-lg" style="margin-block: var(--space-3);">November 23 &amp; Milestone Gatherings</h1>
    <div class="surface-card" style="margin-block: var(--space-6);">
      <p class="meta-label">UPCOMING MILESTONE · NOVEMBER 23, 2026 (BORN NOVEMBER 23, 2006)</p>
      <h2 class="h2" style="margin-block: var(--space-2);">Twentieth Birthday Countdown &amp; Archive</h2>
      <p style="color: var(--text-secondary);">Tradition: Dark chocolate cake decorated with white frosting and dark chocolate drizzle, shared with close friends and family.</p>
    </div>
    <div class="editorial-grid">
      <?php foreach ($archive['events'] as $ev): ?>
        <article class="surface-card" style="grid-column: span 4;">
          <p class="meta-label"><?= htmlspecialchars($ev['event_date']) ?> · <?= strtoupper(htmlspecialchars($ev['event_type'])) ?></p>
          <h3 class="h4" style="margin-block: var(--space-2);"><?= htmlspecialchars($ev['title']) ?></h3>
          <p style="color: var(--text-secondary); font-size: var(--type-small);"><?= htmlspecialchars($ev['description']) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
