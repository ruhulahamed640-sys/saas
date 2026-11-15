<?php
declare(strict_types=1);
$pageTitle = 'Friends & Companions';
$activeNav = 'friends';
$canonicalPath = 'friends.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="editorial-section">
  <div class="editorial-container">
    <p class="meta-label">PAGE H / CONSENT-AWARE FRIENDSHIP DIRECTORY</p>
    <h1 class="display-lg" style="margin-block: var(--space-3);">People &amp; Shared Chapters</h1>
    <p style="color: var(--text-secondary); max-width: 640px; margin-bottom: var(--space-7);">
      Every profile displayed here is consent-approved and curated to celebrate shared rides, championship cricket nights, and university study sessions without exposing private contact details.
    </p>
    <div class="editorial-grid">
      <?php foreach ($archive['friends'] as $friend): ?>
        <article class="surface-card" style="grid-column: span 4;">
          <img src="<?= htmlspecialchars($friend['portrait_url']) ?>" alt="<?= htmlspecialchars($friend['display_name']) ?>" width="400" height="300" loading="lazy" style="margin-bottom: var(--space-4);">
          <p class="meta-label"><?= htmlspecialchars($friend['tier_label']) ?> · <?= htmlspecialchars($friend['known_since']) ?></p>
          <h2 class="h3" style="margin-block: var(--space-2);"><?= htmlspecialchars($friend['display_name']) ?></h2>
          <p style="color: var(--text-secondary); font-size: var(--type-small);"><?= htmlspecialchars($friend['description']) ?></p>
          <p class="meta-label" style="margin-top: var(--space-3); border-top: 1px solid var(--border-color); padding-top: var(--space-3);">
            MEMORY: <?= htmlspecialchars($friend['shared_memory']) ?>
          </p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
