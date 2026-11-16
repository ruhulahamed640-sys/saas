<?php
declare(strict_types=1);
$pageTitle='Life Calendar';$activeNav='calendar';$canonicalPath='calendar.php';
require_once __DIR__.'/includes/header.php';
$items=array_values(array_filter($archive['events'],static fn($e)=>!empty($e['event_date'])));
usort($items,static fn($a,$b)=>strcmp((string)$a['event_date'],(string)$b['event_date']));
?>
<section class="editorial-section"><div class="editorial-container"><p class="meta-label">PERSONAL CHRONOLOGY</p><h1 class="display-lg" style="margin-block:20px">Life Calendar</h1><p class="muted">Public events and milestones, arranged by date.</p>
<?php if($items):?><div class="content-grid"><?php foreach($items as $event):?><article class="surface-card"><p class="meta-label"><?=htmlspecialchars($event['event_date'])?></p><h2 class="h3"><?=htmlspecialchars($event['title']??'')?></h2><p><?=htmlspecialchars($event['description']??'')?></p><?php if(!empty($event['featured_image'])):?><img src="<?=htmlspecialchars($event['featured_image'])?>" alt="" loading="lazy"><?php endif;?></article><?php endforeach;?></div><?php else:?><div class="empty-state"><p>No dated public events have been added yet.</p></div><?php endif;?>
</div></section><?php require_once __DIR__.'/includes/footer.php';?>