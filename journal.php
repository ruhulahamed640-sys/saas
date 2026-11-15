<?php
declare(strict_types=1);
$pageTitle = 'Editorial Journal';
$activeNav = 'journal';
$canonicalPath = 'journal.php';
require_once __DIR__ . '/includes/header.php';
?>

<?php
$selectedCat = $_GET['category'] ?? 'all';
$searchQuery = trim($_GET['q'] ?? '');
$filteredPosts = array_filter($archive['posts'], function($p) use ($selectedCat, $searchQuery) {
    $catMatch = ($selectedCat === 'all' || $p['category_slug'] === $selectedCat);
    $qMatch   = ($searchQuery === '' || stripos($p['title'], $searchQuery) !== false || stripos($p['excerpt'], $searchQuery) !== false);
    return $catMatch && $qMatch;
});
?>
<section class="editorial-section">
  <div class="editorial-container">
    <p class="meta-label">PAGE C / THE EDITORIAL JOURNAL</p>
    <h1 class="display-lg" style="margin-block: var(--space-3);">Essays, Field Notes &amp; Dispatches</h1>
    <form method="get" action="journal.php" style="display:flex; gap:12px; flex-wrap:wrap; margin-block: var(--space-6);">
      <input type="search" name="q" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Search essays by title or topic..." style="padding:12px 16px; border:1px solid var(--border-color); background:var(--surface); color:var(--text-primary); min-width:280px;">
      <select name="category" style="padding:12px 16px; border:1px solid var(--border-color); background:var(--surface); color:var(--text-primary);">
        <option value="all">All Categories</option>
        <?php foreach ($archive['categories'] as $cat): ?>
          <option value="<?= htmlspecialchars($cat['slug']) ?>" <?= $selectedCat === $cat['slug'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="btn-editorial">Filter Journal</button>
    </form>
    <?php if (empty($filteredPosts)): ?>
      <div class="surface-card">
        <p class="meta-label">EMPTY STATE</p>
        <h2 class="h3" style="margin-block:var(--space-2);">No matching essays found</h2>
        <p style="color:var(--text-secondary);">Try clearing your search filter or selecting another editorial category.</p>
      </div>
    <?php else: ?>
      <div class="editorial-grid">
        <?php foreach ($filteredPosts as $post): ?>
          <article class="surface-card" style="grid-column: span 6;">
            <img src="<?= htmlspecialchars($post['featured_image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" width="600" height="360" loading="lazy" style="margin-bottom:var(--space-4);">
            <p class="meta-label"><?= htmlspecialchars($post['category']) ?> · <?= htmlspecialchars($post['date_formatted']) ?> · <?= (int)$post['reading_time_minutes'] ?> MIN READ</p>
            <h2 class="h3" style="margin-block:var(--space-2);">
              <a href="article.php?slug=<?= urlencode($post['slug']) ?>"><?= htmlspecialchars($post['title']) ?></a>
            </h2>
            <p style="color:var(--text-secondary);"><?= htmlspecialchars($post['excerpt']) ?></p>
            <a href="article.php?slug=<?= urlencode($post['slug']) ?>" class="meta-label">Read Article →</a>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
