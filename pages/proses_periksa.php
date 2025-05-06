<?php
// Start output buffering
ob_start();

// Include database connection
require_once 'koneksi/dbkoneksi.php';

// Ambil data dari form
$_tanggal     = $_POST['tanggal'] ?? '';
$_berat       = $_POST['berat'] ?? 0.0;
$_tinggi      = $_POST['tinggi'] ?? 0.0;
$_tensi       = $_POST['tensi'] ?? '';
$_keterangan  = $_POST['keterangan'] ?? '';
$_pasien_id   = $_POST['pasien_id'] ?? '';
$_dokter_id   = $_POST['dokter_id'] ?? '';
$_proses      = $_POST['proses'] ?? '';

// Initialize status
$status = '';
$message = '';

try {
    if ($_proses == "Tambah") {
        // Tambah data periksa
        $data = [
            $_tanggal,
            $_berat,
            $_tinggi,
            $_tensi,
            $_keterangan,
            $_pasien_id,
            $_dokter_id
        ];

        $sql = "INSERT INTO periksa (tanggal, berat, tinggi, tensi, keterangan, pasien_id, dokter_id)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);

        $status = 'success';
        $message = 'Data periksa berhasil ditambahkan';

    } elseif ($_proses == "Ubah") {
        // Ubah data periksa
        $_idx = $_POST['id'] ?? '';
        $data = [
            $_tanggal,
            $_berat,
            $_tinggi,
            $_tensi,
            $_keterangan,
            $_pasien_id,
            $_dokter_id,
            $_idx
        ];

        $sql = "UPDATE periksa SET 
                    tanggal = ?, berat = ?, tinggi = ?, tensi = ?, keterangan = ?, 
                    pasien_id = ?, dokter_id = ?
                WHERE id = ?";

        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);

        $status = 'success';
        $message = 'Data periksa berhasil diubah';

    } elseif (isset($_GET['proses']) && $_GET['proses'] === 'Hapus' && isset($_GET['id'])) {
        // Hapus data periksa
        $id = $_GET['id'];

        $sql = "DELETE FROM periksa WHERE id = ?";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$id]);

        $status = 'success';
        $message = 'Data periksa berhasil dihapus';
    }

    // Redirect ke halaman periksa
    ob_end_clean();
    header('Location: index.php?page=pemeriksaan&status='.$status.'&message='.urlencode($message));
    exit;

} catch (PDOException $e) {
    ob_end_clean();
    header('Location: index.php?page=pemeriksaan&status=error&message='.urlencode('Terjadi kesalahan: '.$e->getMessage()));
    exit;
}
?>
