<?php 
include_once './koneksi/dbkoneksi.php';

$_idx = $_GET['id'] ?? null;
if ($_idx) {
    $sql = "SELECT * FROM pasien WHERE id=?";
    $st = $dbh->prepare($sql);
    $st->execute([$_idx]);
    $row = $st->fetch();
    $tombol = "Ubah";
} else {
    $tombol = "Tambah";
}

$kelurahan = $dbh->query("SELECT * FROM kelurahan");
?>

<div class="col-md-12">
  <div class="card mb-4">
    <h5 class="card-header">Form Pasien</h5>
    <div class="card-body demo-vertical-spacing demo-only-element">
      <form method="POST" action="index.php?page=proses_pasien">
        
        <!-- Hidden ID jika edit -->
        <?php if ($_idx): ?>
          <input type="hidden" name="id" value="<?= $_idx ?>">
        <?php endif; ?>

        <!-- Kode -->
        <div class="input-group mb-3">
          <span class="input-group-text">Kode</span>
          <input type="text" name="kode" class="form-control" placeholder="Masukkan kode pasien" required value="<?= $row['kode'] ?? '' ?>">
        </div>

        <!-- Nama -->
        <div class="input-group mb-3">
          <span class="input-group-text">Nama</span>
          <input type="text" name="nama" class="form-control" placeholder="Masukkan nama pasien" required value="<?= $row['nama'] ?? '' ?>">
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

        <!-- Kelurahan -->
        <div class="mb-3">
          <label for="kelurahan" class="form-label">Kelurahan</label>
          <select class="form-select" name="kelurahan" id="kelurahan">
            <option value="" disabled <?= !isset($row['kelurahan_id']) ? 'selected' : '' ?>>Pilih Kelurahan</option>
            <?php foreach($kelurahan as $kel): ?>
              <option value="<?= $kel['id'] ?>" <?= (isset($row['kelurahan_id']) && $row['kelurahan_id'] == $kel['id']) ? 'selected' : '' ?>>
                <?= $kel['nama'] ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Email -->
        <div class="input-group mb-3">
          <span class="input-group-text">@</span>
          <input type="text" name="email" class="form-control" placeholder="Email" value="<?= $row['email'] ?? '' ?>">
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
