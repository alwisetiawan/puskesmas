<?php
// Start output buffering
ob_start();

// Include database connection
require_once 'koneksi/dbkoneksi.php';

// Get form data sesuai struktur tabel
$_nama = $_POST['nama'] ?? '';
$_gender = $_POST['gender'] ?? '';
$_tmp_lahir = $_POST['tmp_lahir'] ?? '';
$_tgl_lahir = $_POST['tgl_lahir'] ?? '';
$_kategori = $_POST['kategori'] ?? '';
$_telpon = $_POST['telpon'] ?? '';
$_alamat = $_POST['alamat'] ?? '';
$_unit_kerja_id = $_POST['unit_kerja'] ?? ''; // ambil dari select option
$_proses = $_POST['proses'] ?? '';

// Initialize status
$status = '';
$message = '';

try {
    if ($_proses == "Tambah") {
        // Tambah data paramedik
        $data = [
            $_nama,
            $_gender,
            $_tmp_lahir,
            $_tgl_lahir,
            $_kategori,
            $_telpon,
            $_alamat,
            $_unit_kerja_id
        ];

        $sql = "INSERT INTO paramedik (nama, gender, tmp_lahir, tgl_lahir, kategori, telpon, alamat, unit_kerja_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);

        $status = 'success';
        $message = 'Data paramedik berhasil ditambahkan';

    } elseif ($_proses == "Ubah") {
        // Ubah data paramedik
        $_idx = $_POST['id'] ?? '';
        $data = [
            $_nama,
            $_gender,
            $_tmp_lahir,
            $_tgl_lahir,
            $_kategori,
            $_telpon,
            $_alamat,
            $_unit_kerja_id,
            $_idx
        ];

        $sql = "UPDATE paramedik SET 
                nama = ?, gender = ?, tmp_lahir = ?, tgl_lahir = ?, kategori = ?, telpon = ?, alamat = ?, unit_kerja_id = ?
                WHERE id = ?";

        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);

        $status = 'success';
        $message = 'Data paramedik berhasil diubah';

    } elseif (isset($_GET['proses']) && $_GET['proses'] === 'Hapus' && isset($_GET['id'])) {
        // Hapus data paramedik
        $id = $_GET['id'];

        $sql = "DELETE FROM paramedik WHERE id = ?";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$id]);

        $status = 'success';
        $message = 'Data paramedik berhasil dihapus';
    }

    // Redirect ke halaman paramedik
    ob_end_clean();
    header('Location: index.php?page=paramedik&status='.$status.'&message='.urlencode($message));
    exit;

} catch (PDOException $e) {
    ob_end_clean();
    header('Location: index.php?page=paramedik&status=error&message='.urlencode('Terjadi kesalahan: '.$e->getMessage()));
    exit;
}
?>
