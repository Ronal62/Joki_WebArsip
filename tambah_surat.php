<?php 

include 'header.php'; 
include 'include/config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// if ($_SESSION['user_type'] !== 'petugas') {
//     $_SESSION['error'] = "Akses ditolak! Hanya petugas yang bisa upload.";
//     header("Location: index.php");
//     exit();
// }

// session_start();

// Redirect jika belum login
// if (!isset($_SESSION['user_type'])) {
//     header("Location: index.php");
//     exit();
// }

// Cek jika yang login adalah admin
// if ($_SESSION['user_type'] === 'admin') {
//     $_SESSION['error'] = "Maaf, hanya petugas yang dapat mengupload berkas!";
//     header("Location: index.php");
//     exit();
// }

// if ($_SESSION['user_type'] !== 'petugas') {
//     $_SESSION['error'] = "Hanya petugas yang dapat mengupload berkas!";
//     header("Location: index.php");
//     exit();
// }

$pesan = '';
$allowed_types = ['pdf', 'jpeg', 'jpg'];
$max_size = 10 * 1024 * 1024; // 10MB

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_arsip = $_POST['kode_arsip'];
    $nama_arsip = $_POST['nama_arsip'];
    $kategori = $_POST['kategori'];
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];
    
    try {
        // Validasi input
        if(empty($kode_arsip) || empty($nama_arsip) || empty($kategori)) {
            throw new Exception("Field wajib tidak boleh kosong!");
        }

        // Validasi file
        if(!isset($_FILES['file_arsip']) || $_FILES['file_arsip']['error'] != UPLOAD_ERR_OK) {
            throw new Exception("File harus diupload!");
        }

        $file = $_FILES['file_arsip'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $mime_type = mime_content_type($file['tmp_name']);

        // Validasi jenis file
        if(!in_array($ext, $allowed_types)) {
            throw new Exception("Format file tidak valid. Hanya PDF, JPEG, dan JPG yang diizinkan!");
        }

        // Validasi ukuran file
        if($file['size'] > $max_size) {
            throw new Exception("Ukuran file melebihi 10MB!");
        }

        // Membuat direktori kategori
        $upload_dir = "uploads/" . str_replace(' ', '_', $kategori) . "/";
        if(!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Generate nama file unik
        $new_filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9_.]/', '', $file['name']);
        $target_path = $upload_dir . $new_filename;

        if(move_uploaded_file($file['tmp_name'], $target_path)) {
            // Simpan ke database
            $stmt = $conn->prepare("INSERT INTO arsip 
                (kode_arsip, nama_arsip, kategori, tanggal, keterangan, nama_file, path_file, waktu_upload)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
            
            $stmt->bind_param("sssssss", 
                $kode_arsip,
                $nama_arsip,
                $kategori,
                $tanggal,
                $keterangan,
                $file['name'],
                $target_path
            );

            if($stmt->execute()) {
                $pesan = "<div class='alert alert-success'>Arsip berhasil disimpan!</div>";
                echo "<script>setTimeout(() => window.location.href = '{$kategori}.php', 2000)</script>";
            } else {
                unlink($target_path); // Hapus file jika gagal simpan ke database
                throw new Exception("Gagal menyimpan data ke database!");
            }
        } else {
            throw new Exception("Gagal mengupload file!");
        }
    } catch(Exception $e) {
        $pesan = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
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
                            <h5 class="card-title fw-semibold mb-4">Upload Arsip</h5>
                            <div class="card">
                                <div class="card-body">
                                    <?php echo $pesan; ?>
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                                        <div class="mb-3">
            <label class="form-label">Kode Arsip</label>
            <input type="text" class="form-control" name="kode_arsip" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Arsip</label>
            <input type="text" class="form-control" name="nama_arsip" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori" class="form-control" required>
                <!-- options tetap sama -->
                 <option value="">Pilih Kategori</option>
                 <option value="surat_masuk">Surat Masuk</option>
                 <option value="surat_keluar">Surat Keluar</option>                                            
                 <option value="surat_pengantar">Surat Pengantar</option>                                            
                 <option value="surat_pendukung">Surat Pendukung</option>                                            
                 <option value="surat_rahasia">Surat Rahasia</option>                                            
                 <option value="surat_kependudukan">Surat Kependudukan</option>   
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" class="form-control" name="tanggal" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">File</label>
            <input type="file" class="form-control" name="file_arsip" accept=".pdf,.jpg,.jpeg" required>
            <small class="text-muted">Format: PDF, JPG, JPEG (Maks 10MB)</small>
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