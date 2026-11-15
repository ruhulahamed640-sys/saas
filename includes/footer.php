<?php
declare(strict_types=1);
?>
  </main>
  <footer class="editorial-shell" role="contentinfo" style="padding-block: var(--space-9); border-top: 1px solid var(--border-color);">
    <div class="editorial-container">
      <div class="editorial-grid" style="align-items:start;">
        <div style="grid-column: span 5;">
          <p class="site-wordmark" style="margin-bottom: var(--space-3);">AHAMED DRAFTED</p>
          <p style="color: var(--text-secondary); max-width: 420px; font-size: var(--type-small);">
            <?= htmlspecialchars($profile['short_bio'], ENT_QUOTES, 'UTF-8') ?>
          </p>
          <p class="meta-label" style="margin-top: var(--space-4);">
            “<?= htmlspecialchars($profile['philosophy_quote'], ENT_QUOTES, 'UTF-8') ?>”
          </p>
        </div>
        <div style="grid-column: span 4;">
          <p class="meta-label" style="margin-bottom: var(--space-3);">Editorial Index</p>
          <ul style="list-style:none;display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:var(--type-small);">
            <li><a href="index.php">01. Homepage</a></li>
            <li><a href="about.php">02. About Me</a></li>
            <li><a href="story.php">03. My Story</a></li>
            <li><a href="journal.php">04. Journal</a></li>
            <li><a href="gallery.php">05. Photo Gallery</a></li>
            <li><a href="memories.php">06. Memories</a></li>
            <li><a href="friends.php">07. Friends</a></li>
            <li><a href="events.php">08. Birthday &amp; Events</a></li>
            <li><a href="places.php">09. Places &amp; Travel</a></li>
            <li><a href="portfolio.php">10. Portfolio</a></li>
            <li><a href="guestbook.php">11. Guestbook</a></li>
            <li><a href="admin/index.php">12. CMS Dashboard</a></li>
          </ul>
        </div>
        <div style="grid-column: span 3;">
          <p class="meta-label" style="margin-bottom: var(--space-3);">Elsewhere &amp; Archive</p>
          <ul style="list-style:none;display:flex;flex-direction:column;gap:8px;font-size:var(--type-small);">
            <?php foreach ($profile['social_links'] as $link): ?>
              <li><a href="<?= htmlspecialchars($link['url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener"><?= htmlspecialchars($link['label'], ENT_QUOTES, 'UTF-8') ?></a></li>
            <?php endforeach; ?>
          </ul>
          <div style="margin-top: var(--space-5);">
            <a href="#main-content" class="meta-label">↑ Back to top</a>
          </div>
        </div>
      </div>
      <div style="margin-top: var(--space-7); padding-top: var(--space-5); border-top: 1px solid var(--border-color); display:flex; justify-content:space-between; flex-wrap:wrap; gap:12px;">
        <span class="meta-label">© <?= date('Y') ?> RUHUL AHAMED · ALL RIGHTS RESERVED · COOCH BEHAR, WEST BENGAL</span>
        <span class="meta-label">PRIVACY-FIRST ARCHIVE · NATIVE PHP 8.2 &amp; MYSQL 8</span>
      </div>
    </div>
  </footer>
</body>
</html>
