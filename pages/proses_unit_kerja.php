<?php
ob_start();
require_once 'koneksi/dbkoneksi.php';

$_proses = $_POST['proses'] ?? '';
$_nama = $_POST['nama'] ?? '';

$status = '';
$message = '';

try {
    if ($_proses === 'Batal') {
        header('Location: index.php?page=unitkerja');
        exit;
    }

    if ($_proses === "Tambah") {
        $data = [$_nama];
        $sql = "INSERT INTO unit_kerja(nama) VALUES(?)";
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);

        $status = 'success';
        $message = 'Data Unit Kerja berhasil ditambahkan';
        
    } elseif ($_proses === "Ubah") {
        $_idx = $_POST['id'] ?? '';
        $data = [$_nama,  $_idx];
        $sql = "UPDATE unit_kerja SET nama = ? WHERE id = ?";
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);

        $status = 'success';
        $message = 'Data Unit Kerja berhasil diubah';

    } elseif (isset($_GET['proses']) && $_GET['proses'] === 'Hapus' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "DELETE FROM unit_kerja WHERE id = ?";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$id]);

        $status = 'success';
        $message = 'Data Unit Kerja berhasil dihapus';
    }

    ob_end_clean();
    header('Location: index.php?page=unitkerja&status='.$status.'&message='.urlencode($message));
    exit;

} catch (PDOException $e) {
    ob_end_clean();
    header('Location: index.php?page=unitkerja&status=error&message='.urlencode('Terjadi kesalahan: '.$e->getMessage()));
    exit;
}
?>

