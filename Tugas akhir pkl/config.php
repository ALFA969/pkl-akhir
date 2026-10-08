<?php
session_start();

$pdo = new PDO(
  'mysql:host=localhost;dbname=crud_app;charset=utf8mb4',
  'root', '',   // ganti user & password MySQL kamu
  [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
);

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function require_login() {
  if (empty($_SESSION['uid'])) { header('Location: login.php'); exit; }
}

function csrf_field() {
  if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
  return '<input type="hidden" name="csrf" value="' . $_SESSION['csrf'] . '">';
}

function csrf_check() {
  if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) die('Token CSRF tidak valid.');
}

function page_start($title) { ?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?></title>
<style>
  body{font-family:system-ui,sans-serif;background:#f5f6fa;color:#1d2433;margin:0}
  .wrap{max-width:860px;margin:0 auto;padding:16px}
  .card{background:#fff;border:1px solid #e1e4ec;border-radius:12px;padding:20px;margin-bottom:16px}
  nav{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px}
  label{display:block;font-size:13px;color:#6b7385;margin:10px 0 4px}
  input,textarea{width:100%;padding:9px 11px;border:1px solid #d3d7e2;border-radius:8px;font:inherit;box-sizing:border-box}
  .btn{display:inline-block;padding:8px 14px;border:1px solid #d3d7e2;border-radius:8px;background:#fff;color:#1d2433;text-decoration:none;cursor:pointer;font:inherit}
  .p{background:#4f46e5;color:#fff;border-color:#4f46e5}.d{color:#dc2626;border-color:#dc2626}
  .err{background:#fee2e2;color:#991b1b;padding:10px;border-radius:8px;margin-bottom:10px}
  .ok{background:#dcfce7;color:#166534;padding:10px;border-radius:8px;margin-bottom:10px}
  table{width:100%;border-collapse:collapse}th,td{text-align:left;padding:10px 8px;border-bottom:1px solid #e1e4ec}
  .scroll{overflow-x:auto}.row{display:flex;gap:8px}.row input{flex:1}
  .g2{display:grid;grid-template-columns:1fr 1fr;gap:0 12px}
  @media(max-width:560px){.g2{grid-template-columns:1fr}}
</style></head><body><div class="wrap">
<?php if (!empty($_SESSION['uid'])): ?>
<nav><strong>Halo, <?= e($_SESSION['name']) ?></strong>
  <span><a class="btn" href="index.php">Data</a> <a class="btn" href="profile.php">Profil</a> <a class="btn" href="logout.php">Keluar</a></span></nav>
<?php endif;
}

function page_end() { ?>
</div>
<script>
function togglePw(cb){
  cb.form.querySelectorAll('input[name^="password"]').forEach(i => i.type = cb.checked ? 'text' : 'password');
}
</script>
</body></html>
<?php }
