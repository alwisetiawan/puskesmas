<?php
ob_start();
include './koneksi/dbkoneksi.php';

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$offset = ($page - 1) * $limit;

// Ambil data periksa dengan join pasien & dokter
$sql = "SELECT p.*, pa.nama AS pasien_nama, d.nama AS dokter_nama
        FROM periksa p
        JOIN pasien pa ON p.pasien_id = pa.id
        JOIN paramedik d ON p.dokter_id = d.id
        ORDER BY p.tanggal DESC
        LIMIT $limit OFFSET $offset";
$query = $dbh->query($sql);
$dataperiksa = $query->fetchAll();

// Hitung total data
$totalSql = "SELECT COUNT(*) FROM periksa";
$totalQuery = $dbh->query($totalSql);
$totalData = $totalQuery->fetchColumn();
$totalPages = ceil($totalData / $limit);
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <h2 class="card-header">Data Pemeriksaan</h2>
        <div class="card-body">
            <?php if (isset($_GET['status'])): ?>
                <div class="alert alert-<?= $_GET['status'] === 'success' ? 'success' : 'danger' ?> alert-dismissible">
                    <?= urldecode($_GET['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive text-nowrap">
                <div class="d-flex justify-content-between mb-3">
                    <div class="search-box">
                        <input type="text" class="form-control" id="searchInput" placeholder="Cari periksa...">
                    </div>
                    <a href="index.php?page=form_periksa" class="btn btn-primary d-flex align-items-center">
                        <i class="tf-icons bx bx-plus"></i> <span class="ms-1">Tambah periksa</span>
                    </a>
                </div>

                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Pasien</th>
                            <th>Dokter</th>
                            <th>Berat (kg)</th>
                            <th>Tinggi (cm)</th>
                            <th>Tensi</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($dataperiksa) > 0): ?>
                            <?php $no = $offset + 1; foreach($dataperiksa as $p): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= date('d/m/Y', strtotime($p['tanggal'])); ?></td>
                                <td><?= htmlspecialchars($p['pasien_nama']); ?></td>
                                <td><?= htmlspecialchars($p['dokter_nama']); ?></td>
                                <td><?= htmlspecialchars($p['berat']); ?></td>
                                <td><?= htmlspecialchars($p['tinggi']); ?></td>
                                <td><?= htmlspecialchars($p['tensi']); ?></td>
                                <td><?= htmlspecialchars($p['keterangan']); ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="index.php?page=form_periksa&id=<?= $p['id']; ?>" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        <a href="index.php?page=proses_periksa&id=<?= $p['id']; ?>&proses=Hapus" 
                                           class="btn btn-sm btn-danger delete-btn"
                                           data-id="<?= $p['id']; ?>" 
                                           data-name="<?= htmlspecialchars($p['pasien_nama']); ?>"
                                           title="Hapus">
                                            <i class="bx bx-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data periksa</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page - 1; ?>"><i class="bx bx-chevron-left"></i></a>
                        </li>
                        <?php 
                        $start = max(1, $page - 2);
                        $end = min($totalPages, $page + 2);

                        if ($start > 1) echo '<li class="page-item disabled"><a class="page-link">...</a></li>';

                        for ($i = $start; $i <= $end; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                        </li>
                        <?php endfor;

                        if ($end < $totalPages) echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
                        ?>
                        <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page + 1; ?>"><i class="bx bx-chevron-right"></i></a>
                        </li>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault();
        const id = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');

        if (confirm(`Yakin ingin menghapus data periksa milik pasien ${name}?`)) {
            window.location.href = `proses_periksa.php?proses=Hapus&id=${id}`;
        }
    });
});

document.getElementById('searchInput').addEventListener('keyup', function() {
    const value = this.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(value) ? '' : 'none';
    });
});
</script>

<?php ob_end_flush(); ?>
