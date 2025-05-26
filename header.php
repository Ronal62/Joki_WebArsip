<?php
session_start();
include 'include/config.php';

// Redirect jika belum login
if (!isset($_SESSION['user_type'])) {
    header("Location: auth/login.php");
    exit();
}

// Cek hak akses
$is_admin = ($_SESSION['user_type'] == 'admin');
$is_staf = ($_SESSION['user_type'] == 'staf');

// Tampilkan notifikasi
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SiKetan - Arsip</title>
    <link rel="shortcut icon" type="image/png" href="assets/images/logos/logo.png" />
    <link rel="stylesheet" href="assets/css/styles.min.css" />
    <script src="assets/js/apexcharts.min.js"></script>
</head>

<body>
    <!-- Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        <!-- App Topstrip -->
        <div class="app-topstrip bg-success py-6 px-3 w-100 d-lg-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center justify-content-center gap-5 mb-2 mb-lg-0">
                <a class="d-flex justify-content-center" href="dashboard.php">
                    <img src="assets/images/logos/logo.png" alt="" width="40">
                </a>
            </div>

            <div class="d-lg-flex align-items-center gap-2">
                <h3 class="text-white mb-2 mb-lg-0 fs-5 text-center">Pengolahan Surat</h3>
                <div class="d-flex align-items-center justify-content-center gap-2">
                </div>
            </div>
        </div>

        <!-- Sidebar Start -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="dashboard.php" class="text-nowrap logo-img">
                        <img src="assets/images/logos/logo.png" alt="" />
                    </a>
                    <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-6"></i>
                    </div>
                </div>
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
                    <ul id="sidebarnav">
                        <li class="nav-small-cap">
                            <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
                            <span class="hide-menu">Home</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="dashboard.php" aria-expanded="false">
                                <i class="ti ti-atom"></i>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>
                        <?php if ($is_admin) : ?>
                        <!-- Menu khusus Admin -->
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="admin.php" aria-expanded="false">
                                <i class="ti ti-user-circle"></i>
                                <span class="hide-menu">Data Admin</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="staf.php" aria-expanded="false">
                                <i class="ti ti-user-circle"></i>
                                <span class="hide-menu">Data Staf</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <li class="sidebar-item">
                            <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)"
                                aria-expanded="false">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="d-flex">
                                        <i class="ti ti-file-text"></i>
                                    </span>
                                    <span class="hide-menu">Kategori</span>
                                </div>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a class="sidebar-link justify-content-between" href="surat_masuk.php">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="round-16 d-flex align-items-center justify-content-center">
                                                <i class="ti ti-circle"></i>
                                            </div>
                                            <span class="hide-menu">Surat Masuk</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link justify-content-between" href="surat_keluar.php">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="round-16 d-flex align-items-center justify-content-center">
                                                <i class="ti ti-circle"></i>
                                            </div>
                                            <span class="hide-menu">Surat Keluar</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link justify-content-between" href="surat_pengantar.php">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="round-16 d-flex align-items-center justify-content-center">
                                                <i class="ti ti-circle"></i>
                                            </div>
                                            <span class="hide-menu">Surat Pengantar</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link justify-content-between" href="surat_pendukung.php">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="round-16 d-flex align-items-center justify-content-center">
                                                <i class="ti ti-circle"></i>
                                            </div>
                                            <span class="hide-menu">Surat Pendukung</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link justify-content-between" href="surat_rahasia.php">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="round-16 d-flex align-items-center justify-content-center">
                                                <i class="ti ti-circle"></i>
                                            </div>
                                            <span class="hide-menu">Surat Rahasia</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link justify-content-between" href="surat_kependudukan.php">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="round-16 d-flex align-items-center justify-content-center">
                                                <i class="ti ti-circle"></i>
                                            </div>
                                            <span class="hide-menu">Surat Kependudukan</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="tambah_surat.php" aria-expanded="false">
                                <i class="ti ti-file-upload"></i>
                                <span class="hide-menu">Upload Berkas</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>

        <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>