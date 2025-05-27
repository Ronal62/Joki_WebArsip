<?php
session_start();
include 'include/config.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_type']) || !isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Sesi tidak ditemukan. Silakan login kembali.";
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];
$table = ($user_type == 'admin') ? 'admin' : 'staf';

// Ambil data pengguna dari database untuk verifikasi password lama
$query = "SELECT password, username FROM $table WHERE id = ?";
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

if (!$user) {
    $_SESSION['error'] = "Data pengguna tidak ditemukan untuk ID: $user_id di tabel $table.";
    header("Location: changepassword.php");
    exit();
}

// Proses penggantian password jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $old_password = trim($_POST['oldPassword'] ?? '');
    $new_password = trim($_POST['newPassword'] ?? '');
    $confirm_password = trim($_POST['confirmPassword'] ?? '');

    // Validasi input kosong
    if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
        $_SESSION['error'] = "Semua field password harus diisi.";
        header("Location: changepassword.php");
        exit();
    }

    // Validasi password baru dan konfirmasi sama
    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Password baru dan konfirmasi password tidak sama.";
        header("Location: changepassword.php");
        exit();
    }

    // Validasi password baru minimal 8 karakter
    if (strlen($new_password) < 8) {
        $_SESSION['error'] = "Password baru harus memiliki panjang minimal 8 karakter.";
        header("Location: changepassword.php");
        exit();
    }

    // Validasi password baru tidak sama dengan password lama
    if ($old_password === $new_password) {
        $_SESSION['error'] = "Password baru harus berbeda dengan password lama.";
        header("Location: changepassword.php");
        exit();
    }

    // Verifikasi password lama
    if (!password_verify($old_password, $user['password'])) {
        $_SESSION['error'] = "Password lama salah.";
        
        // Debug log untuk password lama salah
        $debug_info = [
            'user_id' => $user_id,
            'username' => $user['username'],
            'user_type' => $user_type,
            'table' => $table,
            'old_password_verify' => 'FAILED',
            'timestamp' => date('Y-m-d H:i:s'),
            'error' => 'Old password verification failed'
        ];
        file_put_contents('debug_password_change.txt', "FAILED LOGIN ATTEMPT: " . json_encode($debug_info, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);
        
        header("Location: changepassword.php");
        exit();
    }

    // Hash password baru
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Verifikasi hash yang dibuat (untuk memastikan)
    if (!password_verify($new_password, $hashed_password)) {
        $_SESSION['error'] = "Gagal membuat hash password yang valid.";
        
        // Debug log untuk hash gagal
        $debug_info = [
            'user_id' => $user_id,
            'username' => $user['username'],
            'error' => 'Hash verification failed immediately after creation',
            'timestamp' => date('Y-m-d H:i:s')
        ];
        file_put_contents('debug_password_change.txt', "HASH ERROR: " . json_encode($debug_info, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);
        
        header("Location: changepassword.php");
        exit();
    }

    // Update password di database
    $query = "UPDATE $table SET password = ?, updated_at = NOW() WHERE id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        $_SESSION['error'] = "Gagal mempersiapkan query untuk menyimpan password: " . $conn->error;
        header("Location: changepassword.php");
        exit();
    }
    
    $stmt->bind_param("si", $hashed_password, $user_id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            // Verifikasi sekali lagi dengan membaca dari database
            $verify_query = "SELECT password FROM $table WHERE id = ?";
            $verify_stmt = $conn->prepare($verify_query);
            $verify_stmt->bind_param("i", $user_id);
            $verify_stmt->execute();
            $verify_result = $verify_stmt->get_result();
            $verify_user = $verify_result->fetch_assoc();
            $verify_stmt->close();
            
            $final_verification = password_verify($new_password, $verify_user['password']);
            
            // Debug log sukses
            $debug_info = [
                'user_id' => $user_id,
                'username' => $user['username'],
                'user_type' => $user_type,
                'table' => $table,
                'old_password_verify' => 'SUCCESS',
                'new_hash_created' => substr($hashed_password, 0, 30) . '...',
                'new_hash_from_db' => substr($verify_user['password'], 0, 30) . '...',
                'final_verification' => $final_verification ? 'SUCCESS' : 'FAILED',
                'affected_rows' => $stmt->affected_rows,
                'timestamp' => date('Y-m-d H:i:s')
            ];
            file_put_contents('debug_password_change.txt', "SUCCESS: " . json_encode($debug_info, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);
            
            if ($final_verification) {
                $_SESSION['success'] = "Password berhasil diganti. Silakan login dengan password baru.";
            } else {
                $_SESSION['error'] = "Password tersimpan tapi verifikasi gagal. Silakan coba login dengan password baru.";
            }
        } else {
            $_SESSION['error'] = "Tidak ada data yang diupdate. Silakan coba lagi.";
        }
    } else {
        // Debug log gagal update
        $debug_info = [
            'user_id' => $user_id,
            'username' => $user['username'],
            'error' => $stmt->error,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        file_put_contents('debug_password_change.txt', "UPDATE FAILED: " . json_encode($debug_info, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);
        
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
                            <a href="javascript:void(0)" class="dropdown-item">Item 1</a>
                            <a href="javascript:void(0)" class="dropdown-item">Item 2</a>
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
                                <a href="../auth/login.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
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
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h4 class="mb-0 text-white">Ganti Password</h4>
                        </div>
                        <div class="card-body">
                            
                            <!-- Display Success/Error Messages -->
                            <?php if (isset($_SESSION['success'])): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="ti ti-check-circle me-2"></i>
                                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="ti ti-alert-circle me-2"></i>
                                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>
                            
                            <form action="changepassword.php" method="POST" autocomplete="off">
                                <div class="mb-3">
                                    <label for="oldPassword" class="form-label">Masukkan Password Lama</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="oldPassword" name="oldPassword" 
                                               placeholder="Masukkan password lama" required autocomplete="current-password">
                                        <button type="button" class="input-group-text bg-success text-white border-success cursor-pointer" 
                                                id="toggleOldPassword" aria-label="Toggle password visibility">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="newPassword" class="form-label">Masukkan Password Baru</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="newPassword" name="newPassword" 
                                               placeholder="Masukkan password baru (minimal 8 karakter)" required autocomplete="new-password">
                                        <button type="button" class="input-group-text bg-success text-white border-success cursor-pointer" 
                                                id="toggleNewPassword" aria-label="Toggle password visibility">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Password harus minimal 8 karakter dan berbeda dari password lama</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="confirmPassword" class="form-label">Konfirmasi Password Baru</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" 
                                               placeholder="Masukkan ulang password baru" required autocomplete="new-password">
                                        <button type="button" class="input-group-text bg-success text-white border-success cursor-pointer" 
                                                id="toggleConfirmPassword" aria-label="Toggle password visibility">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    </div>
                                    <div class="form-text" id="passwordMatch"></div>
                                </div>
                                
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-success" id="submitBtn">Ganti Password</button>
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
    // Password toggle functionality
    const passwordInputs = [
        { toggle: '#toggleOldPassword', input: '#oldPassword' },
        { toggle: '#toggleNewPassword', input: '#newPassword' },
        { toggle: '#toggleConfirmPassword', input: '#confirmPassword' }
    ];

    passwordInputs.forEach(item => {
        const toggleBtn = document.querySelector(item.toggle);
        const input = document.querySelector(item.input);
        const icon = toggleBtn.querySelector('i');

        toggleBtn.addEventListener('click', function () {
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            icon.classList.toggle('ti-eye');
            icon.classList.toggle('ti-eye-off');
            input.focus();
        });
    });

    // Real-time password confirmation check
    const newPasswordInput = document.querySelector('#newPassword');
    const confirmPasswordInput = document.querySelector('#confirmPassword');
    const passwordMatchDiv = document.querySelector('#passwordMatch');
    const submitBtn = document.querySelector('#submitBtn');

    function checkPasswordMatch() {
        const newPassword = newPasswordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (confirmPassword === '') {
            passwordMatchDiv.textContent = '';
            passwordMatchDiv.className = 'form-text';
            return;
        }
        
        if (newPassword === confirmPassword) {
            passwordMatchDiv.textContent = '✓ Password cocok';
            passwordMatchDiv.className = 'form-text text-success';
        } else {
            passwordMatchDiv.textContent = '✗ Password tidak cocok';
            passwordMatchDiv.className = 'form-text text-danger';
        }
    }

    newPasswordInput.addEventListener('input', checkPasswordMatch);
    confirmPasswordInput.addEventListener('input', checkPasswordMatch);

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const oldPassword = document.querySelector('#oldPassword').value.trim();
        const newPassword = document.querySelector('#newPassword').value.trim();
        const confirmPassword = document.querySelector('#confirmPassword').value.trim();
        
        // Check empty fields
        if (!oldPassword || !newPassword || !confirmPassword) {
            e.preventDefault();
            alert('Semua field password harus diisi!');
            return false;
        }
        
        // Check password match
        if (newPassword !== confirmPassword) {
            e.preventDefault();
            alert('Password baru dan konfirmasi password tidak sama!');
            return false;
        }
        
        // Check minimum length
        if (newPassword.length < 8) {
            e.preventDefault();
            alert('Password baru harus minimal 8 karakter!');
            return false;
        }
        
        // Check if new password is different from old password
        if (oldPassword === newPassword) {
            e.preventDefault();
            alert('Password baru harus berbeda dengan password lama!');
            return false;
        }
        
        // Show loading state
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengganti Password...';
        submitBtn.disabled = true;
    });
});
</script>

<?php include 'footer.php'; ?>