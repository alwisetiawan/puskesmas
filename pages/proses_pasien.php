<?php
// Start output buffering to prevent header errors
ob_start();

// Include database connection
require_once 'koneksi/dbkoneksi.php';

// Get form data
$_kode = $_POST['kode'] ?? '';
$_nama = $_POST['nama'] ?? '';
$_gender = $_POST['gender'] ?? '';
$_kelurahan = $_POST['kelurahan'] ?? '';
$_email = $_POST['email'] ?? '';
$_alamat = $_POST['alamat'] ?? '';
$_tgl_lahir = $_POST['tgl_lahir'] ?? '';
$_tmp_lahir = $_POST['tmp_lahir'] ?? '';
$_proses = $_POST['proses'] ?? '';

// Initialize status message
$status = '';
$message = '';

try {
    if ($_proses == "Tambah") {
        // INSERT operation
        $data = [
            $_kode,
            $_nama,
            $_tmp_lahir,
            $_tgl_lahir,
            $_gender,
            $_email,
            $_alamat,
            $_kelurahan
        ];
        
        $sql = "INSERT INTO pasien(kode, nama, tmp_lahir, tgl_lahir, gender, email, alamat, kelurahan_id) 
                VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);
        
        $status = 'success';
        $message = 'Data pasien berhasil ditambahkan';
        
    } elseif ($_proses == "Ubah") {
        // UPDATE operation
        $_idx = $_POST['id'] ?? '';
        $data = [
            $_kode,
            $_nama,
            $_tmp_lahir,
            $_tgl_lahir,
            $_gender,
            $_email,
            $_alamat,
            $_kelurahan,
            $_idx
        ];
        
        $sql = "UPDATE pasien SET 
                kode = ?, nama = ?, tmp_lahir = ?, tgl_lahir = ?,
                gender = ?, email = ?, alamat = ?, kelurahan_id = ? 
                WHERE id = ?"; 
        
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);
        
        $status = 'success';
        $message = 'Data pasien berhasil diubah';
        
    } elseif(isset($_GET['proses']) && $_GET['proses'] === 'Hapus' && isset($_GET['id'])) {
        $id = $_GET['id'];
    
        // Persiapkan dan eksekusi query hapus
        $sql = "DELETE FROM pasien WHERE id = ?";
        $stmt = $dbh->prepare($sql);
        $success = $stmt->execute([$id]);
        
        $status = 'success';
        $message = 'Data pasien berhasil dihapus';
    }

    // Clean output buffer and redirect with status
    ob_end_clean();
    header('Location: index.php?page=pasien&status='.$status.'&message='.urlencode($message));
    exit;

} catch (PDOException $e) {
    // Handle database errors
    ob_end_clean();
    header('Location: index.php?page=pasien&status=error&message='.urlencode('Terjadi kesalahan: '.$e->getMessage()));
    exit;
}
?>