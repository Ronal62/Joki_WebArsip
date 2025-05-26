<?php 
include 'header.php'; 
require_once 'include/config.php';

$stmt = $conn->prepare("SELECT * FROM staf ORDER BY id DESC");
$stmt->execute();
$result = $stmt->get_result();

if (!$conn) {
    die("Koneksi database tidak tersedia");
}

// Query dengan error handling
try {
    $stmt = $conn->prepare("SELECT * FROM staf ORDER BY id DESC");
    $stmt->execute();
    $result = $stmt->get_result();
} catch(Exception $e) {
    die("Error query: " . $e->getMessage());
}

?>

<div class="body-wrapper">
    <!--  Header Start -->
    <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item d-block d-xl-none">
                    <a class="nav-link sidebartoggler " id="headerCollapse" href="javascript:void(0)">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link " href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ti ti-bell"></i>
                        <div class="notification bg-primary rounded-circle"></div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-animate-up" aria-labelledby="drop1">
                        <div class="message-body">
                            <a href="javascript:void(0)" class="dropdown-item">
                                Item 1
                            </a>
                            <a href="javascript:void(0)" class="dropdown-item">
                                Item 2
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">

                    <li class="nav-item dropdown">
                        <a class="nav-link " href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <img src="./assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                            <div class="message-body">
                                <a href="profile.php" class="d-flex align-items-center gap-2 dropdown-item">
                                    <i class="ti ti-user fs-6"></i>
                                    <p class="mb-0 fs-3">My Profile</p>
                                </a>
                                <!-- <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                    <i class="ti ti-mail fs-6"></i>
                                    <p class="mb-0 fs-3">My Account</p>
                                </a>
                                <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                    <i class="ti ti-list-check fs-6"></i>
                                    <p class="mb-0 fs-3">My Task</p>
                                </a> -->
                                <a href="login.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!--  Header End -->
    <div class="body-wrapper-inner">
        <div class="container-fluid">
            <!--  Row 1 -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-md-flex align-items-center">
                                <div>
                                    <h4 class="card-title">Daftar staf</h4>
                                    <p class="card-subtitle">
                                        Data staf
                                    </p>
                                </div>
                                <div class="ms-auto mt-3 mt-md-0">
                                    <a href="tambah_staf.php" class="badge bg-primary"><i class="ti ti-plus"></i> Tambah staf</a>
                                </div>
                            </div>
                            <tbody>
                            <?php
                            // Cek jika ada pesan dari halaman tambah_staf.php
                            if (isset($_GET['pesan'])) {
                                if ($_GET['pesan'] == "sukses") {
                                    echo "<div class='alert alert-success'>Data staf berhasil ditambahkan!</div>";
                                } else if ($_GET['pesan'] == "update") {
                                    echo "<div class='alert alert-success'>Data staf berhasil diperbarui!</div>";
                                } else if ($_GET['pesan'] == "hapus") {
                                    echo "<div class='alert alert-success'>Data staf berhasil dihapus!</div>";
                                }
                            }
                            ?>
                            
                            <div class="table-responsive mt-4">
                                <table class="table mb-0 text-nowrap varient-table align-middle fs-3">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="px-0 text-muted">Nama</th>
                                            <th scope="col" class="px-0 text-muted">Username</th>
                                            <th scope="col" class="px-0 text-muted">Foto</th>
                                            <th scope="col" class="px-0 text-muted">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['nama']) ?></td>
        <td><?= htmlspecialchars($row['username']) ?></td>
        <td>
            <img src="<?= $row['foto'] ?? './assets/images/profile/user-1.jpg' ?>" 
                 alt="Foto staf" 
                 class="rounded-circle" 
                 width="50" 
                 height="50"
                 onerror="this.src='./assets/images/profile/user-1.jpg'">
        </td>
        <td>
            <a href="edit_staf.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">
                <i class="ti ti-edit"></i> Edit
            </a>
            <a href="delete_staf.php?id=<?= $row['id'] ?>" 
               class="btn btn-danger btn-sm" 
               onclick="return confirm('Yakin menghapus data ini?')">
                <i class="ti ti-trash"></i> Hapus
            </a>
        </td>
    </tr>
    <?php endwhile; ?>
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

<?php include 'footer.php'; ?>