<?php
ob_start();
require_once 'koneksi/dbkoneksi.php';

$_proses = $_POST['proses'] ?? '';
$_nama = $_POST['nama'] ?? '';
$_kec_id = $_POST['kec_id'] ?? '';

$status = '';
$message = '';

try {
    if ($_proses === 'Batal') {
        header('Location: index.php?page=kelurahan');
        exit;
    }

    if ($_proses === "Tambah") {
        $data = [$_nama, $_kec_id];
        $sql = "INSERT INTO kelurahan(nama, kec_id) VALUES(?, ?)";
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);

        $status = 'success';
        $message = 'Data kelurahan berhasil ditambahkan';
        
    } elseif ($_proses === "Ubah") {
        $_idx = $_POST['id'] ?? '';
        $data = [$_nama, $_kec_id, $_idx];
        $sql = "UPDATE kelurahan SET nama = ?, kec_id = ? WHERE id = ?";
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);

        $status = 'success';
        $message = 'Data kelurahan berhasil diubah';

    } elseif (isset($_GET['proses']) && $_GET['proses'] === 'Hapus' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "DELETE FROM kelurahan WHERE id = ?";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$id]);

        $status = 'success';
        $message = 'Data kelurahan berhasil dihapus';
    }

    ob_end_clean();
    header('Location: index.php?page=kelurahan&status='.$status.'&message='.urlencode($message));
    exit;

} catch (PDOException $e) {
    ob_end_clean();
    header('Location: index.php?page=kelurahan&status=error&message='.urlencode('Terjadi kesalahan: '.$e->getMessage()));
    exit;
}
?>
