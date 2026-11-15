<?php
declare(strict_types=1);
$pageTitle='Photo Album'; $activeNav='gallery'; $canonicalPath='album.php';
require_once __DIR__ . '/includes/header.php';
$slug=trim((string)($_GET['slug']??'')); $album=null;
foreach($archive['albums'] as $item){if(($item['slug']??'')===$slug){$album=$item;break;}}
?>
<section class="editorial-section"><div class="editorial-container">
<a class="meta-label" href="/gallery.php">← ALL ALBUMS</a>
<?php if($album): ?><h1 class="display-lg" style="margin-block:20px"><?= htmlspecialchars($album['title']??'') ?></h1><p><?= htmlspecialchars($album['editorial_intro']??$album['description']??'') ?></p>
<div class="content-grid"><?php foreach($archive['photographs'] as $photo): ?><figure class="surface-card"><?php if(!empty($photo['file_path'])): ?><img src="<?= htmlspecialchars($photo['file_path']) ?>" alt="<?= htmlspecialchars($photo['alt_text']??'') ?>" loading="lazy"><?php endif; ?><h2 class="h4"><?= htmlspecialchars($photo['title']??'') ?></h2><p><?= htmlspecialchars($photo['caption']??'') ?></p></figure><?php endforeach; ?></div>
<?php else: ?><div class="empty-state"><h1 class="h2">No published album found</h1><p>Published albums will appear here after you add them in the CMS.</p></div><?php endif; ?>
</div></section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
