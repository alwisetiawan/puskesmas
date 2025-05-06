<?php 
include_once './koneksi/dbkoneksi.php';

$_idx = $_GET['id'] ?? null;
if ($_idx) {
    $sql = "SELECT * FROM paramedik WHERE id=?";
    $st = $dbh->prepare($sql);
    $st->execute([$_idx]);
    $row = $st->fetch();
    $tombol = "Ubah";
} else {
    $tombol = "Tambah";
}

$unit_kerja = $dbh->query("SELECT * FROM unit_kerja");
?>

<div class="col-md-12">
  <div class="card mb-4">
    <h5 class="card-header">Form Paramedik</h5>
    <div class="card-body demo-vertical-spacing demo-only-element">
      <form method="POST" action="index.php?page=proses_paramedik">

        <!-- Hidden ID jika edit -->
        <?php if ($_idx): ?>
          <input type="hidden" name="id" value="<?= $_idx ?>">
        <?php endif; ?>

        <!-- Nama -->
        <div class="input-group mb-3">
          <span class="input-group-text">Nama</span>
          <input type="text" name="nama" class="form-control" placeholder="Masukkan nama paramedik" required value="<?= $row['nama'] ?? '' ?>">
        </div>

        <!-- Tempat Lahir -->
        <div class="input-group mb-3">
          <span class="input-group-text">Tempat Lahir</span>
          <input type="text" name="tmp_lahir" class="form-control" placeholder="Masukkan tempat lahir" value="<?= $row['tmp_lahir'] ?? '' ?>">
        </div>

        <!-- Tanggal Lahir -->
        <div class="input-group mb-3">
          <span class="input-group-text">Tanggal Lahir</span>
          <input type="date" name="tgl_lahir" class="form-control" value="<?= $row['tgl_lahir'] ?? '' ?>">
        </div>

        <!-- Jenis Kelamin -->
        <label class="form-label d-block">Jenis Kelamin</label>
        <div class="form-check form-check-inline mb-3">
          <input class="form-check-input" type="radio" name="gender" value="L" id="genderL" <?= (isset($row['gender']) && $row['gender'] == 'L') ? 'checked' : '' ?>>
          <label class="form-check-label" for="genderL">Laki-Laki</label>
        </div>
        <div class="form-check form-check-inline mb-3">
          <input class="form-check-input" type="radio" name="gender" value="P" id="genderP" <?= (isset($row['gender']) && $row['gender'] == 'P') ? 'checked' : '' ?>>
          <label class="form-check-label" for="genderP">Perempuan</label>
        </div>

        <!-- Kategori -->
        <div class="mb-3">
          <label for="kategori" class="form-label">Kategori</label>
          <select class="form-select" name="kategori" id="kategori">
            <option value="" disabled <?= !isset($row['kategori']) ? 'selected' : '' ?>>Pilih Kategori</option>
            <option value="dokter" <?= (isset($row['kategori']) && $row['kategori'] == 'dokter') ? 'selected' : '' ?>>Dokter</option>
            <option value="perawat" <?= (isset($row['kategori']) && $row['kategori'] == 'perawat') ? 'selected' : '' ?>>Perawat</option>
            <option value="bidan" <?= (isset($row['kategori']) && $row['kategori'] == 'bidan') ? 'selected' : '' ?>>Bidan</option>
            <option value="apoteker" <?= (isset($row['kategori']) && $row['kategori'] == 'apoteker') ? 'selected' : '' ?>>Apoteker</option>
          </select>
        </div>

        <!-- Telpon -->
        <div class="input-group mb-3">
          <span class="input-group-text">Telpon</span>
          <input type="text" name="telpon" class="form-control" placeholder="Masukkan nomor telepon" value="<?= $row['telpon'] ?? '' ?>">
        </div>

        <!-- Unit Kerja -->
        <div class="mb-3">
          <label for="unit_kerja" class="form-label">Unit Kerja</label>
          <select class="form-select" name="unit_kerja" id="unit_kerja">
            <option value="" disabled <?= !isset($row['unit_kerja_id']) ? 'selected' : '' ?>>Pilih Unit Kerja</option>
            <?php foreach($unit_kerja as $unit): ?>
              <option value="<?= $unit['id'] ?>" <?= (isset($row['unit_kerja_id']) && $row['unit_kerja_id'] == $unit['id']) ? 'selected' : '' ?>>
                <?= $unit['nama'] ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Alamat -->
        <div class="input-group mb-3">
          <span class="input-group-text">Alamat</span>
          <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat"><?= $row['alamat'] ?? '' ?></textarea>
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
