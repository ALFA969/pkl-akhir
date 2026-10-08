<?php
require 'config.php';
require_login();

$q = trim($_GET['q'] ?? '');
$s = $pdo->prepare('SELECT * FROM contacts WHERE user_id=? AND name LIKE ? ORDER BY id DESC');
$s->execute([$_SESSION['uid'], "%$q%"]);
$rows = $s->fetchAll();

page_start('Data'); ?>
<div class="card">
  <h2>Data</h2>
  <form class="row" method="get">
    <input name="q" value="<?= e($q) ?>" placeholder="Cari nama…">
    <button class="btn">Cari</button>
    <a class="btn p" href="form.php">+ Tambah</a>
  </form><br>
  <div class="scroll"><table>
    <tr><th>Nama</th><th></th></tr>
    <?php foreach ($rows as $r): ?>
    <tr>
      <td><?= e($r['name']) ?></td>
      <td style="white-space:nowrap;text-align:right">
        <a class="btn" href="form.php?id=<?= $r['id'] ?>">Edit</a>
        <form method="post" action="delete.php" style="display:inline" onsubmit="return confirm('Hapus data ini?')">
          <?= csrf_field() ?><input type="hidden" name="id" value="<?= $r['id'] ?>">
          <button class="btn d">Hapus</button>
        </form>
      </td>
    </tr>
    <?php endforeach; if (!$rows): ?>
    <tr><td colspan="2" style="text-align:center;color:#6b7385">Belum ada data.</td></tr>
    <?php endif; ?>
  </table></div>
</div>
<?php page_end();
