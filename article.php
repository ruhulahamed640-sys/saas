<?php
declare(strict_types=1);
$pageTitle = 'Article Dispatch';
$activeNav = 'journal';
$canonicalPath = 'article.php';
require_once __DIR__ . '/includes/header.php';
?>

<?php
$slug = $_GET['slug'] ?? $archive['posts'][0]['slug'];
$article = $archive['posts'][0];
foreach ($archive['posts'] as $candidate) {
    if ($candidate['slug'] === $slug) { $article = $candidate; break; }
}
?>
<article class="editorial-section">
  <div class="editorial-container" style="max-width: var(--reading-width);">
    <p class="meta-label"><a href="journal.php">← BACK TO JOURNAL</a> · <?= htmlspecialchars($article['category']) ?> · <?= htmlspecialchars($article['date_formatted']) ?> · <?= (int)$article['reading_time_minutes'] ?> MIN READ</p>
    <h1 class="h1" style="margin-block: var(--space-4);"><?= htmlspecialchars($article['title']) ?></h1>
    <figure style="margin-block: var(--space-6);">
      <img src="<?= htmlspecialchars($article['featured_image']) ?>" alt="<?= htmlspecialchars($article['title']) ?>" width="900" height="520">
      <figcaption class="meta-label" style="margin-top: var(--space-2);"><?= htmlspecialchars($article['featured_caption'] ?? '') ?></figcaption>
    </figure>
    <blockquote class="pull-quote">“<?= htmlspecialchars($article['pull_quote'] ?? '') ?>”</blockquote>
    <div class="article-body">
      <?= $article['content'] ?>
    </div>
  </div>
</article>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
