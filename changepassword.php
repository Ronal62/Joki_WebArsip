<?php
session_start();
include 'include/config.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_type']) || !isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Sesi tidak ditemukan. Silakan login kembali.";
    header("Location: ../auth/login.php");
    exit();
}

// Tentukan tabel berdasarkan user_type
$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];
$table = ($user_type == 'admin') ? 'admin' : 'staf';

// Ambil data pengguna dari database untuk verifikasi password lama
$query = "SELECT password FROM $table WHERE id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    $_SESSION['error'] = "Gagal mempersiapkan query: " . $conn->error;
    header("Location: changepassword.php");
    exit();
}
$stmt->bind_param("i", $user_id);
if (!$stmt->execute()) {
    $_SESSION['error'] = "Gagal menjalankan query: " . $stmt->error;
    header("Location: changepassword.php");
    exit();
}
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Jika data pengguna tidak ditemukan
if (!$user) {
    $_SESSION['error'] = "Data pengguna tidak ditemukan untuk ID: $user_id di tabel $table.";
    header("Location: changepassword.php");
    exit();
}

// Proses penggantian password jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $old_password = $_POST['oldPassword'];
    $new_password = $_POST['newPassword'];

    // Validasi input
    if (empty($old_password) || empty($new_password)) {
        $_SESSION['error'] = "Password lama dan password baru harus diisi.";
        header("Location: changepassword.php");
        exit();
    }

    // Verifikasi password lama
    if (!password_verify($old_password, $user['password'])) {
        $_SESSION['error'] = "Password lama salah.";
        header("Location: changepassword.php");
        exit();
    }

    // Validasi password baru (opsional: tambahkan aturan seperti panjang minimum)
    if (strlen($new_password) < 8) {
        $_SESSION['error'] = "Password baru harus memiliki panjang minimal 8 karakter.";
        header("Location: changepassword.php");
        exit();
    }

    // Hash password baru
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Simpan password baru ke database
    $query = "UPDATE $table SET password = ?, updated_at = NOW() WHERE id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        $_SESSION['error'] = "Gagal mempersiapkan query untuk menyimpan password: " . $conn->error;
        header("Location: changepassword.php");
        exit();
    }
    $stmt->bind_param("si", $hashed_password, $user_id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Password berhasil diganti.";
    } else {
        $_SESSION['error'] = "Gagal menyimpan password baru: " . $stmt->error;
    }
    $stmt->close();
    header("Location: changepassword.php");
    exit();
}

include 'header.php';
?>

<div class="body-wrapper">
    <!-- Header Start -->
    <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item d-block d-xl-none">
                    <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown" aria-expanded="false">
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
                        <a class="nav-link" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="./assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                            <div class="message-body">
                                <a href="profile.php" class="d-flex align-items-center gap-2 dropdown-item">
                                    <i class="ti ti-user fs-6"></i>
                                    <p class="mb-0 fs-3">My Profile</p>
                                </a>
                                <a href="../auth/logout.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Header End -->
    <div class="body-wrapper-inner">
        <div class="container-fluid">
            <!-- Change Password Page -->
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h4 class="mb-0 text-white">Ganti Password</h4>
                        </div>
                        <div class="card-body">
                            <?php if (isset($_SESSION['success'])): ?>
                                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                            <?php endif; ?>
                            <?php if (isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                            <?php endif; ?>
                            <form action="changepassword.php" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="oldPassword" class="form-label">Masukkan Password Lama</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="oldPassword" name="oldPassword" placeholder="Masukkan password lama" aria-describedby="toggleOldPassword" required>
                                        <button type="button" class="input-group-text bg-success text-white border-success cursor-pointer" id="toggleOldPassword" aria-label="Toggle password visibility">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="newPassword" class="form-label">Masukkan Password Baru</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="Masukkan password baru" aria-describedby="toggleNewPassword" required>
                                        <button type="button" class="input-group-text bg-success text-white border-success cursor-pointer" id="toggleNewPassword" aria-label="Toggle password visibility">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-success">Ganti Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Toggle untuk Password Lama
        const toggleOldPassword = document.querySelector('#toggleOldPassword');
        const oldPasswordInput = document.querySelector('#oldPassword');
        const oldIcon = toggleOldPassword.querySelector('i');

        toggleOldPassword.addEventListener('click', function () {
            const type = oldPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            oldPasswordInput.setAttribute('type', type);
            oldIcon.classList.toggle('ti-eye');
            oldIcon.classList.toggle('ti-eye-off');
            oldPasswordInput.focus();
        });

        // Toggle untuk Password Baru
        const toggleNewPassword = document.querySelector('#toggleNewPassword');
        const newPasswordInput = document.querySelector('#newPassword');
        const newIcon = toggleNewPassword.querySelector('i');

        toggleNewPassword.addEventListener('click', function () {
            const type = newPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            newPasswordInput.setAttribute('type', type);
            newIcon.classList.toggle('ti-eye');
            newIcon.classList.toggle('ti-eye-off');
            newPasswordInput.focus();
        });
    });
</script>

<?php include 'footer.php'; ?>