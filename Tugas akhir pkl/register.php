<?php
require 'config.php';
$errors = [];
$v = ['username'=>'','full_name'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  foreach ($v as $k => $_) $v[$k] = trim($_POST[$k] ?? '');
  $v['username'] = strtolower($v['username']);
  $pw = $_POST['password'] ?? '';

  if ($v['full_name'] === '') $errors[] = 'Nama lengkap wajib diisi.';
  if (!preg_match('/^[a-z0-9_.]{3,20}$/', $v['username'])) $errors[] = 'Username 3–20 karakter: huruf, angka, _ atau .';
  if (strlen($pw) < 6) $errors[] = 'Password minimal 6 karakter.';
  if ($pw !== ($_POST['password2'] ?? '')) $errors[] = 'Konfirmasi password tidak cocok.';

  if (!$errors) {
    $s = $pdo->prepare('SELECT 1 FROM users WHERE username=?');
    $s->execute([$v['username']]);
    if ($s->fetch()) $errors[] = 'Username sudah dipakai.';
  }
  if (!$errors) {
    try {
      $pdo->prepare('INSERT INTO users (username,password,full_name) VALUES (?,?,?)')
          ->execute([$v['username'], password_hash($pw, PASSWORD_DEFAULT), $v['full_name']]);
      header('Location: login.php?registered=1'); exit;
    } catch (PDOException $ex) {
      $errors[] = 'Gagal menyimpan ke database: ' . $ex->getMessage()
                . ' — Buka reset.php untuk membuat ulang tabel.';
    }
  }
}
page_start('Daftar Akun'); ?>
<div class="card" style="max-width:420px;margin:6vh auto">
  <h2>Buat akun</h2>
  <?php foreach ($errors as $er): ?><div class="err"><?= e($er) ?></div><?php endforeach; ?>
  <form method="post"><?= csrf_field() ?>
    <label>Nama lengkap *</label><input name="full_name" value="<?= e($v['full_name']) ?>" required>
    <label>Username *</label><input name="username" value="<?= e($v['username']) ?>" required>
    <label>Password * (min. 6)</label><input type="password" name="password" required>
    <label>Ulangi password *</label><input type="password" name="password2" required>
    <label style="display:flex;gap:6px;align-items:center;cursor:pointer"><input type="checkbox" style="width:auto" onclick="togglePw(this)"> Tampilkan password</label>
    <p><button class="btn p" style="width:100%">Daftar</button></p>
  </form>
  <p>Sudah punya akun? <a href="login.php">Masuk</a></p>
</div>
<?php page_end();
