<?php 
include_once './koneksi/dbkoneksi.php';

$_idx = $_GET['id'] ?? null;
if ($_idx) {
    $sql = "SELECT * FROM kelurahan WHERE id=?";
    $st = $dbh->prepare($sql);
    $st->execute([$_idx]);
    $row = $st->fetch();
    $tombol = "Ubah";
} else {
    $tombol = "Tambah";
}

?>

<div class="col-md-12">
  <div class="card mb-4">
    <h5 class="card-header">Form kelurahan</h5>
    <div class="card-body demo-vertical-spacing demo-only-element">
      <form method="POST" action="index.php?page=proses_kelurahan">
        
        <!-- Hidden ID jika edit -->
        <?php if ($_idx): ?>
          <input type="hidden" name="id" value="<?= $_idx ?>">
        <?php endif; ?>

        <!-- kecamatan -->
        <div class="input-group mb-3">
          <span class="input-group-text">kecamatan</span>
          <input type="text" name="kec_id" class="form-control" placeholder="Masukkan kecamatan kelurahan" required value="<?= $row['kec_id'] ?? '' ?>">
        </div>

        <!-- Nama -->
        <div class="input-group mb-3">
          <span class="input-group-text">Nama</span>
          <input type="text" name="nama" class="form-control" placeholder="Masukkan nama kelurahan" required value="<?= $row['nama'] ?? '' ?>">
        </div>
        
        <!-- Tombol Submit -->
        <div class="d-flex justify-content-end">
          <button type="submit" name="proses" value="<?= $tombol ?>" class="btn btn-primary me-2"><?= $tombol ?></button>
          <button type="submit" name="proses" value="Batal" class="btn btn-secondary">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>
