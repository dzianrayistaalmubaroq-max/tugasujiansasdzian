<?php
require 'koneksi.php';
wajib_login();
$u = $_SESSION['user'];

// supplier menambah pengadaan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $u['role'] === 'supplier') {
    $id_obat = (int) $_POST['id_obat'];
    $jumlah  = (int) $_POST['jumlah_beli'];
    $harga   = (float) $_POST['harga_beli'];
    $tgl     = $_POST['tgl_masuk'];

    if ($jumlah > 0 && $harga > 0) {
        $stmt = $db->prepare('INSERT INTO tb_pengadaan (id_obat, id_user, jumlah_beli, harga_beli, tgl_masuk) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('iiids', $id_obat, $u['id_user'], $jumlah, $harga, $tgl);
        $stmt->execute();
    }
    header('Location: pengadaan.php');
    exit;
}

$sql = 'SELECT p.*, o.nama_obat, o.satuan, s.nama AS supplier
        FROM tb_pengadaan p
        JOIN tb_obat o ON o.id_obat = p.id_obat
        JOIN tb_user s ON s.id_user = p.id_user';
if ($u['role'] === 'supplier') {
    $sql .= ' WHERE p.id_user = ' . (int) $u['id_user'];
}
$daftar = $db->query($sql . ' ORDER BY p.tgl_masuk DESC, p.id_pengadaan DESC');
$obat   = $db->query('SELECT id_obat, nama_obat, satuan FROM tb_obat ORDER BY nama_obat');

require 'header.php';
?>
<h2>Pengadaan Obat</h2>

<?php if ($u['role'] === 'supplier'): ?>
<form method="post" class="kartu">
  <label>Obat</label>
  <select name="id_obat" required>
    <?php while ($o = $obat->fetch_assoc()): ?>
      <option value="<?= $o['id_obat'] ?>"><?= e($o['nama_obat']) ?> (<?= e($o['satuan']) ?>)</option>
    <?php endwhile; ?>
  </select>
  <label>Jumlah</label>
  <input type="number" name="jumlah_beli" min="1" required>
  <label>Harga satuan</label>
  <input type="number" name="harga_beli" min="1" step="0.01" required>
  <label>Tanggal masuk</label>
  <input type="date" name="tgl_masuk" value="<?= date('Y-m-d') ?>" required>
  <button type="submit">Simpan pengadaan</button>
</form>
<?php endif; ?>

<div class="kartu tabel">
  <table>
    <tr>
      <th>Tanggal</th><th>Obat</th>
      <?php if ($u['role'] === 'admin'): ?><th>Supplier</th><?php endif; ?>
      <th>Jumlah</th><th>Harga</th><th>Total</th>
    </tr>
    <?php while ($r = $daftar->fetch_assoc()): ?>
    <tr>
      <td><?= date('d/m/Y', strtotime($r['tgl_masuk'])) ?></td>
      <td><?= e($r['nama_obat']) ?></td>
      <?php if ($u['role'] === 'admin'): ?><td><?= e($r['supplier']) ?></td><?php endif; ?>
      <td><?= $r['jumlah_beli'] ?> <?= e($r['satuan']) ?></td>
      <td><?= rp($r['harga_beli']) ?></td>
      <td><?= rp($r['jumlah_beli'] * $r['harga_beli']) ?></td>
    </tr>
    <?php endwhile; ?>
  </table>
</div>
<?php require 'footer.php'; ?>
