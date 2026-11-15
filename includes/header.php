<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/seed_data.php';

use AhamedDrafted\Config\Auth;
Auth::startSession();

$archive   = get_ahamed_archive_data();
$profile   = $archive['profile'];
$pageTitle = isset($pageTitle) ? $pageTitle . ' — AHAMED DRAFTED' : 'AHAMED DRAFTED — ' . $profile['tagline'];
$pageDesc  = $pageDesc ?? $profile['short_bio'];
$activeNav = $activeNav ?? 'home';
$canonical = 'https://ahameddrafted.in/' . ($canonicalPath ?? '');
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
  <meta name="description" content="<?= htmlspecialchars($pageDesc, ENT_QUOTES, 'UTF-8') ?>">
  <link rel="canonical" href="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
  <?php if (!empty($noIndex)): ?>
  <meta name="robots" content="noindex, nofollow">
  <?php else: ?>
  <meta name="robots" content="index, follow">
  <?php endif; ?>
  <meta property="og:site_name" content="AHAMED DRAFTED">
  <meta property="og:title" content="<?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:description" content="<?= htmlspecialchars($pageDesc, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:type" content="website">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/tokens.css">
  <link rel="stylesheet" href="assets/css/base.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <script src="assets/js/theme.js"></script>
</head>
<body>
  <a href="#main-content" class="meta-label" style="position:absolute;left:-9999px;top:8px;">Skip to content</a>
  <header class="site-header" role="banner">
    <div class="site-header__inner">
      <a href="index.php" class="site-wordmark" aria-label="Ahamed Drafted Home">AHAMED DRAFTED</a>
      <nav class="site-nav" aria-label="Primary Editorial Navigation">
        <a href="index.php" class="site-nav__link <?= $activeNav === 'home' ? 'is-active' : '' ?>">Home</a>
        <a href="about.php" class="site-nav__link <?= $activeNav === 'about' ? 'is-active' : '' ?>">About</a>
        <a href="story.php" class="site-nav__link <?= $activeNav === 'story' ? 'is-active' : '' ?>">My Story</a>
        <a href="journal.php" class="site-nav__link <?= $activeNav === 'journal' ? 'is-active' : '' ?>">Journal</a>
        <a href="gallery.php" class="site-nav__link <?= $activeNav === 'gallery' ? 'is-active' : '' ?>">Gallery</a>
        <a href="memories.php" class="site-nav__link <?= $activeNav === 'memories' ? 'is-active' : '' ?>">Memories</a>
        <a href="friends.php" class="site-nav__link <?= $activeNav === 'friends' ? 'is-active' : '' ?>">Friends</a>
        <a href="places.php" class="site-nav__link <?= $activeNav === 'places' ? 'is-active' : '' ?>">Places</a>
        <a href="portfolio.php" class="site-nav__link <?= $activeNav === 'portfolio' ? 'is-active' : '' ?>">Portfolio</a>
        <a href="events.php" class="site-nav__link <?= $activeNav === 'events' ? 'is-active' : '' ?>">Events</a>
        <a href="guestbook.php" class="site-nav__link <?= $activeNav === 'guestbook' ? 'is-active' : '' ?>">Guestbook</a>
      </nav>
      <div style="display:flex;align-items:center;gap:12px;">
        <button type="button" class="btn-editorial btn-editorial--outline" data-theme-toggle aria-label="Toggle colour theme">
          <span data-theme-label>Deep Ink</span>
        </button>
        <a href="admin/index.php" class="btn-editorial" style="padding:10px 16px;">CMS Admin</a>
      </div>
    </div>
  </header>
  <main id="main-content" class="editorial-shell">
