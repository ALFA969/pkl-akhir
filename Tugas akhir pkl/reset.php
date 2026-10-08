<?php
// Membuat ulang database & tabel dari nol. SEMUA DATA LAMA AKAN HILANG.
// Hapus file ini setelah selesai dipakai.
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '',   // samakan dengan config.php
      [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $pdo->exec('DROP DATABASE IF EXISTS crud_app');
    $pdo->exec('CREATE DATABASE crud_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    $pdo->exec('USE crud_app');
    $pdo->exec('CREATE TABLE users (
      id INT AUTO_INCREMENT PRIMARY KEY,
      username VARCHAR(20) NOT NULL UNIQUE,
      password VARCHAR(255) NOT NULL,
      full_name VARCHAR(100) NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)');
    $pdo->exec('CREATE TABLE contacts (
      id INT AUTO_INCREMENT PRIMARY KEY,
      user_id INT NOT NULL,
      name VARCHAR(100) NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE)');
    $msg = '<p style="color:green">Berhasil! Database dibuat ulang. <a href="register.php">Daftar sekarang</a></p>';
  } catch (PDOException $e) {
    $msg = '<p style="color:red">Gagal: ' . htmlspecialchars($e->getMessage()) . '</p>';
  }
}
?>
<!DOCTYPE html><html lang="id"><meta charset="utf-8"><title>Reset Database</title>
<body style="font-family:system-ui;max-width:460px;margin:8vh auto;padding:16px">
<h2>Reset database</h2>
<p>Ini akan menghapus database <b>crud_app</b> beserta semua datanya, lalu membuatnya ulang.</p>
<?= $msg ?>
<form method="post" onsubmit="return confirm('Yakin hapus semua data?')"><button>Reset sekarang</button></form>
</body></html>
