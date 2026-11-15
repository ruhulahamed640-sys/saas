<?php
declare(strict_types=1);
require_once __DIR__ . '/config/seed_data.php';
header('Content-Type: application/xml; charset=utf-8');
$archive = get_ahamed_archive_data();
$base = 'https://ahameddrafted.in';
$pages = ['index.php', 'about.php', 'story.php', 'journal.php', 'gallery.php', 'memories.php', 'friends.php', 'events.php', 'places.php', 'portfolio.php', 'guestbook.php'];
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <?php foreach ($pages as $p): ?>
  <url>
    <loc><?= $base . '/' . $p ?></loc>
    <changefreq>weekly</changefreq>
    <priority><?= $p === 'index.php' ? '1.0' : '0.8' ?></priority>
  </url>
  <?php endforeach; ?>
  <?php foreach ($archive['posts'] as $post): ?>
    <?php if (($post['visibility'] ?? 'public') === 'public' && ($post['status'] ?? 'published') === 'published'): ?>
  <url>
    <loc><?= $base . '/article.php?slug=' . urlencode($post['slug']) ?></loc>
    <lastmod><?= htmlspecialchars($post['published_at']) ?></lastmod>
    <priority>0.9</priority>
  </url>
    <?php endif; ?>
  <?php endforeach; ?>
</urlset>
