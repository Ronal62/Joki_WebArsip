<?php
include 'header.php';
include 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$id = $_GET['id'] ?? null;
$staf = [];
$pesan = "";

if (!$id) {
    echo "<div class='alert alert-danger'>ID tidak ditemukan.</div>";
    exit;
}

// Ambil data staf
$stmt = $conn->prepare("SELECT * FROM staf WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$staf = $result->fetch_assoc();

if (!$staf) {
    echo "<div class='alert alert-danger'>Data staf tidak ditemukan.</div>";
    exit;
}

// Set nilai default dari data staf
$nama = $staf['nama'];
$username = $staf['username'];
$foto_lama = $staf['foto'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $update_fields = [
            "nama = ?",
            "username = ?",
            "updated_at = NOW()"
        ];
        $params = [$nama, $username];
        $types = "ss";

        // Password jika diubah
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update_fields[] = "password = ?";
            $params[] = $hashed_password;
            $types .= "s";
        }

        // Upload foto jika ada
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
            $target_dir = "uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $foto_name = basename($_FILES["foto"]["name"]);
            $foto_path = $target_dir . time() . "_" . $foto_name;
            $file_tmp = $_FILES["foto"]["tmp_name"];
            $file_type = strtolower(pathinfo($foto_path, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($file_type, $allowed)) {
                if (move_uploaded_file($file_tmp, $foto_path)) {
                    // Hapus foto lama
                    if (!empty($foto_lama) && file_exists($foto_lama)) {
                        unlink($foto_lama);
                    }

                    $update_fields[] = "foto = ?";
                    $params[] = $foto_path;
                    $types .= "s";
                } else {
                    throw new Exception("Upload foto gagal.");
                }
            } else {
                throw new Exception("Tipe file tidak diizinkan.");
            }
        }

        // Update query
        $query = "UPDATE staf SET " . implode(", ", $update_fields) . " WHERE id = ?";
        $params[] = $id;
        $types .= "i";

        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            $pesan = "<div class='alert alert-success'>Update berhasil!</div>";
            echo "<script>setTimeout(() => window.location.href = 'staf.php?pesan=update', 1500)</script>";
        } else {
            throw new Exception("Gagal menyimpan perubahan.");
        }
    } catch (Exception $e) {
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
                            <h5 class="card-title fw-semibold mb-4">Edit staf</h5>
                            <div class="card">
                                <div class="card-body">
                                    <?php echo $pesan; ?>
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id; ?>" method="POST" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label for="nama" class="form-label">Nama</label>
                                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($nama); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                                            <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                                        </div>
                                        <div class="mb-3">
                                            <label for="foto" class="form-label">Foto</label>
                                            <?php if (!empty($foto_lama)) : ?>
                                                <div class="mb-2">
                                                    <img src="<?php echo $foto_lama; ?>" alt="Foto staf" width="100" class="rounded">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control" id="foto" name="foto">
                                            <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        <a href="staf.php" class="btn btn-secondary">Kembali</a>
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