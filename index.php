<?php
declare(strict_types=1);
$pageTitle = 'Personal Digital Journal, Editorial Portfolio & Life Archive';
$activeNav = 'home';
$canonicalPath = '';
require_once __DIR__ . '/includes/header.php';
$featuredPost = $archive['posts'][0];
$secondaryPosts = array_slice($archive['posts'], 1, 2);
?>
<!-- Section 01: Editorial Hero -->
<section class="editorial-section" aria-labelledby="hero-heading">
  <div class="editorial-container editorial-grid" style="align-items:center;">
    <div style="grid-column: span 7;">
      <p class="meta-label" style="margin-bottom: var(--space-4);"><?= htmlspecialchars($profile['established_label']) ?> · WEST BENGAL, INDIA</p>
      <h1 id="hero-heading" class="display-xl" style="margin-bottom: var(--space-5);"><?= htmlspecialchars($profile['hero_headline']) ?></h1>
      <p style="font-size: var(--type-body-lg); color: var(--text-secondary); max-width: 560px; margin-bottom: var(--space-6);">
        <?= htmlspecialchars($profile['hero_supporting']) ?>
      </p>
      <div style="display:flex; gap: var(--space-4); flex-wrap:wrap;">
        <a href="journal.php" class="btn-editorial">Explore Journal</a>
        <a href="story.php" class="btn-editorial btn-editorial--outline">Read My Story</a>
      </div>
    </div>
    <div style="grid-column: span 5;">
      <figure style="border: 1px solid var(--border-color); padding: var(--space-3); background: var(--surface);">
        <img src="<?= htmlspecialchars($profile['profile_image']) ?>" alt="Portrait of Ruhul Ahamed" width="600" height="800" loading="eager">
        <figcaption class="meta-label" style="margin-top: var(--space-3);">FIG. 01 — RUHUL AHAMED · NATURAL WINDOW LIGHT, AUGUST 2026</figcaption>
      </figure>
    </div>
  </div>
</section>

<!-- Section 02: About Preview -->
<section class="editorial-section" aria-labelledby="about-preview-heading">
  <div class="editorial-container editorial-grid">
    <div style="grid-column: span 3;">
      <p class="meta-label">02 / ABOUT ME</p>
    </div>
    <div style="grid-column: span 9;">
      <h2 id="about-preview-heading" class="h1" style="margin-bottom: var(--space-5);"><?= htmlspecialchars($profile['display_name']) ?></h2>
      <p style="font-size: var(--type-body-lg); max-width: 720px;"><?= htmlspecialchars($profile['short_bio']) ?></p>
      <div class="editorial-grid" style="margin-block: var(--space-6); padding-top: var(--space-5); border-top: 1px solid var(--border-color);">
        <div style="grid-column: span 6;">
          <p class="meta-label">EDUCATION</p>
          <p style="margin-top: var(--space-2);"><?= htmlspecialchars($profile['education_summary']) ?></p>
        </div>
        <div style="grid-column: span 6;">
          <p class="meta-label">CURRENT FOCUS</p>
          <p style="margin-top: var(--space-2);"><?= htmlspecialchars($profile['current_focus']) ?></p>
        </div>
      </div>
      <a href="about.php" class="btn-editorial btn-editorial--outline">Read Full Biography →</a>
    </div>
  </div>
</section>

<!-- Section 03: Featured Journal -->
<section class="editorial-section" aria-labelledby="journal-preview-heading">
  <div class="editorial-container">
    <div style="display:flex; justify-content:space-between; align-items:baseline; margin-bottom: var(--space-7);">
      <div>
        <p class="meta-label">03 / FEATURED JOURNAL</p>
        <h2 id="journal-preview-heading" class="h2" style="margin-top: var(--space-2);">Essays, Dispatches &amp; Field Notes</h2>
      </div>
      <a href="journal.php" class="meta-label">All Essays (<?= count($archive['posts']) ?>) →</a>
    </div>
    <div class="editorial-grid">
      <article style="grid-column: span 7; border-right: 1px solid var(--border-color); padding-right: var(--space-6);">
        <a href="article.php?slug=<?= urlencode($featuredPost['slug']) ?>">
          <img src="<?= htmlspecialchars($featuredPost['featured_image']) ?>" alt="<?= htmlspecialchars($featuredPost['title']) ?>" width="800" height="450" loading="lazy" style="margin-bottom: var(--space-4);">
        </a>
        <p class="meta-label"><?= htmlspecialchars($featuredPost['category']) ?> · <?= htmlspecialchars($featuredPost['date_formatted']) ?> · <?= (int)$featuredPost['reading_time_minutes'] ?> MIN READ</p>
        <h3 class="h2" style="margin-block: var(--space-3);">
          <a href="article.php?slug=<?= urlencode($featuredPost['slug']) ?>"><?= htmlspecialchars($featuredPost['title']) ?></a>
        </h3>
        <p style="color: var(--text-secondary);"><?= htmlspecialchars($featuredPost['excerpt']) ?></p>
      </article>
      <div style="grid-column: span 5; display:flex; flex-direction:column; gap: var(--space-6);">
        <?php foreach ($secondaryPosts as $post): ?>
          <article class="surface-card">
            <p class="meta-label"><?= htmlspecialchars($post['category']) ?> · <?= htmlspecialchars($post['date_formatted']) ?> · <?= (int)$post['reading_time_minutes'] ?> MIN READ</p>
            <h3 class="h3" style="margin-block: var(--space-2);">
              <a href="article.php?slug=<?= urlencode($post['slug']) ?>"><?= htmlspecialchars($post['title']) ?></a>
            </h3>
            <p style="color: var(--text-secondary); font-size: var(--type-small);"><?= htmlspecialchars($post['excerpt']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- Section 04: Photography Asymmetrical Grid -->
<section class="editorial-section" aria-labelledby="photo-preview-heading">
  <div class="editorial-container">
    <div style="display:flex; justify-content:space-between; align-items:baseline; margin-bottom: var(--space-7);">
      <div>
        <p class="meta-label">04 / PHOTOGRAPHY ARCHIVE</p>
        <h2 id="photo-preview-heading" class="h2" style="margin-top: var(--space-2);">Selected Documentary Frames</h2>
      </div>
      <a href="gallery.php" class="meta-label">Open Photo Gallery →</a>
    </div>
    <div class="editorial-grid">
      <figure style="grid-column: span 8;">
        <img src="assets/images/train-window.jpg" alt="Indian railway window view" width="900" height="506" loading="lazy">
        <figcaption class="meta-label" style="margin-top: var(--space-2);">ALBUM 01 · RAILWAYS &amp; PASSING HORIZONS (JUNE 2026)</figcaption>
      </figure>
      <figure style="grid-column: span 4;">
        <img src="assets/images/portrait-hero.jpg" alt="Portrait study" width="450" height="600" loading="lazy">
        <figcaption class="meta-label" style="margin-top: var(--space-2);">ALBUM 05 · NATURAL LIGHT PORTRAITURE</figcaption>
      </figure>
      <figure style="grid-column: span 4;">
        <img src="assets/images/morning-bridge.jpg" alt="River bridge at dawn" width="450" height="338" loading="lazy">
        <figcaption class="meta-label" style="margin-top: var(--space-2);">ALBUM 02 · MIST OVER THE RIVER BRIDGE</figcaption>
      </figure>
      <figure style="grid-column: span 4;">
        <img src="assets/images/wooded-motorcycle.jpg" alt="Motorcycles in sal forest" width="450" height="338" loading="lazy">
        <figcaption class="meta-label" style="margin-top: var(--space-2);">ALBUM 03 · WOODED TRAILS &amp; TWO WHEELS</figcaption>
      </figure>
      <figure style="grid-column: span 4;">
        <img src="assets/images/haryana-courtyard.jpg" alt="Sandstone courtyard in Haryana" width="450" height="338" loading="lazy">
        <figcaption class="meta-label" style="margin-top: var(--space-2);">ALBUM 04 · HARYANA SANDSTONE &amp; SHADOWS</figcaption>
      </figure>
    </div>
  </div>
</section>

<!-- Section 05: Life Timeline -->
<section class="editorial-section" aria-labelledby="timeline-heading">
  <div class="editorial-container">
    <p class="meta-label">05 / CHRONOLOGY</p>
    <h2 id="timeline-heading" class="h2" style="margin-top: var(--space-2); margin-bottom: var(--space-7);">Life Timeline</h2>
    <div style="border-left: 1px solid var(--border-color); padding-left: var(--space-6); display:flex; flex-direction:column; gap: var(--space-6);">
      <?php foreach ($archive['timeline'] as $item): ?>
        <div>
          <p class="meta-label"><?= htmlspecialchars($item['year_label']) ?> · <?= htmlspecialchars($item['category']) ?></p>
          <h3 class="h4" style="margin-block: var(--space-1);"><?= htmlspecialchars($item['title']) ?></h3>
          <p style="color: var(--text-secondary); max-width: 680px;"><?= htmlspecialchars($item['description']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Section 06: People & Friendships -->
<section class="editorial-section" aria-labelledby="friends-heading">
  <div class="editorial-container">
    <div style="display:flex; justify-content:space-between; align-items:baseline; margin-bottom: var(--space-6);">
      <div>
        <p class="meta-label">06 / PEOPLE &amp; FRIENDSHIPS</p>
        <h2 id="friends-heading" class="h2" style="margin-top: var(--space-2);">Companions Along the Way</h2>
      </div>
      <a href="friends.php" class="meta-label">View Full Circle →</a>
    </div>
    <div class="editorial-grid">
      <?php foreach ($archive['friends'] as $friend): ?>
        <article class="surface-card" style="grid-column: span 4;">
          <p class="meta-label"><?= htmlspecialchars($friend['tier_label']) ?></p>
          <h3 class="h4" style="margin-block: var(--space-2);"><?= htmlspecialchars($friend['display_name']) ?></h3>
          <p style="color: var(--text-secondary); font-size: var(--type-small);"><?= htmlspecialchars($friend['description']) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Section 07: Personal Interests & Section 08: Portfolio -->
<section class="editorial-section" aria-labelledby="portfolio-preview-heading">
  <div class="editorial-container">
    <p class="meta-label">07 &amp; 08 / INTERESTS &amp; SELECTED PORTFOLIO</p>
    <h2 id="portfolio-preview-heading" class="h2" style="margin-top: var(--space-2); margin-bottom: var(--space-6);">Craft, Capital &amp; Digital Systems</h2>
    <div class="editorial-grid">
      <?php foreach ($archive['projects'] as $project): ?>
        <article class="surface-card" style="grid-column: span 4;">
          <p class="meta-label"><?= htmlspecialchars($project['category']) ?> · <?= htmlspecialchars($project['year_label']) ?></p>
          <h3 class="h4" style="margin-block: var(--space-2);"><?= htmlspecialchars($project['title']) ?></h3>
          <p style="color: var(--text-secondary); font-size: var(--type-small);"><?= htmlspecialchars($project['summary']) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Section 09: Places & Memories -->
<section class="editorial-section" aria-labelledby="places-preview-heading">
  <div class="editorial-container">
    <div style="display:flex; justify-content:space-between; align-items:baseline; margin-bottom: var(--space-6);">
      <div>
        <p class="meta-label">09 / PLACES &amp; MEMORIES</p>
        <h2 id="places-preview-heading" class="h2" style="margin-top: var(--space-2);">Coordinates &amp; Travel Notes</h2>
      </div>
      <a href="places.php" class="meta-label">Explore Travel Map →</a>
    </div>
    <div class="editorial-grid">
      <?php foreach ($archive['places'] as $place): ?>
        <article class="surface-card" style="grid-column: span 4;">
          <p class="meta-label"><?= htmlspecialchars($place['region_state']) ?> · <?= htmlspecialchars($place['approx_coordinates']) ?></p>
          <h3 class="h4" style="margin-block: var(--space-2);"><?= htmlspecialchars($place['place_name']) ?></h3>
          <p style="color: var(--text-secondary); font-size: var(--type-small);"><?= htmlspecialchars($place['story_excerpt']) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
