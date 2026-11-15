<?php
declare(strict_types=1);
$pageTitle = 'Visitor Guestbook';
$activeNav = 'guestbook';
$canonicalPath = 'guestbook.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="editorial-section">
  <div class="editorial-container">
    <div class="editorial-grid">
      <div style="grid-column: span 5;">
        <p class="meta-label">PAGE L / READER CORRESPONDENCE</p>
        <h1 class="h1" style="margin-block: var(--space-3);">Sign the Guestbook</h1>
        <p style="color: var(--text-secondary); margin-bottom: var(--space-5);">
          Leave a note, reflection, or greeting. All entries pass through editorial moderation before public display.
        </p>
        <form method="post" action="api/v1/index.php?route=guestbook" class="surface-card" style="display:flex; flex-direction:column; gap: var(--space-4);">
          <div>
            <label class="meta-label" for="visitor_name">YOUR NAME *</label>
            <input id="visitor_name" name="visitor_name" required style="width:100%; padding:12px; margin-top:6px; border:1px solid var(--border-color); background:var(--background); color:var(--text-primary);">
          </div>
          <div>
            <label class="meta-label" for="public_profile_url">OPTIONAL WEBSITE OR PROFILE URL</label>
            <input id="public_profile_url" name="public_profile_url" type="url" placeholder="https://" style="width:100%; padding:12px; margin-top:6px; border:1px solid var(--border-color); background:var(--background); color:var(--text-primary);">
          </div>
          <div>
            <label class="meta-label" for="message">MESSAGE *</label>
            <textarea id="message" name="message" rows="4" required style="width:100%; padding:12px; margin-top:6px; border:1px solid var(--border-color); background:var(--background); color:var(--text-primary);"></textarea>
          </div>
          <button type="submit" class="btn-editorial">Submit to Archive</button>
        </form>
      </div>
      <div style="grid-column: span 7;">
        <p class="meta-label" style="margin-bottom: var(--space-4);">APPROVED READER NOTES</p>
        <div style="display:flex; flex-direction:column; gap: var(--space-4);">
          <?php foreach ($archive['guestbook'] as $entry): ?>
            <?php if ($entry['moderation_status'] === 'approved'): ?>
              <article class="surface-card">
                <p style="font-family: var(--font-heading); font-size: var(--type-h4); margin-bottom: var(--space-3);">“<?= htmlspecialchars($entry['message']) ?>”</p>
                <p class="meta-label">
                  <?= htmlspecialchars($entry['visitor_name']) ?> · <?= htmlspecialchars($entry['visitor_location'] ?? '') ?> · <?= htmlspecialchars($entry['created_at']) ?>
                </p>
              </article>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
