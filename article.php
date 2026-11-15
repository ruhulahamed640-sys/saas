<?php
declare(strict_types=1);
$pageTitle='Journal Article'; $activeNav='journal'; $canonicalPath='article.php';
require_once __DIR__ . '/includes/header.php';
$slug=trim((string)($_GET['slug']??'')); $article=null;
foreach($archive['posts'] as $post){if(($post['slug']??'')===$slug){$article=$post;break;}}
?>
<section class="editorial-section"><div class="editorial-container" style="max-width:var(--reading-width)">
<a class="meta-label" href="/journal.php">← BACK TO JOURNAL</a>
<?php if($article): ?>
<p class="meta-label" style="margin-top:24px"><?= htmlspecialchars($article['category']??'Journal') ?> · <?= htmlspecialchars($article['date_formatted']??'') ?></p>
<h1 class="h1" style="margin-block:20px"><?= htmlspecialchars($article['title']??'') ?></h1>
<?php if(!empty($article['featured_image'])): ?><img src="<?= htmlspecialchars($article['featured_image']) ?>" alt="<?= htmlspecialchars($article['title']??'Article image') ?>" loading="eager"><?php endif; ?>
<?php if(!empty($article['pull_quote'])): ?><blockquote class="pull-quote"><?= htmlspecialchars($article['pull_quote']) ?></blockquote><?php endif; ?>
<div class="article-body"><?= $article['content']??'' ?></div>
<?php else: ?><div class="empty-state"><h1 class="h2">Article not found</h1><p>This article may be unpublished, private, or not yet added.</p></div><?php endif; ?>
</div></section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
