<?php
require 'config.php';
require_login();

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$name = '';

if ($id) {
  $s = $pdo->prepare('SELECT name FROM contacts WHERE id=? AND user_id=?');
  $s->execute([$id, $_SESSION['uid']]);
  $row = $s->fetch() ?: die('Data tidak ditemukan.');
  $name = $row['name'];
}
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  $name = trim($_POST['name'] ?? '');
  if ($name === '') $error = 'Nama wajib diisi.';
  else {
    if ($id) $pdo->prepare('UPDATE contacts SET name=? WHERE id=? AND user_id=?')->execute([$name, $id, $_SESSION['uid']]);
    else     $pdo->prepare('INSERT INTO contacts (name,user_id) VALUES (?,?)')->execute([$name, $_SESSION['uid']]);
    header('Location: index.php'); exit;
  }
}
page_start($id ? 'Edit Data' : 'Tambah Data'); ?>
<div class="card" style="max-width:420px">
  <h2><?= $id ? 'Edit' : 'Tambah' ?> data</h2>
  <?php if ($error): ?><div class="err"><?= e($error) ?></div><?php endif; ?>
  <form method="post"><?= csrf_field() ?><input type="hidden" name="id" value="<?= $id ?>">
    <label>Nama *</label><input name="name" value="<?= e($name) ?>" required autofocus>
    <p><button class="btn p">Simpan</button> <a class="btn" href="index.php">Batal</a></p>
  </form>
</div>
<?php page_end();
