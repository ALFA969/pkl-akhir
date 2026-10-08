<?php
require 'config.php';
require_login();

$s = $pdo->prepare('SELECT * FROM users WHERE id=?');
$s->execute([$_SESSION['uid']]);
$u = $s->fetch();
$error = ''; $ok = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  $u['full_name'] = trim($_POST['full_name'] ?? '');
  if ($u['full_name'] === '') $error = 'Nama wajib diisi.';
  else {
    $pdo->prepare('UPDATE users SET full_name=? WHERE id=?')->execute([$u['full_name'], $u['id']]);
    $_SESSION['name'] = $u['full_name'];
    $ok = true;
  }
}
page_start('Profil'); ?>
<div class="card" style="max-width:420px">
  <h2>Profil saya</h2>
  <?php if ($ok): ?><div class="ok">Profil diperbarui.</div><?php endif; ?>
  <?php if ($error): ?><div class="err"><?= e($error) ?></div><?php endif; ?>
  <form method="post"><?= csrf_field() ?>
    <label>Username</label><input value="<?= e($u['username']) ?>" disabled>
    <label>Nama lengkap *</label><input name="full_name" value="<?= e($u['full_name']) ?>" required>
    <p><button class="btn p">Simpan</button></p>
  </form>
</div>
<?php page_end();
