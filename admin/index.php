<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/archive.php';

use AhamedDrafted\Config\Auth;
Auth::startSession();
$pdo = \AhamedDrafted\Config\Database::getConnection();

if (empty($_SESSION['user_id']) || !in_array($_SESSION['user_role'] ?? '', ['owner','editor'], true)) {
    header('Location: login.php'); exit;
}

$allowed = ['journal','category','album','photograph','memory','friend','event','place','project','timeline','guestbook','education','achievement','interest'];
$message = '';
$error = '';

$profileRow = $pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key = 'profile' LIMIT 1");
$profileRow->execute();
$profileData = json_decode((string)($profileRow->fetchColumn() ?: '{}'), true);
$profileData = is_array($profileData) ? $profileData : [];
$heroImage = (string)($profileData['hero_image'] ?? '');
$heroEnabled = (string)($profileData['hero_image_enabled'] ?? '1') === '1';

$saveProfile = static function (PDO $pdo, array $profile): void {
    $stmt = $pdo->prepare(
        "INSERT INTO site_settings (setting_key, setting_value) VALUES ('profile', ?)
         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
    );
    $stmt->execute([json_encode($profile, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)]);
};

$deleteManagedHero = static function (string $path): void {
    if (!preg_match('#^/uploads/hero/[A-Za-z0-9_-]+\.(?:png|webp)$#i', $path)) return;
    $file = dirname(__DIR__) . $path;
    $base = realpath(dirname(__DIR__) . '/uploads/hero');
    $real = realpath($file);
    if ($base !== false && $real !== false && str_starts_with($real, $base . DIRECTORY_SEPARATOR) && is_file($real)) {
        @unlink($real);
    }
};

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Auth::verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $error = 'Session validation failed. Refresh and try again.';
    } else {
        $action = (string)($_POST['action'] ?? '');
        if ($action === 'hero_upload') {
            $file = $_FILES['hero_image'] ?? null;
            if (!$file || !isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
                $error = 'Choose a valid PNG or WebP image to upload.';
            } elseif ((int)$file['size'] < 1 || (int)$file['size'] > 6 * 1024 * 1024 || !is_uploaded_file($file['tmp_name'])) {
                $error = 'Image must be smaller than 6 MB.';
            } else {
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->file($file['tmp_name']);
                $imageInfo = @getimagesize($file['tmp_name']);
                $extensions = ['image/png' => 'png', 'image/webp' => 'webp'];
                if (!isset($extensions[$mime]) || !$imageInfo || (int)$imageInfo[0] > 8000 || (int)$imageInfo[1] > 8000) {
                    $error = 'Only valid PNG and WebP images up to 8000 × 8000 pixels are accepted.';
                } else {
                    $uploadDir = dirname(__DIR__) . '/uploads/hero';
                    if (!is_dir($uploadDir) && !@mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
                        $error = 'Upload directory is unavailable. Check hosting folder permissions.';
                    } else {
                        $filename = bin2hex(random_bytes(18)) . '.' . $extensions[$mime];
                        $destination = $uploadDir . '/' . $filename;
                        $newPath = '/uploads/hero/' . $filename;
                        if (!move_uploaded_file($file['tmp_name'], $destination)) {
                            $error = 'The image could not be saved. Check upload folder permissions.';
                        } else {
                            try {
                                $oldPath = (string)($profileData['hero_image'] ?? '');
                                $profileData['hero_image'] = $newPath;
                                $profileData['hero_image_enabled'] = '1';
                                $saveProfile($pdo, $profileData);
                                if ($oldPath !== '' && $oldPath !== $newPath) $deleteManagedHero($oldPath);
                                $heroImage = $newPath;
                                $heroEnabled = true;
                                $message = 'Hero image uploaded and activated successfully.';
                            } catch (Throwable $e) {
                                @unlink($destination);
                                error_log('Hero image setting save failed: ' . $e->getMessage());
                                $error = 'Image upload could not be completed. Please try again.';
                            }
                        }
                    }
                }
            }
        } elseif ($action === 'hero_remove') {
            $oldPath = (string)($profileData['hero_image'] ?? '');
            try {
                $profileData['hero_image'] = '';
                $profileData['hero_image_enabled'] = '0';
                $saveProfile($pdo, $profileData);
                if ($oldPath !== '') $deleteManagedHero($oldPath);
                $heroImage = '';
                $heroEnabled = false;
                $message = 'Hero image removed.';
            } catch (Throwable $e) {
                error_log('Hero image removal failed: ' . $e->getMessage());
                $error = 'The hero image could not be removed. Please try again.';
            }
        } elseif ($action === 'hero_toggle') {
            $heroEnabled = ($_POST['hero_image_enabled'] ?? '') === '1';
            $profileData['hero_image_enabled'] = $heroEnabled ? '1' : '0';
            try {
                $saveProfile($pdo, $profileData);
                $message = $heroEnabled ? 'Hero image enabled.' : 'Hero image disabled.';
            } catch (Throwable $e) {
                error_log('Hero image toggle failed: ' . $e->getMessage());
                $error = 'The display setting could not be saved.';
            }
        } else {
            $section = (string)($_POST['section'] ?? '');
            $title = trim((string)($_POST['title'] ?? ''));
            $slug = trim((string)($_POST['slug'] ?? ''));
            $json = trim((string)($_POST['data_json'] ?? '{}'));
            $visibility = ($_POST['visibility'] ?? 'private') === 'public' ? 'public' : 'private';
            $status = in_array($_POST['status'] ?? '', ['draft','published','pending','archived'], true) ? $_POST['status'] : 'draft';
            $decoded = json_decode($json, true);
            if (!in_array($section, $allowed, true) || $title === '' || !is_array($decoded)) {
                $error = 'Choose a valid content type, enter a title, and provide valid JSON content.';
            } else {
                $decoded['title'] = $decoded['title'] ?? $title;
                if ($slug !== '') $decoded['slug'] = $slug;
                $stmt = $pdo->prepare('INSERT INTO content_records (section, slug, title, data_json, visibility, status, published_at) VALUES (?, ?, ?, ?, ?, ?, ?)');
                $stmt->execute([$section, $slug !== '' ? $slug : null, $title, json_encode($decoded, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES), $visibility, $status, $status === 'published' ? date('Y-m-d H:i:s') : null]);
                $message = 'Content record saved.';
            }
        }
    }
}

$profileRow = $pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key = 'profile' LIMIT 1");
$profileRow->execute();
$profileData = json_decode((string)($profileRow->fetchColumn() ?: '{}'), true);
$profileData = is_array($profileData) ? $profileData : [];
$heroImage = (string)($profileData['hero_image'] ?? '');
$heroEnabled = (string)($profileData['hero_image_enabled'] ?? '1') === '1';
$heroImageValid = preg_match('#^/uploads/hero/[A-Za-z0-9_-]+\.(?:png|webp)$#i', $heroImage) === 1
    && is_file(dirname(__DIR__) . $heroImage);
$csrf = htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8');

$counts = $pdo->query("SELECT section, COUNT(*) AS total FROM content_records GROUP BY section")->fetchAll();
$total = (int)$pdo->query('SELECT COUNT(*) FROM content_records')->fetchColumn();
$views = (int)$pdo->query('SELECT COUNT(*) FROM page_views')->fetchColumn();
$recent = $pdo->query('SELECT id,section,title,status,visibility,created_at FROM content_records ORDER BY id DESC LIMIT 12')->fetchAll();
?>
<!doctype html><html lang="en" data-theme="light"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Dashboard — Ahamed Drafted</title>
<link rel="stylesheet" href="../assets/css/tokens.css"><link rel="stylesheet" href="../assets/css/base.css"><link rel="stylesheet" href="../assets/css/components.css"><link rel="stylesheet" href="../assets/css/mobile.css">
<style>
.admin-wrap{max-width:1200px;margin:auto;padding:24px}.admin-top{display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;margin-bottom:24px}.stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;margin-bottom:24px}.admin-panel{background:var(--surface);border:1px solid var(--border-color);padding:20px;margin-bottom:20px}.admin-form{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}.admin-form label{display:flex;flex-direction:column;gap:6px;font-size:13px}.admin-form input,.admin-form select,.admin-form textarea{width:100%;padding:12px;border:1px solid var(--border-color);background:var(--background);color:var(--text-primary);font:inherit;border-radius:2px}.wide{grid-column:1/-1}.notice{padding:12px;background:var(--surface-secondary);margin-bottom:14px}.admin-table{width:100%;border-collapse:collapse}.admin-table th,.admin-table td{text-align:left;padding:10px;border-bottom:1px solid var(--border-color);font-size:13px}.hero-admin-preview{display:block;max-width:260px;max-height:280px;object-fit:contain;background:#111;padding:12px;margin:12px 0}.hero-admin-actions{display:flex;gap:10px;flex-wrap:wrap;align-items:center}.hero-toggle{display:flex;gap:10px;align-items:center;margin:16px 0}.hero-toggle input{width:auto}
@media(max-width:700px){.admin-wrap{padding:16px}.stats{grid-template-columns:1fr}.admin-form{grid-template-columns:1fr}.wide{grid-column:auto}.admin-panel{padding:15px}.table-scroll{overflow-x:auto}}
</style></head><body><main class="admin-wrap">
<header class="admin-top"><div><p class="meta-label">AHAMED DRAFTED / CMS</p><h1 class="h2">Admin Dashboard</h1><p>Signed in as <?= htmlspecialchars($_SESSION['display_name'] ?? 'Admin', ENT_QUOTES, 'UTF-8') ?></p></div><div><a class="btn-editorial btn-editorial--outline" href="../">View Website</a> <a class="btn-editorial" href="logout.php">Log out</a></div></header>
<?php if($message): ?><p class="notice" role="status"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?><?php if($error): ?><p class="notice" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
<section class="stats"><div class="admin-panel"><p class="meta-label">CONTENT RECORDS</p><p class="h1"><?= $total ?></p></div><div class="admin-panel"><p class="meta-label">TRACKED PAGE VIEWS</p><p class="h1"><?= $views ?></p></div><div class="admin-panel"><p class="meta-label">CONTENT TYPES</p><p class="h1"><?= count($counts) ?></p></div></section>

<section class="admin-panel" id="hero-image-management">
<p class="meta-label">HOMEPAGE PRESENTATION</p><h2 class="h3" style="margin:8px 0 6px">Hero Image Management</h2>
<p style="font-size:13px;color:var(--text-secondary)">Upload a transparent PNG or WebP portrait. The image appears on the right side of the homepage hero.</p>
<?php if ($heroImageValid): ?><img class="hero-admin-preview" src="<?= htmlspecialchars($heroImage, ENT_QUOTES, 'UTF-8') ?>" alt="Current hero image preview"><?php else: ?><p class="notice">No active hero image is configured. The homepage will keep its text-only layout.</p><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="admin-form">
<input type="hidden" name="csrf_token" value="<?= $csrf ?>"><input type="hidden" name="action" value="hero_upload">
<input type="hidden" name="MAX_FILE_SIZE" value="6291456">
<label class="wide">Choose new hero image (PNG or WebP, maximum 6 MB)<input type="file" name="hero_image" accept="image/png,image/webp" required></label>
<div class="wide hero-admin-actions"><button class="btn-editorial" type="submit">Upload and activate</button></div>
</form>
<form method="post" class="hero-toggle">
<input type="hidden" name="csrf_token" value="<?= $csrf ?>"><input type="hidden" name="action" value="hero_toggle">
<input type="hidden" name="hero_image_enabled" value="0">
<label style="display:flex;gap:9px;align-items:center"><input type="checkbox" name="hero_image_enabled" value="1" <?= $heroEnabled ? 'checked' : '' ?> <?= !$heroImageValid ? 'disabled' : '' ?>> Show hero image on homepage</label>
<button class="btn-editorial btn-editorial--outline" type="submit" <?= !$heroImageValid ? 'disabled' : '' ?>>Save display setting</button>
</form>
<?php if ($heroImage !== ''): ?><form method="post" onsubmit="return confirm('Remove the current hero image?')" class="hero-admin-actions"><input type="hidden" name="csrf_token" value="<?= $csrf ?>"><input type="hidden" name="action" value="hero_remove"><button class="btn-editorial btn-editorial--outline" type="submit">Remove current image</button></form><?php endif; ?>
</section>

<section class="admin-panel"><p class="meta-label">REAL CONTENT ENTRY</p><h2 class="h3" style="margin:8px 0 18px">Add a content record</h2>
<form method="post" class="admin-form"><input type="hidden" name="csrf_token" value="<?= $csrf ?>">
<label>Content type<select name="section" required><?php foreach($allowed as $type): ?><option value="<?= $type ?>"><?= ucfirst($type) ?></option><?php endforeach; ?></select></label>
<label>Title<input name="title" required maxlength="255"></label>
<label>Slug (optional)<input name="slug" maxlength="190"></label>
<label>Visibility<select name="visibility"><option value="private">Private</option><option value="public">Public</option></select></label>
<label>Status<select name="status"><option value="draft">Draft</option><option value="published">Published</option><option value="pending">Pending</option></select></label>
<label class="wide">Content JSON<textarea name="data_json" rows="9" required>{}</textarea></label>
<p class="wide" style="font-size:13px;color:var(--text-secondary)">Enter the fields required by the selected page, for example: excerpt, category, featured_image, date_formatted, reading_time_minutes and content for a journal entry. No demo records are preloaded.</p>
<button class="btn-editorial wide" type="submit">Save real content</button></form></section>
<section class="admin-panel"><p class="meta-label">RECENT RECORDS</p><h2 class="h3" style="margin:8px 0 16px">Content archive</h2><div class="table-scroll"><table class="admin-table"><thead><tr><th>Type</th><th>Title</th><th>Status</th><th>Visibility</th><th>Created</th></tr></thead><tbody><?php foreach($recent as $row): ?><tr><td><?= htmlspecialchars($row['section']) ?></td><td><?= htmlspecialchars($row['title'] ?? '') ?></td><td><?= htmlspecialchars($row['status']) ?></td><td><?= htmlspecialchars($row['visibility']) ?></td><td><?= htmlspecialchars($row['created_at']) ?></td></tr><?php endforeach; ?><?php if(!$recent): ?><tr><td colspan="5">No content has been entered yet.</td></tr><?php endif; ?></tbody></table></div></section>
</main></body></html>
