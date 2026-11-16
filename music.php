<?php
declare(strict_types=1);
$pageTitle='Favourite Music';$activeNav='music';$canonicalPath='music.php';
require_once __DIR__.'/includes/header.php';
?>
<section class="editorial-section"><div class="editorial-container"><p class="meta-label">PERSONAL SOUNDTRACK</p><h1 class="display-lg" style="margin-block:20px">Favourite Music</h1>
<?php if($archive['songs']):?><div class="content-grid"><?php foreach($archive['songs'] as $song):?><article class="surface-card"><?php if(!empty($song['cover_image'])):?><img src="<?=htmlspecialchars($song['cover_image'])?>" alt="" loading="lazy"><?php endif;?><p class="meta-label"><?=htmlspecialchars($song['artist']??'')?></p><h2 class="h3"><?=htmlspecialchars($song['title']??'')?></h2><?php if(!empty($song['audio_url'])):?><audio controls preload="none" src="<?=htmlspecialchars($song['audio_url'])?>" style="width:100%"></audio><?php endif;?><?php if(!empty($song['external_url'])):?><a class="text-link" href="<?=htmlspecialchars($song['external_url'])?>" target="_blank" rel="noopener noreferrer">Listen on music platform ↗</a><?php endif;?></article><?php endforeach;?></div><?php else:?><div class="empty-state"><p>No public music entries yet.</p></div><?php endif;?>
</div></section><?php require_once __DIR__.'/includes/footer.php';?>