<?php
require 'config.php';
if (!empty($_SESSION['uid'])) { header('Location: index.php'); exit; }
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  $s = $pdo->prepare('SELECT * FROM users WHERE username=?');
  $s->execute([strtolower(trim($_POST['username'] ?? ''))]);
  $u = $s->fetch();
  if ($u && password_verify($_POST['password'] ?? '', $u['password'])) {
    session_regenerate_id(true);
    $_SESSION['uid'] = $u['id'];
    $_SESSION['name'] = $u['full_name'];
    header('Location: index.php'); exit;
  }
  $error = 'Username atau password salah.';
}
page_start('Masuk'); ?>
<div class="card" style="max-width:420px;margin:8vh auto">
  <h2>Masuk</h2>
  <?php if (isset($_GET['registered'])): ?><div class="ok">Akun berhasil dibuat. Silakan masuk.</div><?php endif; ?>
  <?php if ($error): ?><div class="err"><?= e($error) ?></div><?php endif; ?>
  <form method="post"><?= csrf_field() ?>
    <label>Username</label><input name="username" required autofocus>
    <label>Password</label><input type="password" name="password" required>
    <label style="display:flex;gap:6px;align-items:center;cursor:pointer"><input type="checkbox" style="width:auto" onclick="togglePw(this)"> Tampilkan password</label>
    <p><button class="btn p" style="width:100%">Masuk</button></p>
  </form>
  <p>Belum punya akun? <a href="register.php">Daftar</a></p>
</div>
<?php page_end();
