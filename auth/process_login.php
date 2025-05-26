<?php
session_start();
include '../include/config.php';

// Pastikan tidak ada output sebelum header
// ob_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    // Validasi input
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username dan password harus diisi.";
        header("Location: login.php");
        ob_end_flush();
        exit();
    }

    // Cek koneksi database
    if (!$conn) {
        $_SESSION['error'] = "Koneksi database gagal: " . mysqli_connect_error();
        header("Location: login.php");
        ob_end_flush();
        exit();
    }

    // Query untuk mencari user di tabel admin atau staf
    $query = "SELECT id, password, 'admin' as user_type FROM admin WHERE username = ? 
              UNION 
              SELECT id, password, 'staf' as user_type FROM staf WHERE username = ?";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        $_SESSION['error'] = "Gagal menyiapkan query: " . $conn->error;
        header("Location: login.php");
        ob_end_flush();
        exit();
    }

    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user && password_verify($password, $user['password'])) {
        // Simpan data sesi
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = $user['user_type'];
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "Login berhasil sebagai " . ucfirst($user['user_type']) . "!";

        // Debugging: Cek apakah sesi disimpan
        if (session_status() === PHP_SESSION_ACTIVE) {
            file_put_contents('debug.txt', "Sesi disimpan: user_id=" . $_SESSION['user_id'] . ", user_type=" . $_SESSION['user_type'] . ", username=" . $_SESSION['username'] . "\n", FILE_APPEND);
        }

        header("Location: ../dashboard.php");
        ob_end_flush();
        exit();
    } else {
        $_SESSION['error'] = "Username atau password salah.";
        header("Location: login.php");
        ob_end_flush();
        exit();
    }
} else {
    $_SESSION['error'] = "Permintaan tidak valid.";
    header("Location: login.php");
    ob_end_flush();
    exit();
}

ob_end_flush();
?>