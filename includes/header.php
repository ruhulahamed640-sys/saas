<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/archive.php';

use AhamedDrafted\Config\Auth;
Auth::startSession();

$archive = get_ahamed_archive_data();
try {
    $pdo = \\AhamedDrafted\\Config\\Database::getConnection();
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $agent = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
    $device = preg_match('/ipad|tablet|kindle|playbook/i', $agent) ? 'tablet' : (preg_match('/mobile|android|iphone|ipod/i', $agent) ? 'mobile' : 'desktop');
    $visitor = hash_hmac('sha256', ($_SERVER['REMOTE_ADDR'] ?? '') . '|' . $agent, date('Y-m-d') . '|' . (getenv('APP_URL') ?: 'ruhul.online'));
    $track = $pdo->prepare('INSERT INTO page_views (path, visitor_hash, device_type) VALUES (?, ?, ?)');
    $track->execute([$path, $visitor, $device]);
} catch (Throwable $e) {
    error_log('Page view tracking skipped: ' . $e->getMessage());
}
$profile = $archive['profile'];
$pageTitle = isset($pageTitle) ? $pageTitle . ' — AHAMED DRAFTED' : 'AHAMED DRAFTED — ' . ($profile['tagline'] ?: 'Personal Digital Journal');
$pageDesc = $pageDesc ?? ($profile['short_bio'] ?: 'The personal digital journal and editorial portfolio of Ruhul Ahamed.');
$activeNav = $activeNav ?? 'home';
$canonical = 'https://ruhul.online/' . ($canonicalPath ?? '');
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
  <meta name="description" content="<?= htmlspecialchars($pageDesc, ENT_QUOTES, 'UTF-8') ?>">
  <link rel="canonical" href="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
  <?php if (!empty($noIndex)): ?><meta name="robots" content="noindex, nofollow"><?php else: ?><meta name="robots" content="index, follow"><?php endif; ?>
  <meta property="og:site_name" content="AHAMED DRAFTED">
  <meta property="og:title" content="<?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:description" content="<?= htmlspecialchars($pageDesc, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:type" content="website">
  <meta name="theme-color" content="#F6F5F1">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/tokens.css">
  <link rel="stylesheet" href="/assets/css/base.css">
  <link rel="stylesheet" href="/assets/css/components.css">
  <link rel="stylesheet" href="/assets/css/mobile.css">
  <script src="/assets/js/theme.js" defer></script>
  <script src="/assets/js/navigation.js" defer></script>
</head>
<body>
  <a href="#main-content" class="skip-link">Skip to content</a>
  <header class="site-header" role="banner">
    <div class="site-header__inner">
      <a href="/" class="site-wordmark" aria-label="Ahamed Drafted Home">AHAMED DRAFTED</a>
      <button class="mobile-menu-toggle" type="button" aria-expanded="false" aria-controls="primary-navigation" data-menu-toggle>
        <span class="menu-toggle-label">Menu</span><span aria-hidden="true">☰</span>
      </button>
      <nav class="site-nav" id="primary-navigation" aria-label="Primary Editorial Navigation">
        <a href="/" class="site-nav__link <?= $activeNav === 'home' ? 'is-active' : '' ?>">Home</a>
        <a href="/about.php" class="site-nav__link <?= $activeNav === 'about' ? 'is-active' : '' ?>">About</a>
        <a href="/story.php" class="site-nav__link <?= $activeNav === 'story' ? 'is-active' : '' ?>">My Story</a>
        <a href="/journal.php" class="site-nav__link <?= $activeNav === 'journal' ? 'is-active' : '' ?>">Journal</a>
        <a href="/gallery.php" class="site-nav__link <?= $activeNav === 'gallery' ? 'is-active' : '' ?>">Gallery</a>
        <a href="/memories.php" class="site-nav__link <?= $activeNav === 'memories' ? 'is-active' : '' ?>">Memories</a>
        <a href="/friends.php" class="site-nav__link <?= $activeNav === 'friends' ? 'is-active' : '' ?>">Friends</a>
        <a href="/places.php" class="site-nav__link <?= $activeNav === 'places' ? 'is-active' : '' ?>">Places</a>
        <a href="/portfolio.php" class="site-nav__link <?= $activeNav === 'portfolio' ? 'is-active' : '' ?>">Portfolio</a>
        <a href="/events.php" class="site-nav__link <?= $activeNav === 'events' ? 'is-active' : '' ?>">Events</a>
        <a href="/guestbook.php" class="site-nav__link <?= $activeNav === 'guestbook' ? 'is-active' : '' ?>">Guestbook</a>
      </nav>
      <div class="header-actions">
        <button type="button" class="btn-editorial btn-editorial--outline" data-theme-toggle aria-label="Toggle colour theme"><span data-theme-label>Deep Ink</span></button>
        <a href="/admin/" class="btn-editorial admin-link">CMS Admin</a>
      </div>
    </div>
  </header>
  <main id="main-content" class="editorial-shell">
