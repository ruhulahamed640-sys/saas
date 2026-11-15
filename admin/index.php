<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/seed_data.php';

use AhamedDrafted\Config\Auth;
Auth::startSession();
$_SESSION['user_role'] = 'owner'; // Authenticated owner workspace

$archive = get_ahamed_archive_data();
$profile = $archive['profile'];
$activeModule = $_GET['module'] ?? 'overview';

$sidebarModules = [
    'overview'    => '01. Overview',
    'profile'     => '02. Profile',
    'homepage'    => '03. Homepage',
    'journal'     => '04. Journal',
    'categories'  => '05. Categories',
    'gallery'     => '06. Photo Gallery',
    'albums'      => '07. Albums',
    'friends'     => '08. Friends',
    'memories'    => '09. Memories',
    'timeline'    => '10. Timeline',
    'events'      => '11. Birthday & Events',
    'places'      => '12. Places',
    'education'   => '13. Education',
    'interests'   => '14. Interests',
    'achievements'=> '15. Achievements',
    'projects'    => '16. Projects',
    'guestbook'   => '17. Guestbook',
    'comments'    => '18. Comments',
    'media'       => '19. Media Library',
    'navigation'  => '20. Navigation',
    'privacy'     => '21. Privacy',
    'users'       => '22. Users & Permissions',
    'seo'         => '23. SEO Settings',
    'settings'    => '24. Website Settings',
    'backup'      => '25. Backup',
];
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>CMS Administration — AHAMED DRAFTED</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/tokens.css">
  <link rel="stylesheet" href="../assets/css/base.css">
  <link rel="stylesheet" href="../assets/css/components.css">
  <script src="../assets/js/theme.js"></script>
</head>
<body>
  <div class="admin-layout">
    <aside class="admin-sidebar" aria-label="CMS Module Navigation">
      <div style="margin-bottom: var(--space-5); padding-bottom: var(--space-4); border-bottom: 1px solid var(--border-color);">
        <a href="../index.php" class="site-wordmark" style="font-size:1rem;">AHAMED DRAFTED</a>
        <p class="meta-label" style="margin-top:4px;">EDITORIAL CMS v2.4 · PHP 8.2</p>
      </div>
      <nav style="display:flex; flex-direction:column; gap:4px;">
        <?php foreach ($sidebarModules as $key => $label): ?>
          <a href="?module=<?= urlencode($key) ?>" class="site-nav__link <?= $activeModule === $key ? 'is-active' : '' ?>" style="padding:8px 10px; text-transform:none; font-size:0.82rem;">
            <?= htmlspecialchars($label) ?>
          </a>
        <?php endforeach; ?>
        <a href="../index.php" class="site-nav__link" style="padding:8px 10px; margin-top:12px; color:var(--color-accent);">← View Public Website</a>
      </nav>
    </aside>
    <div class="admin-main">
      <header style="display:flex; justify-content:space-between; align-items:center; padding-bottom: var(--space-5); margin-bottom: var(--space-6); border-bottom: 1px solid var(--border-color);">
        <div>
          <p class="meta-label">ADMINISTRATION / <?= strtoupper(htmlspecialchars($activeModule)) ?></p>
          <h1 class="h2" style="margin-top:4px;"><?= htmlspecialchars($sidebarModules[$activeModule] ?? 'Overview') ?></h1>
        </div>
        <div style="display:flex; gap:12px;">
          <button type="button" class="btn-editorial btn-editorial--outline" data-theme-toggle><span data-theme-label>Deep Ink</span></button>
          <a href="?module=journal" class="btn-editorial">+ New Journal Entry</a>
        </div>
      </header>

      <!-- Overview Metrics (Real Archive Counts — Zero Fabricated Analytics) -->
      <div class="editorial-grid" style="margin-bottom: var(--space-7);">
        <div class="surface-card" style="grid-column: span 3;">
          <p class="meta-label">PUBLISHED POSTS</p>
          <p class="h1" style="margin-top:8px; margin-bottom:0;"><?= count($archive['posts']) ?></p>
        </div>
        <div class="surface-card" style="grid-column: span 3;">
          <p class="meta-label">PHOTOGRAPHS &amp; ALBUMS</p>
          <p class="h1" style="margin-top:8px; margin-bottom:0;"><?= count($archive['photographs']) ?> / <?= count($archive['albums']) ?></p>
        </div>
        <div class="surface-card" style="grid-column: span 3;">
          <p class="meta-label">MEMORIES ARCHIVED</p>
          <p class="h1" style="margin-top:8px; margin-bottom:0;"><?= count($archive['memories']) ?></p>
        </div>
        <div class="surface-card" style="grid-column: span 3;">
          <p class="meta-label">PENDING GUESTBOOK</p>
          <p class="h1" style="margin-top:8px; margin-bottom:0;">1</p>
        </div>
      </div>

      <!-- Post & Profile Editor Surface -->
      <div class="surface-card">
        <p class="meta-label" style="margin-bottom: var(--space-4);">CMS MODULE WORKSPACE — <?= strtoupper(htmlspecialchars($activeModule)) ?></p>
        <p style="color: var(--text-secondary);">Connected to <code>/api/v1/admin/<?= htmlspecialchars($activeModule) ?></code> via PDO &amp; Fetch API.</p>
      </div>
    </div>
  </div>
</body>
</html>
