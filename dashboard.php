<?php 

// Sesuaikan path include sesuai dengan struktur folder baru Anda
include 'include/config.php';
include 'header.php';

$kategori_query = "SELECT COUNT(DISTINCT kategori) AS total_kategori FROM arsip";
$total_arsip_query = "SELECT COUNT(*) AS total_arsip FROM arsip";
$total_admin_query = "SELECT COUNT(*) AS total_admin FROM admin";
$total_staf_query = "SELECT COUNT(*) AS total_staf FROM staf";

$kategori_result = mysqli_query($conn, $kategori_query);
$total_arsip_result = mysqli_query($conn, $total_arsip_query);
$total_admin_result = mysqli_query($conn, $total_admin_query);
$total_staf_result = mysqli_query($conn, $total_staf_query);

$total_kategori = mysqli_fetch_assoc($kategori_result)['total_kategori'];
$total_arsip = mysqli_fetch_assoc($total_arsip_result)['total_arsip'];
$total_admin = mysqli_fetch_assoc($total_admin_result)['total_admin'];
$total_staf = mysqli_fetch_assoc($total_staf_result)['total_staf'];
$total_user = $total_admin + $total_staf;

mysqli_close($conn);
?>
<style>
    .navbar-nav .username-text {
    font-size: 50px;
}
</style>
<div class="body-wrapper">
    <!--  Header Start -->
    <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">

                    <li class="nav-item dropdown">
                        <a class="nav-link" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <div class="d-none d-md-flex flex-column justify-content-center align-items-center me-2" style="line-height: 1.2;">
                                <span class="text-muted" style="font-size: 15px; margin-bottom: 0;">Welcome</span>
                                <span class="fw-medium username-text" style="font-size: 13px; margin-top: 0;"><?php echo ($_SESSION['username']); ?></span>
                            </div>



                        
                            <img src="assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
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
                                <a href="auth/login.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
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
                <div class="col-lg-4">
                    <div class="card overflow-hidden">
                        <div class="card-body pb-0">
                            <div class="d-flex align-items-start">
                                <div>
                                    <h4 class="card-title">Monitoring Arsip</h4>
                                    <!-- <p class="card-subtitle">Average sales</p> -->
                                </div>
                                <div class="ms-auto">
                                    <div class="dropdown">
                                        <a href="javascript:void(0)" class="text-muted" id="year1-dropdown" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="ti ti-dots fs-7"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="year1-dropdown">
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)">Action</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)">Another action</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)">Something else here</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 pb-3 d-flex align-items-center">
                                <span class="btn btn-primary rounded-circle round-48 hstack justify-content-center">
                                    <i class="ti ti-folder fs-6"></i>
                                </span>
                                <div class="ms-3">
                                    <h5 class="mb-0 fw-bolder fs-4">Kategori Arsip</h5>
                                    <span class="text-muted fs-3"></span>
                                </div>
                                <div class="ms-auto">
                                   <span class="badge bg-secondary-subtle text-muted"><?= $total_kategori ?></span>
                                </div>
                            </div>
                            <div class="py-3 d-flex align-items-center">
                                <span class="btn btn-warning rounded-circle round-48 hstack justify-content-center">
                                    <i class="ti ti-file fs-6"></i>
                                </span>
                                <div class="ms-3">
                                    <h5 class="mb-0 fw-bolder fs-4">Total Arsip</h5>
                                    <span class="text-muted fs-3"></span>
                                </div>
                                <div class="ms-auto">
                                    <span class="badge bg-secondary-subtle text-muted"><?= $total_arsip ?></span>
                                </div>
                            </div>
                            <div class="py-3 d-flex align-items-center">
                                <span class="btn btn-success rounded-circle round-48 hstack justify-content-center">
                                    <i class="ti ti-user fs-6"></i>
                                </span>
                                <div class="ms-3">
                                    <h5 class="mb-0 fw-bolder fs-4">Jumlah User / Pengguna</h5>
                                    <span class="text-muted fs-3"></span>
                                </div>
                                <div class="ms-auto">
                                     <span class="badge bg-secondary-subtle text-muted"><?= $total_user ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>