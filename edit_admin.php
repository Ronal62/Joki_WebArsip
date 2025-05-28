<?php
include 'header.php';
require_once 'include/config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$id = $_GET['id'] ?? null;
$admin = [];
$pesan = "";
$nama = "";
$username = "";
$password = "";
$foto_lama = "";

// Ambil data admin
if ($id) {
    $stmt = $conn->prepare("SELECT * FROM admin WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin) {
        $nama = $admin['nama'];
        $username = $admin['username'];
        $foto_lama = $admin['foto'] ?? '';
    }
}

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

        // Password (jika diisi)
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update_fields[] = "password = ?";
            $params[] = $hashed_password;
            $types .= "s";
        }

        // Foto (jika ada)
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $target_dir = "uploads/admin/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_name = time() . '_' . basename($_FILES["foto"]["name"]);
            $target_file = $target_dir . $file_name;
            move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);

            // Hapus foto lama
            if (!empty($admin['foto']) && file_exists($admin['foto'])) {
                unlink($admin['foto']);
            }

            $foto_path = $target_file;
            $update_fields[] = "foto = ?";
            $params[] = $foto_path;
            $types .= "s";
        }

        // Bangun query update
        $query = "UPDATE admin SET " . implode(", ", $update_fields) . " WHERE id = ?";
        $params[] = $id;
        $types .= "i";

        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            $pesan = "<div class='alert alert-success'>Update berhasil!</div>";
            echo "<script>setTimeout(() => window.location.href = 'admin.php?pesan=update', 2000)</script>";
        }
    } catch (Exception $e) {
        $pesan = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

<!-- Bagian HTML -->
<div class="body-wrapper">
    <div class="body-wrapper-inner">
        <div class="container-fluid">
            <!-- Notifikasi -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Edit Admin</h5>
                            <?php echo $pesan; ?>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id; ?>" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama" value="<?php echo htmlspecialchars($nama); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username" value="<?php echo htmlspecialchars($username); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                                    <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                                </div>
                                <div class="mb-3">
                                    <label for="foto" class="form-label">Foto</label>
                                    <div class='mb-2'>
                                        <img id="preview" src="<?php echo !empty($foto_lama) ? $foto_lama : 'assets/images/profile/user-1.jpg'; ?>" alt='Foto admin' width='100' class='rounded'>
                                    </div>
                                    <input type="file" class="form-control" id="foto" name="foto" onchange="previewImage(this)">
                                    <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <a href="admin.php" class="btn btn-secondary">Kembali</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('preview');
    const file = input.files[0];
    const reader = new FileReader();

    reader.onloadend = function () {
        preview.src = reader.result;
    }

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.src = "<?php echo !empty($foto_lama) ? $foto_lama : 'assets/images/profile/user-1.jpg'; ?>";
    }
}
</script>

<?php include 'footer.php'; ?>
