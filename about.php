<?php
declare(strict_types=1);
$pageTitle = 'About Me';
$activeNav = 'about';
$canonicalPath = 'about.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="editorial-section">
  <div class="editorial-container editorial-grid">
    <div style="grid-column: span 5;">
      <img src="<?= htmlspecialchars($profile['profile_image']) ?>" alt="Ruhul Ahamed Portrait" width="600" height="800">
      <p class="meta-label" style="margin-top: var(--space-3);">BORN NOVEMBER 23, 2006 · COOCH BEHAR, WEST BENGAL</p>
    </div>
    <div style="grid-column: span 7;">
      <p class="meta-label">PAGE A / BIOGRAPHY &amp; PHILOSOPHY</p>
      <h1 class="display-lg" style="margin-block: var(--space-3);"><?= htmlspecialchars($profile['display_name']) ?></h1>
      <blockquote class="pull-quote">“<?= htmlspecialchars($profile['philosophy_quote']) ?>”</blockquote>
      <?php foreach (explode("\n\n", $profile['full_biography']) as $para): ?>
        <p><?= htmlspecialchars($para) ?></p>
      <?php endforeach; ?>
      <h2 class="h3" style="margin-top: var(--space-7); margin-bottom: var(--space-4);">Education Journey</h2>
      <?php foreach ($archive['education'] as $edu): ?>
        <div class="surface-card" style="margin-bottom: var(--space-4);">
          <p class="meta-label"><?= htmlspecialchars($edu['period_label']) ?> · <?= htmlspecialchars($edu['status_label']) ?></p>
          <h3 class="h4" style="margin-block: var(--space-1);"><?= htmlspecialchars($edu['institution']) ?></h3>
          <p style="color: var(--text-secondary);"><?= htmlspecialchars($edu['description']) ?></p>
        </div>
      <?php endforeach; ?>
      <h2 class="h3" style="margin-top: var(--space-7); margin-bottom: var(--space-4);">Favourite Things &amp; Daily Rituals</h2>
      <div class="editorial-grid">
        <?php foreach ($profile['favourite_things'] as $fav): ?>
          <div class="surface-card" style="grid-column: span 6;">
            <p class="meta-label"><?= htmlspecialchars($fav['label']) ?></p>
            <p style="margin-top: var(--space-2); margin-bottom:0;"><?= htmlspecialchars($fav['value']) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
