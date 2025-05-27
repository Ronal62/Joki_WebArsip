<?php 
include 'header.php'; 
include 'include/config.php';

$is_admin = ($_SESSION['user_type'] == 'admin');
$is_staf = ($_SESSION['user_type'] == 'staf');

$current_kategori = 'surat_masuk';

$kategori_labels = [
    'surat_masuk' => 'Surat Masuk',
    'surat_keluar' => 'Surat Keluar',
    'surat_pengantar' => 'Surat Pengantar',
    'surat_pendukung' => 'Surat Pendukung',
    'surat_rahasia' => 'Surat Rahasia',
    'surat_kependudukan' => 'Surat Kependudukan'
];

try {
    $stmt = $conn->prepare("SELECT * FROM arsip WHERE kategori = ? ORDER BY waktu_upload DESC");
    $stmt->bind_param("s", $current_kategori);
    $stmt->execute();
    $result = $stmt->get_result();
} catch(Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<div class="body-wrapper">
    <div class="body-wrapper-inner">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-md-flex align-items-center">
                                <div>
                                    <h4 class="card-title">Surat Table</h4>
                                    <p class="card-subtitle">Data Surat</p>
                                </div>
                                <div class="ms-auto mt-3 mt-md-0">
                                    <div class="input-group">
                                        <input type="text" id="searchInput" class="form-control" placeholder="Cari Surat...">
                                        <span class="input-group-text"><i class="ti ti-search"></i></span>
                                    </div>
                                    <small class="text-muted" style="font-size: 12px;">Pencarian berdasarkan <strong>kode surat</strong> atau <strong>nama surat</strong></small>
                                </div>
                            </div>

                            <div class="table-responsive mt-4">
                                <table class="table mb-0 text-nowrap varient-table align-middle fs-3" id="suratTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Surat</th>
                                            <th>Nama Surat</th>
                                            <th>Kategori</th>
                                            <th>Keterangan</th>
                                            <th>Waktu Upload</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($result->num_rows > 0): ?>
                                            <?php $no = 1; ?>
                                            <?php while($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= htmlspecialchars($row['kode_arsip']) ?></td>
                                                <td><?= htmlspecialchars($row['nama_arsip']) ?></td>
                                                <td><?= $kategori_labels[str_replace(' ', '_', $row['kategori'])] ?></td>
                                                <td><?= htmlspecialchars($row['keterangan']) ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($row['waktu_upload'])) ?></td>
                                                <td>
                                                    <a href="download.php?id=<?= $row['id'] ?>" class="badge bg-warning">
                                                        <i class="ti ti-download"></i> Download
                                                    </a>
                                                    <a href="preview.php?id=<?= $row['id'] ?>" class="badge bg-info" target="_blank">
                                                        <i class="ti ti-eye"></i> Preview
                                                    </a>
                                                    <?php if ($is_admin) : ?>
                                                    <a href="delete.php?id=<?= $row['id'] ?>" class="badge bg-danger" onclick="return confirm('Yakin ingin menghapus <?= htmlspecialchars($row['nama_arsip']) ?>?')">
                                                        <i class="ti ti-trash"></i> Hapus
                                                    </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center">Tidak ada data surat masuk</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script pencarian -->
<script>
document.getElementById("searchInput").addEventListener("keyup", function () {
    var input = this.value.toLowerCase();
    var rows = document.querySelectorAll("#suratTable tbody tr");

    rows.forEach(function (row) {
        var kode = row.cells[1].textContent.toLowerCase();
        var nama = row.cells[2].textContent.toLowerCase();

        if (kode.includes(input) || nama.includes(input)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});
</script>

<?php include 'footer.php'; ?>
