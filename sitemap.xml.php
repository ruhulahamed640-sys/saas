<?php
declare(strict_types=1);
require_once __DIR__ . '/config/archive.php';
header('Content-Type: application/xml; charset=utf-8');
header('Cache-Control: public, max-age=3600');
$base = 'https://ruhul.online';
$pages = ['','about.php','story.php','journal.php','gallery.php','memories.php','friends.php','events.php','places.php','portfolio.php','guestbook.php'];
$archive = get_ahamed_archive_data();
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($pages as $page): ?>
  <url><loc><?= htmlspecialchars($base . ($page ? '/' . $page : '/'), ENT_XML1, 'UTF-8') ?></loc><changefreq>weekly</changefreq><priority><?= $page === '' ? '1.0' : '0.7' ?></priority></url>
<?php endforeach; ?>
<?php foreach ($archive['posts'] as $post): if (empty($post['slug'])) continue; ?>
  <url><loc><?= htmlspecialchars($base . '/article.php?slug=' . rawurlencode($post['slug']), ENT_XML1, 'UTF-8') ?></loc><changefreq>monthly</changefreq><priority>0.8</priority></url>
<?php endforeach; ?>
</urlset>
