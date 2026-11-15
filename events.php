<?php
declare(strict_types=1);
$pageTitle='Events'; $activeNav='events'; $canonicalPath='events.php';
require_once __DIR__ . '/includes/header.php';
?>
<section class="editorial-section"><div class="editorial-container"><p class="meta-label">EVENT ARCHIVE</p><h1 class="display-lg" style="margin-block:20px">Events & Milestones</h1>
<?php if($archive['events']): ?><div class="content-grid"><?php foreach($archive['events'] as $event): ?><article class="surface-card"><p class="meta-label"><?= htmlspecialchars($event['event_date']??'') ?></p><h2 class="h3"><?= htmlspecialchars($event['title']??'') ?></h2><p><?= htmlspecialchars($event['description']??'') ?></p></article><?php endforeach; ?></div><?php else: ?><div class="empty-state"><p>No public events have been added yet.</p></div><?php endif; ?>
</div></section><?php require_once __DIR__ . '/includes/footer.php'; ?>
