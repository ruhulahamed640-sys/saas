<?php
declare(strict_types=1);
$pageTitle = 'Album Contact Sheet';
$activeNav = 'gallery';
$canonicalPath = 'album.php';
require_once __DIR__ . '/includes/header.php';
?>

<?php
$slug = $_GET['slug'] ?? $archive['albums'][0]['slug'];
$album = $archive['albums'][0];
foreach ($archive['albums'] as $cand) {
    if ($cand['slug'] === $slug) { $album = $cand; break; }
}
?>
<section class="editorial-section">
  <div class="editorial-container">
    <p class="meta-label"><a href="gallery.php">← ALL ALBUMS</a> · <?= htmlspecialchars($album['category']) ?> · <?= htmlspecialchars($album['location_approx']) ?></p>
    <h1 class="display-lg" style="margin-block: var(--space-3);"><?= htmlspecialchars($album['title']) ?></h1>
    <p style="max-width: 680px; color: var(--text-secondary); margin-bottom: var(--space-7);"><?= htmlspecialchars($album['editorial_intro']) ?></p>
    <div class="editorial-grid">
      <?php foreach ($archive['photographs'] as $photo): ?>
        <figure class="surface-card" style="grid-column: span 6;">
          <img src="<?= htmlspecialchars($photo['file_path']) ?>" alt="<?= htmlspecialchars($photo['alt_text']) ?>" width="640" height="480" loading="lazy">
          <figcaption style="margin-top: var(--space-3);">
            <p class="meta-label"><?= htmlspecialchars($photo['taken_date']) ?> · <?= htmlspecialchars($photo['camera_exif']) ?></p>
            <h2 class="h4" style="margin-block: var(--space-1);"><?= htmlspecialchars($photo['title']) ?></h2>
            <p style="color: var(--text-secondary); font-size: var(--type-small); margin-bottom:0;"><?= htmlspecialchars($photo['caption']) ?></p>
          </figcaption>
        </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
