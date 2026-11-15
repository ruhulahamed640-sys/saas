<?php
declare(strict_types=1);
$pageTitle='My Story'; $activeNav='story'; $canonicalPath='story.php';
require_once __DIR__ . '/includes/header.php';
?>
<section class="editorial-section"><div class="editorial-container" style="max-width:var(--reading-width)">
<p class="meta-label">PERSONAL STORY / TIMELINE</p><h1 class="display-lg" style="margin-block:20px">My Story</h1>
<?php if(!empty($profile['full_biography'])): ?><div><?= nl2br(htmlspecialchars($profile['full_biography'])) ?></div><?php else: ?><div class="empty-state"><p>Your biography has not been published yet. Add it through the profile content settings.</p></div><?php endif; ?>
<?php if($archive['timeline']): ?><div style="margin-top:40px"><?php foreach($archive['timeline'] as $item): ?><article class="surface-card" style="margin-bottom:16px"><p class="meta-label"><?= htmlspecialchars($item['year_label']??'') ?></p><h2 class="h3"><?= htmlspecialchars($item['title']??'') ?></h2><p><?= htmlspecialchars($item['description']??'') ?></p></article><?php endforeach; ?></div><?php endif; ?>
</div></section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
