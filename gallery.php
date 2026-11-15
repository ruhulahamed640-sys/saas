<?php
declare(strict_types=1);
$pageTitle = 'Photo Gallery';
$activeNav = 'gallery';
$canonicalPath = 'gallery.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="editorial-section">
  <div class="editorial-container">
    <p class="meta-label">PAGE E / PHOTOGRAPHIC ARCHIVE</p>
    <h1 class="display-lg" style="margin-block: var(--space-3);">Curated Albums &amp; Contact Sheets</h1>
    <div class="editorial-grid" style="margin-top: var(--space-6);">
      <?php foreach ($archive['albums'] as $album): ?>
        <article class="surface-card" style="grid-column: span 6;">
          <a href="album.php?slug=<?= urlencode($album['slug']) ?>">
            <img src="<?= htmlspecialchars($album['cover_image']) ?>" alt="<?= htmlspecialchars($album['title']) ?>" width="640" height="420" loading="lazy" style="margin-bottom: var(--space-4);">
          </a>
          <p class="meta-label"><?= htmlspecialchars($album['category']) ?> · <?= (int)$album['photo_count'] ?> PHOTOGRAPHS · <?= htmlspecialchars($album['created_date']) ?></p>
          <h2 class="h3" style="margin-block: var(--space-2);">
            <a href="album.php?slug=<?= urlencode($album['slug']) ?>"><?= htmlspecialchars($album['title']) ?></a>
          </h2>
          <p style="color: var(--text-secondary);"><?= htmlspecialchars($album['description']) ?></p>
          <a href="album.php?slug=<?= urlencode($album['slug']) ?>" class="meta-label">Open Album &amp; Lightbox →</a>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
