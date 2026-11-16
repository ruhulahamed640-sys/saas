<?php
declare(strict_types=1);
$pageTitle='Birthday Archive';$activeNav='memories';$canonicalPath='birthdays.php';
require_once __DIR__.'/includes/header.php';
?>
<section class="editorial-section"><div class="editorial-container"><p class="meta-label">PERSONAL MEMORY ARCHIVE</p><h1 class="display-lg" style="margin-block:20px">Birthday Memories</h1>
<?php if($archive['birthdays']):?><div class="content-grid"><?php foreach($archive['birthdays'] as $item):?><article class="surface-card"><?php if(!empty($item['photograph_url'])):?><img src="<?=htmlspecialchars($item['photograph_url'])?>" alt="<?=htmlspecialchars($item['title']??'Birthday memory')?>" loading="lazy"><?php endif;?><p class="meta-label"><?=htmlspecialchars($item['date_label']??'')?></p><h2 class="h3"><?=htmlspecialchars($item['title']??'')?></h2><p><?=htmlspecialchars($item['description']??'')?></p></article><?php endforeach;?></div><?php else:?><div class="empty-state"><p>No birthday memories published yet.</p></div><?php endif;?>
</div></section><?php require_once __DIR__.'/includes/footer.php';?>