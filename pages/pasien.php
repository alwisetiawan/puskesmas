<?php
// Start output buffering
ob_start();

// Include database connection
include './koneksi/dbkoneksi.php';

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$offset = ($page - 1) * $limit;

// Get patient data with pagination
$sql = "SELECT * FROM pasien LIMIT $limit OFFSET $offset";
$query = $dbh->query($sql);
$dataPasien = $query->fetchAll();

// Get total count for pagination
$totalSql = "SELECT COUNT(*) FROM pasien";
$totalQuery = $dbh->query($totalSql);
$totalData = $totalQuery->fetchColumn();
$totalPages = ceil($totalData / $limit);
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <h2 class="card-header">Data Pasien</h2>
        <div class="card-body">
            <!-- SweetAlert Notifications -->
            <?php if (isset($_GET['status'])): ?>
                <div class="alert alert-<?= $_GET['status'] === 'success' ? 'success' : 'danger' ?> alert-dismissible">
                    <?= urldecode($_GET['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive text-nowrap">
                <div class="d-flex justify-content-between mb-3">
                    <div class="search-box">
                        <input type="text" class="form-control" id="searchInput" placeholder="Cari pasien...">
                    </div>
                    <a href="index.php?page=form_pasien" class="btn btn-primary d-flex align-items-center">
                        <i class="tf-icons bx bx-plus"></i> <span class="ms-1">Tambah Data</span>
                    </a>
                </div>
                
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Tempat Lahir</th>
                            <th>Tanggal Lahir</th>
                            <th>Gender</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($dataPasien) > 0): ?>
                            <?php $no = $offset + 1; foreach($dataPasien as $pasien): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($pasien['kode']); ?></td>
                                <td><?= htmlspecialchars($pasien['nama']); ?></td>
                                <td><?= htmlspecialchars($pasien['tmp_lahir']); ?></td>
                                <td><?= date('d/m/Y', strtotime($pasien['tgl_lahir'])); ?></td>
                                <td>
                                    <span class="badge bg-<?= $pasien['gender'] === 'L' ? 'primary' : 'danger' ?>">
                                        <?= $pasien['gender'] === 'L' ? 'Laki-laki' : 'Perempuan' ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($pasien['email']); ?></td>
                                <td><?= htmlspecialchars($pasien['alamat']); ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="index.php?page=form_pasien&id=<?= $pasien['id']; ?>" 
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        <a href="index.php?page=proses_pasien&id=<?= $pasien['id']; ?>&proses=Hapus" class="btn btn-sm btn-danger delete-btn" 
                                                data-id="<?= $pasien['id']; ?>" 
                                                data-name="<?= htmlspecialchars($pasien['nama']); ?>" 
                                                title="Hapus">
                                            <i class="bx bx-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data pasien</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page - 1; ?>">
                                <i class="bx bx-chevron-left"></i>
                            </a>
                        </li>
                        
                        <?php 
                        // Show limited pagination links
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
                            <a class="page-link" href="?page=<?= $page + 1; ?>">
                                <i class="bx bx-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for enhanced functionality -->
<script>
// Delete confirmation modal
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function() {
        const patientId = this.getAttribute('data-id');
        const patientName = this.getAttribute('data-name');
        
        if (confirm(`Yakin ingin menghapus data pasien ${patientName}?`)) {
            window.location.href = `proses_pasien.php?proses=Hapus&id=${patientId}`;
        }
    });
});

// Simple search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchValue) ? '' : 'none';
    });
});
</script>

<?php
// Clean output buffer
ob_end_flush();
?>
