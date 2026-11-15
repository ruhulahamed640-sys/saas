<?php
declare(strict_types=1);
$pageTitle = 'Personal Digital Journal, Editorial Portfolio & Life Archive';
$activeNav = 'home';
$canonicalPath = '';
require_once __DIR__ . '/includes/header.php';
$posts = $archive['posts'];
$featuredPost = $posts[0] ?? null;
?>
<section class="editorial-section hero-section">
  <div class="editorial-container hero-grid">
    <div>
      <p class="meta-label">PERSONAL DIGITAL JOURNAL · WEST BENGAL, INDIA</p>
      <h1 class="display-xl"><?= htmlspecialchars($profile['hero_headline'] ?: 'A Personal Archive of Ideas, Work & Everyday Life') ?></h1>
      <p class="hero-copy"><?= htmlspecialchars($profile['hero_supporting'] ?: $profile['short_bio']) ?></p>
      <div class="hero-actions">
        <a href="/journal.php" class="btn-editorial">Explore Journal</a>
        <a href="/about.php" class="btn-editorial btn-editorial--outline">About Me</a>
      </div>
    </div>
    <?php if (!empty($profile['profile_image'])): ?>
    <figure class="hero-image"><img src="<?= htmlspecialchars($profile['profile_image']) ?>" alt="Profile portrait" fetchpriority="high" width="720" height="900"></figure>
    <?php endif; ?>
  </div>
</section>

<section class="editorial-section">
  <div class="editorial-container">
    <p class="meta-label">01 / JOURNAL</p>
    <h2 class="h2 section-heading">Latest Writing</h2>
    <?php if ($featuredPost): ?>
      <div class="content-grid">
      <?php foreach (array_slice($posts, 0, 3) as $post): ?>
        <article class="surface-card content-card">
          <?php if (!empty($post['featured_image'])): ?><img src="<?= htmlspecialchars($post['featured_image']) ?>" alt="<?= htmlspecialchars($post['title'] ?? 'Journal image') ?>" loading="lazy" width="640" height="420"><?php endif; ?>
          <p class="meta-label"><?= htmlspecialchars($post['category'] ?? 'Journal') ?> · <?= htmlspecialchars($post['date_formatted'] ?? '') ?></p>
          <h3 class="h3"><a href="/article.php?slug=<?= urlencode($post['slug'] ?? '') ?>"><?= htmlspecialchars($post['title'] ?? '') ?></a></h3>
          <p><?= htmlspecialchars($post['excerpt'] ?? '') ?></p>
        </article>
      <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty-state"><h3 class="h3">The journal is ready for its first entry.</h3><p>Published articles will appear here after you add them through the CMS.</p></div>
    <?php endif; ?>
    <a class="text-link" href="/journal.php">Browse the journal →</a>
  </div>
</section>

<section class="editorial-section">
  <div class="editorial-container">
    <p class="meta-label">02 / PORTFOLIO</p><h2 class="h2 section-heading">Selected Work</h2>
    <?php if ($archive['projects']): ?>
      <div class="content-grid"><?php foreach (array_slice($archive['projects'],0,3) as $project): ?>
        <article class="surface-card"><p class="meta-label"><?= htmlspecialchars($project['category'] ?? 'Project') ?></p><h3 class="h3"><?= htmlspecialchars($project['title'] ?? '') ?></h3><p><?= htmlspecialchars($project['summary'] ?? '') ?></p></article>
      <?php endforeach; ?></div>
    <?php else: ?><div class="empty-state"><p>Projects you publish will appear here.</p></div><?php endif; ?>
    <a class="text-link" href="/portfolio.php">View portfolio →</a>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
