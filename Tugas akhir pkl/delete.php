<?php
require 'config.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  $pdo->prepare('DELETE FROM contacts WHERE id=? AND user_id=?')
      ->execute([(int)($_POST['id'] ?? 0), $_SESSION['uid']]);
}
header('Location: index.php');
