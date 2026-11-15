<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
use AhamedDrafted\Config\Auth;
Auth::startSession();
$pdo = \AhamedDrafted\Config\Database::getConnection();
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 if (!Auth::verifyCsrfToken($_POST['csrf_token']??null)) $error='Session validation failed.';
 else {
  $stmt=$pdo->prepare("SELECT id,username,email,password_hash,display_name,role,status FROM users WHERE username=? OR email=? LIMIT 1");
  $identity=trim((string)($_POST['identity']??'')); $stmt->execute([$identity,$identity]); $user=$stmt->fetch();
  if ($user && $user['status']==='active' && password_verify((string)($_POST['password']??''),$user['password_hash'])) {
   session_regenerate_id(true); $_SESSION['user_id']=(int)$user['id']; $_SESSION['user_role']=$user['role']; $_SESSION['display_name']=$user['display_name'];
   $pdo->prepare('UPDATE users SET last_login_at=NOW() WHERE id=?')->execute([$user['id']]);
   header('Location: index.php'); exit;
  }
  $error='Invalid login details or inactive account.';
 }
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin Login</title><link rel="stylesheet" href="../assets/css/tokens.css"><link rel="stylesheet" href="../assets/css/base.css"><style>main{max-width:440px;margin:10vh auto;padding:24px}.panel{padding:24px;background:var(--surface);border:1px solid var(--border-color)}label{display:block;margin:14px 0}input{display:block;width:100%;padding:12px;border:1px solid var(--border-color);background:var(--background);color:var(--text-primary)}.error{padding:12px;background:var(--surface-secondary)}</style></head><body><main class="panel"><p class="meta-label">AHAMED DRAFTED / ADMIN</p><h1 class="h2">Sign in</h1><?php if(isset($_GET['created'])): ?><p>Owner account created. Sign in to continue.</p><?php endif; ?><?php if($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?><form method="post"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>"><label>Username or email<input name="identity" autocomplete="username" required></label><label>Password<input name="password" type="password" autocomplete="current-password" required></label><button class="btn-editorial" type="submit">Sign in</button></form><p style="margin-top:16px"><a href="setup.php">Initial owner setup</a></p></main></body></html>