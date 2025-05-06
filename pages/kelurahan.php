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
$sql = "SELECT * FROM kelurahan LIMIT $limit OFFSET $offset";
$query = $dbh->query($sql);
$dataKelurahan = $query->fetchAll();

// Get total count for pagination
$totalSql = "SELECT COUNT(*) FROM kelurahan";
$totalQuery = $dbh->query($totalSql);
$totalData = $totalQuery->fetchColumn();
$totalPages = ceil($totalData / $limit);
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <h2 class="card-header">Data kelurahan</h2>
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
                        <input type="text" class="form-control" id="searchInput" placeholder="Cari kelurahan...">
                    </div>
                    <a href="index.php?page=form_kelurahan" class="btn btn-primary d-flex align-items-center">
                        <i class="tf-icons bx bx-plus"></i> <span class="ms-1">Tambah Data</span>
                    </a>
                </div>
                
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Kecamatan_id</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($dataKelurahan) > 0): ?>
                            <?php $no = $offset + 1; foreach($dataKelurahan as $kelurahan): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($kelurahan['nama']); ?></td>
                                <td><?= htmlspecialchars($kelurahan['kec_id']); ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="index.php?page=form_kelurahan&id=<?= $kelurahan['id']; ?>" 
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        <a href="index.php?page=proses_kelurahan&id=<?= $kelurahan['id']; ?>&proses=Hapus" class="btn btn-sm btn-danger delete-btn" 
                                                data-id="<?= $kelurahan['id']; ?>" 
                                                data-name="<?= htmlspecialchars($kelurahan['nama']); ?>" 
                                                title="Hapus">
                                            <i class="bx bx-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data kelurahan</td>
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
        
        if (confirm(`Yakin ingin menghapus data kelurahan ${patientName}?`)) {
            window.location.href = `proses_kelurahan.php?proses=Hapus&id=${patientId}`;
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
