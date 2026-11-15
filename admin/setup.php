<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
use AhamedDrafted\Config\Auth;
Auth::startSession();
$pdo = \AhamedDrafted\Config\Database::getConnection();
$count = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$error = '';
if ($count > 0) { http_response_code(403); exit('Initial owner setup is already closed.'); }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 if (!Auth::verifyCsrfToken($_POST['csrf_token'] ?? null)) $error='Session expired. Refresh and retry.';
 else {
  $username=trim((string)($_POST['username']??'')); $email=filter_var($_POST['email']??'',FILTER_VALIDATE_EMAIL);
  $name=trim((string)($_POST['display_name']??'')); $password=(string)($_POST['password']??'');
  if (!preg_match('/^[a-zA-Z0-9_.-]{3,80}$/',$username) || !$email || $name==='' || strlen($password)<12) $error='Enter valid details. Password must be at least 12 characters.';
  else {
   $stmt=$pdo->prepare("INSERT INTO users (username,email,password_hash,display_name,role,status) VALUES (?,?,?,?, 'owner','active')");
   $stmt->execute([$username,$email,password_hash($password,PASSWORD_DEFAULT),$name]);
   header('Location: login.php?created=1'); exit;
  }
 }
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Initial Owner Setup</title><link rel="stylesheet" href="../assets/css/tokens.css"><link rel="stylesheet" href="../assets/css/base.css"><style>main{max-width:480px;margin:8vh auto;padding:24px}.panel{background:var(--surface);border:1px solid var(--border-color);padding:24px}label{display:block;margin:14px 0}input{display:block;width:100%;padding:12px;border:1px solid var(--border-color);background:var(--background);color:var(--text-primary)}.error{padding:12px;background:var(--surface-secondary)}</style></head><body><main class="panel"><p class="meta-label">ONE-TIME SETUP</p><h1 class="h2">Create the site owner</h1><p>This page is available only while the users table is empty.</p><?php if($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?><form method="post"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>"><label>Username<input name="username" required></label><label>Display name<input name="display_name" required></label><label>Email<input name="email" type="email" required></label><label>Password (12+ characters)<input name="password" type="password" minlength="12" required></label><button class="btn-editorial" type="submit">Create owner account</button></form></main></body></html>