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

// Get paramedik data with pagination
$sql = "SELECT * FROM paramedik LIMIT $limit OFFSET $offset";
$query = $dbh->query($sql);
$dataParamedik = $query->fetchAll();

// Get total count for pagination
$totalSql = "SELECT COUNT(*) FROM paramedik";
$totalQuery = $dbh->query($totalSql);
$totalData = $totalQuery->fetchColumn();
$totalPages = ceil($totalData / $limit);

$unit_kerja = $dbh->query("SELECT * FROM unit_kerja");


?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <h2 class="card-header">Data Paramedis</h2>
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
                        <input type="text" class="form-control" id="searchInput" placeholder="Cari paramedis...">
                    </div>
                    <a href="index.php?page=form_paramedik" class="btn btn-primary d-flex align-items-center">
                        <i class="tf-icons bx bx-plus"></i> <span class="ms-1">Tambah Data</span>
                    </a>
                </div>
                
                <table class="table table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Gender</th>
                        <th>Tempat Lahir</th>
                        <th>Tanggal Lahir</th>
                        <th>Kategori</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th>Unit Kerja</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($dataParamedik) > 0): ?>
                        <?php $no = $offset + 1; foreach($dataParamedik as $paramedik): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($paramedik['nama']); ?></td>
                            <td>
                                <span class="badge bg-<?= $paramedik['gender'] === 'L' ? 'primary' : 'danger' ?>">
                                    <?= $paramedik['gender'] === 'L' ? 'Laki-laki' : 'Perempuan' ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($paramedik['tmp_lahir']); ?></td>
                            <td><?= date('d/m/Y', strtotime($paramedik['tgl_lahir'])); ?></td>
                            <td><?= htmlspecialchars($paramedik['kategori']); ?></td>
                            <td><?= htmlspecialchars($paramedik['telpon']); ?></td>
                            <td><?= htmlspecialchars($paramedik['alamat']); ?></td>
                            <td><?= htmlspecialchars($paramedik['unit_kerja_id'] ? $dbh->query("SELECT nama FROM unit_kerja WHERE id = '{$paramedik['unit_kerja_id']}'")->fetchColumn() : '-'); ?></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="index.php?page=form_paramedik&id=<?= $paramedik['id']; ?>" 
                                    class="btn btn-sm btn-warning" title="Edit">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    <a href="index.php?page=proses_paramedik&id=<?= $paramedik['id']; ?>&proses=Hapus" 
                                    class="btn btn-sm btn-danger delete-btn" 
                                    data-id="<?= $paramedik['id']; ?>" 
                                    data-name="<?= htmlspecialchars($paramedik['nama']); ?>" 
                                    title="Hapus">
                                        <i class="bx bx-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center">Tidak ada data paramedik</td>
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
        const paramedikId = this.getAttribute('data-id');
        const paramedikName = this.getAttribute('data-name');
        
        if (confirm(`Yakin ingin menghapus data paramedik ${paramedikName}?`)) {
            window.location.href = `proses_paramedik.php?proses=Hapus&id=${paramedikId}`;
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
