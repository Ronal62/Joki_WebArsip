<?php 

include 'header.php'; 
include 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inisialisasi variabel
$pesan = "";
$nama = "";     
$username = "";  
$password = "";  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Validasi input
    if(empty($nama) || empty($username) || empty($password)) {
        $pesan = "<div class='alert alert-danger'>Semua field wajib diisi!</div>";
    } else {
        try {
            // Enkripsi password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Handle file upload
            $foto_path = null;
            if(isset($_FILES['foto'])) {
                $upload_dir = "uploads/admin/";
                if(!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                
                $file = $_FILES['foto'];
                $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                
                if(in_array($ext, $allowed_types)) {
                    if($file['size'] <= 2 * 1024 * 1024) { // 2MB max
                        $new_filename = uniqid('admin_', true) . '.' . $ext;
                        $target_path = $upload_dir . $new_filename;
                        
                        if(move_uploaded_file($file['tmp_name'], $target_path)) {
                            $foto_path = $target_path;
                        }
                    }
                }
            }
            
            // Query menggunakan prepared statement
            $stmt = $conn->prepare("INSERT INTO admin 
                (nama, username, password, foto, created_at) 
                VALUES (?, ?, ?, ?, NOW())");
            
            $stmt->bind_param("ssss", $nama, $username, $hashed_password, $foto_path);
            
            if($stmt->execute()) {
                $pesan = "<div class='alert alert-success'>Data berhasil ditambahkan!</div>";
                echo "<script>setTimeout(() => window.location.href = 'admin.php?pesan=sukses', 2000)</script>";
            }
        } catch(Exception $e) {
            $pesan = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
        }
    }
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
                                <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                    <i class="ti ti-user fs-6"></i>
                                    <p class="mb-0 fs-3">My Profile</p>
                                </a>
                                <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                    <i class="ti ti-mail fs-6"></i>
                                    <p class="mb-0 fs-3">My Account</p>
                                </a>
                                <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                    <i class="ti ti-list-check fs-6"></i>
                                    <p class="mb-0 fs-3">My Task</p>
                                </a>
                                <a href="./authentication-login.html" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
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
                            <h5 class="card-title fw-semibold mb-4">Tambah Admin</h5>
                            <div class="card">
                                <div class="card-body">
                                    <?php echo $pesan; ?>
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label for="exampleInputEmail1" class="form-label">Nama</label>
                                            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="nama" placeholder="Masukkan Nama">
                                        </div>
                                        <div class="mb-3">
                                            <label for="exampleInputUsername1" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="exampleInputUsername1" name="username" placeholder="Masukkan Username">
                                        </div>
                                        <div class="mb-3">
                                            <label for="exampleInputPassword1" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Masukkan Password">
                                        </div>
                                        <div class="mb-3">
                                            <label for="exampleInputPassword1" class="form-label">Foto</label>
                                            <input type="file" class="form-control" id="exampleInputPassword1" name="foto" placeholder="Upload Foto"  accept=".jpg, .jpeg" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </form>
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