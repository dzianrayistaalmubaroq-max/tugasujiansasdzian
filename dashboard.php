<?php
require 'koneksi.php';
require 'header.php';

$jml_obat  = $db->query('SELECT COUNT(*) FROM tb_obat')->fetch_row()[0];
$tot_stok  = $db->query('SELECT COALESCE(SUM(stok),0) FROM tb_obat')->fetch_row()[0];
$bulan_ini = $db->query("SELECT COALESCE(SUM(jumlah_beli * harga_beli),0) FROM tb_pengadaan
                         WHERE MONTH(tgl_masuk) = MONTH(CURDATE()) AND YEAR(tgl_masuk) = YEAR(CURDATE())")->fetch_row()[0];
$menipis   = $db->query('SELECT nama_obat, satuan, stok FROM tb_obat WHERE stok < 20 ORDER BY stok');
?>
<h2>Halo, <?= e($u['nama']) ?></h2>
<p class="muted">Ringkasan pengadaan obat klinik.</p>

<div class="grid">
  <div class="kartu stat"><span><?= $jml_obat ?></span>Jenis obat</div>
  <div class="kartu stat"><span><?= $tot_stok ?></span>Total stok</div>
  <div class="kartu stat"><span><?= rp($bulan_ini) ?></span>Pengadaan bulan ini</div>
</div>

<div class="kartu">
  <h3>Stok menipis</h3>
  <?php if ($menipis->num_rows == 0): ?>
    <p class="muted">Semua stok masih aman.</p>
  <?php else: ?>
  <table>
    <tr><th>Obat</th><th>Satuan</th><th>Stok</th></tr>
    <?php while ($r = $menipis->fetch_assoc()): ?>
      <tr><td><?= e($r['nama_obat']) ?></td><td><?= e($r['satuan']) ?></td><td><?= $r['stok'] ?></td></tr>
    <?php endwhile; ?>
  </table>
  <?php endif; ?>
</div>
<?php require 'footer.php'; ?>
