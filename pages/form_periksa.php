<?php
include_once './koneksi/dbkoneksi.php';

$_id = $_GET['id'] ?? null;
if ($_id) {
    $sql = "SELECT * FROM periksa WHERE id=?";
    $st = $dbh->prepare($sql);
    $st->execute([$_id]);
    $row = $st->fetch();
    $tombol = "Ubah";
} else {
    $tombol = "Tambah";
}

// Ambil data pasien & dokter
$pasien = $dbh->query("SELECT id, nama FROM pasien");
$dokter = $dbh->query("SELECT id, nama FROM paramedik WHERE kategori = 'Dokter'");
?>

<div class="col-md-12">
  <div class="card mb-4">
    <h5 class="card-header">Form Pemeriksaan</h5>
    <div class="card-body demo-vertical-spacing demo-only-element">
      <form method="POST" action="index.php?page=proses_periksa">

        <?php if ($_id): ?>
          <input type="hidden" name="id" value="<?= $_id ?>">
        <?php endif; ?>

        <!-- Tanggal -->
        <div class="input-group mb-3">
          <span class="input-group-text">Tanggal</span>
          <input type="date" name="tanggal" class="form-control" required value="<?= $row['tanggal'] ?? '' ?>">
        </div>

        <!-- Berat -->
        <div class="input-group mb-3">
          <span class="input-group-text">Berat (kg)</span>
          <input type="number" step="0.1" name="berat" class="form-control" placeholder="Masukkan berat" required value="<?= $row['berat'] ?? '' ?>">
        </div>

        <!-- Tinggi -->
        <div class="input-group mb-3">
          <span class="input-group-text">Tinggi (cm)</span>
          <input type="number" step="0.1" name="tinggi" class="form-control" placeholder="Masukkan tinggi" required value="<?= $row['tinggi'] ?? '' ?>">
        </div>

        <!-- Tensi -->
        <div class="input-group mb-3">
          <span class="input-group-text">Tensi</span>
          <input type="text" name="tensi" class="form-control" placeholder="Contoh: 120/80" required value="<?= $row['tensi'] ?? '' ?>">
        </div>

        <!-- Keterangan -->
        <div class="input-group mb-3">
          <span class="input-group-text">Keterangan</span>
          <textarea name="keterangan" class="form-control" rows="2" placeholder="Masukkan keterangan"><?= $row['keterangan'] ?? '' ?></textarea>
        </div>

        <!-- Pasien -->
        <div class="mb-3">
          <label for="pasien" class="form-label">Pasien</label>
          <select class="form-select" name="pasien_id" id="pasien" required>
            <option value="" disabled <?= !isset($row['pasien_id']) ? 'selected' : '' ?>>Pilih Pasien</option>
            <?php foreach($pasien as $p): ?>
              <option value="<?= $p['id'] ?>" <?= (isset($row['pasien_id']) && $row['pasien_id'] == $p['id']) ? 'selected' : '' ?>>
                <?= $p['nama'] ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Dokter -->
        <div class="mb-3">
          <label for="dokter" class="form-label">Dokter</label>
          <select class="form-select" name="dokter_id" id="dokter" required>
            <option value="" disabled <?= !isset($row['dokter_id']) ? 'selected' : '' ?>>Pilih Dokter</option>
            <?php foreach($dokter as $d): ?>
              <option value="<?= $d['id'] ?>" <?= (isset($row['dokter_id']) && $row['dokter_id'] == $d['id']) ? 'selected' : '' ?>>
                <?= $d['nama'] ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Tombol -->
        <div class="d-flex justify-content-end">
          <button type="submit" name="proses" value="<?= $tombol ?>" class="btn btn-primary me-2"><?= $tombol ?></button>
          <button type="submit" name="proses" value="Batal" class="btn btn-secondary">Batal</button>
        </div>

      </form>
    </div>
  </div>
</div>
