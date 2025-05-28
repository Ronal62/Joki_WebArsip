<?php

include 'include/config.php';
include 'header.php';

// Validasi sesi login
if (!isset($_SESSION['user_type'], $_SESSION['user_id'])) {
    $_SESSION['error'] = "Silakan login terlebih dahulu";
    header("Location: ../auth/login.php");  
    exit();
}

// Ambil data user login
$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];
$table = ($user_type === 'admin') ? 'admin' : 'staf';

try {
    // Query data user berdasarkan session login
    $stmt = $conn->prepare("SELECT * FROM $table WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if (!$stmt->execute()) {
        throw new Exception("Gagal mengambil data pengguna");
    }

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        throw new Exception("Data pengguna tidak ditemukan");
    }

} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: profile.php");
    exit();
}
?>

<div class="body-wrapper">
    <div class="body-wrapper-inner">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h4 class="mb-0 text-white">Profil Pengguna</h4>
                        </div>
                        <div class="card-body">

                            <!-- Form Profil -->
                            <form action="profile.php" method="POST" enctype="multipart/form-data">
                                <div class="text-center mb-4">
                                    <?php
                                    $folder = ($user_type === 'admin') ? 'admin' : 'staf';
                                    
                                    // Path absolut ke file untuk pengecekan
                                    $file_path = __DIR__ . "/../uploads/{$folder}/" . $user['foto'];
                                    
                                    // Debug: echo "<!-- File path: $file_path -->";
                                    
                                    // Tentukan nama file foto
                                    $fotoFile = (!empty($user['foto']) && file_exists($file_path))
                                        ? $user['foto']
                                        : 'default.jpg';
                                    
                                    // Path relatif untuk browser
                                    $fotoUrl = "../uploads/{$folder}/" . htmlspecialchars($fotoFile);
                                    ?>
                                    
                                    <img src="<?= $fotoUrl ?>" 
                                         class="rounded-circle mb-3" 
                                         width="150" 
                                         height="150"
                                         alt="Foto Profil <?= htmlspecialchars($user['username']) ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Role</label>
                                    <p class="form-control-plaintext bg-light p-3 rounded">
                                        <?= strtoupper($user_type) === 'ADMIN' ? 'Administrator' : 'Staf' ?>
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="fullName" 
                                           value="<?= htmlspecialchars($user['nama']) ?>" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Username</label>
                                    <input type="text" class="form-control" name="username"
                                           value="<?= htmlspecialchars($user['username']) ?>" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" value="********" disabled>
                                        <a href="changepassword.php" class="btn btn-outline-secondary">
                                            <i class="ti ti-key"></i> Ubah
                                        </a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>              
                </div>               
            </div>                       
        </div>
    </div>                        
</div>

<?php include 'footer.php'; ?>
