<?php
require 'koneksi.php';

if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $pass     = hash('sha256', $_POST['pass']);

    $stmt = $db->prepare('SELECT id_user, username, role, nama FROM tb_user WHERE username = ? AND pass = ?');
    $stmt->bind_param('ss', $username, $pass);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user) {
        $_SESSION['user'] = $user;
        header('Location: dashboard.php');
        exit;
    }
    $error = 'Username atau password salah.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Masuk | Klinik</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="login">
  <form method="post" class="kartu login-box">
    <h1>Pengadaan Obat</h1>
    <p class="muted">Masuk untuk melanjutkan</p>
    <?php if ($error): ?><div class="alert"><?= e($error) ?></div><?php endif; ?>
    <label>Username</label>
    <input type="text" name="username" required autofocus>
    <label>Password</label>
    <input type="password" name="pass" required>
    <button type="submit">Masuk</button>
  </form>
</body>
</html>
