<?php
require 'koneksi.php';
wajib_login('admin');

$pesan = '';

// simpan (tambah / ubah)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = (int) $_POST['id_obat'];
    $nama   = trim($_POST['nama_obat']);
    $satuan = trim($_POST['satuan']);

    if ($id) {
        $stmt = $db->prepare('UPDATE tb_obat SET nama_obat = ?, satuan = ? WHERE id_obat = ?');
        $stmt->bind_param('ssi', $nama, $satuan, $id);
    } else {
        $stmt = $db->prepare('INSERT INTO tb_obat (nama_obat, satuan) VALUES (?, ?)');
        $stmt->bind_param('ss', $nama, $satuan);
    }
    $stmt->execute();
    header('Location: obat.php');
    exit;
}

// hapus
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $stmt = $db->prepare('DELETE FROM tb_obat WHERE id_obat = ?');
    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
        $pesan = 'Obat tidak bisa dihapus karena sudah punya riwayat pengadaan.';
    } else {
        header('Location: obat.php');
        exit;
    }
}

// data untuk form ubah
$edit = ['id_obat' => 0, 'nama_obat' => '', 'satuan' => ''];
if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $hasil = $db->query("SELECT * FROM tb_obat WHERE id_obat = $id")->fetch_assoc();
    if ($hasil) $edit = $hasil;
}

$daftar = $db->query('SELECT * FROM tb_obat ORDER BY nama_obat');
require 'header.php';
?>
<h2>Data Obat</h2>
<?php if ($pesan): ?><div class="alert"><?= e($pesan) ?></div><?php endif; ?>

<form method="post" class="kartu">
  <input type="hidden" name="id_obat" value="<?= $edit['id_obat'] ?>">
  <label>Nama obat</label>
  <input type="text" name="nama_obat" value="<?= e($edit['nama_obat']) ?>" required>
  <label>Satuan</label>
  <input type="text" name="satuan" value="<?= e($edit['satuan']) ?>" placeholder="strip, botol, box" required>
  <button type="submit"><?= $edit['id_obat'] ? 'Simpan perubahan' : 'Tambah obat' ?></button>
  <?php if ($edit['id_obat']): ?><a href="obat.php" class="batal">Batal</a><?php endif; ?>
</form>

<div class="kartu tabel">
  <table>
    <tr><th>Obat</th><th>Satuan</th><th>Stok</th><th></th></tr>
    <?php while ($r = $daftar->fetch_assoc()): ?>
    <tr>
      <td><?= e($r['nama_obat']) ?></td>
      <td><?= e($r['satuan']) ?></td>
      <td><?= $r['stok'] ?></td>
      <td class="aksi">
        <a href="?edit=<?= $r['id_obat'] ?>">Ubah</a>
        <a href="?hapus=<?= $r['id_obat'] ?>" class="merah" onclick="return confirm('Hapus obat ini?')">Hapus</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</div>
<?php require 'footer.php'; ?>
