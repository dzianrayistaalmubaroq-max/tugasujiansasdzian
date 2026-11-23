<?php wajib_login(); $u = $_SESSION['user']; $hal = basename($_SERVER['PHP_SELF']); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Klinik | Pengadaan Obat</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<nav>
  <strong>Klinik</strong>
  <div class="menu">
    <a href="dashboard.php" class="<?= $hal == 'dashboard.php' ? 'on' : '' ?>">Beranda</a>
    <?php if ($u['role'] === 'admin'): ?>
      <a href="obat.php" class="<?= $hal == 'obat.php' ? 'on' : '' ?>">Obat</a>
    <?php endif; ?>
    <a href="pengadaan.php" class="<?= $hal == 'pengadaan.php' ? 'on' : '' ?>">Pengadaan</a>
    <a href="logout.php">Keluar</a>
  </div>
</nav>
<main>
