<?php
session_start();
include '../include/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validasi input kosong
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username dan password harus diisi.";
        header("Location: login.php");
        exit();
    }

    // Validasi koneksi database
    if (!$conn) {
        $_SESSION['error'] = "Koneksi database gagal: " . mysqli_connect_error();
        header("Location: login.php");
        exit();
    }

    $login_success = false;
    $user_data = null;
    $login_table = '';

    // Debug log untuk login attempt
    $login_debug = [
        'username' => $username,
        'timestamp' => date('Y-m-d H:i:s'),
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ];

    // Cek di tabel admin dulu
    $queryAdmin = "SELECT id, username, password FROM admin WHERE username = ?";
    $stmt = $conn->prepare($queryAdmin);
    
    if (!$stmt) {
        $_SESSION['error'] = "Gagal mempersiapkan query admin: " . $conn->error;
        header("Location: login.php");
        exit();
    }
    
    $stmt->bind_param("s", $username);
    
    if (!$stmt->execute()) {
        $_SESSION['error'] = "Gagal menjalankan query admin: " . $stmt->error;
        header("Location: login.php");
        exit();
    }
    
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $login_debug['found_in'] = 'admin';
        $login_debug['user_id'] = $user['id'];
        $login_debug['hash_from_db'] = substr($user['password'], 0, 30) . '...';
        
        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            $login_success = true;
            $user_data = $user;
            $login_table = 'admin';
            $login_debug['password_verify'] = 'SUCCESS';
        } else {
            $login_debug['password_verify'] = 'FAILED';
            
            // Log failed admin login
            file_put_contents('debug_login_attempts.txt', "ADMIN LOGIN FAILED: " . json_encode($login_debug, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);
        }
    } else {
        $login_debug['found_in'] = 'admin - NOT FOUND';
    }
    $stmt->close();

    // Jika belum berhasil login, cek di tabel staf
    if (!$login_success) {
        $queryStaf = "SELECT id, username, password FROM staf WHERE username = ?";
        $stmt = $conn->prepare($queryStaf);
        
        if (!$stmt) {
            $_SESSION['error'] = "Gagal mempersiapkan query staf: " . $conn->error;
            header("Location: login.php");
            exit();
        }
        
        $stmt->bind_param("s", $username);
        
        if (!$stmt->execute()) {
            $_SESSION['error'] = "Gagal menjalankan query staf: " . $stmt->error;
            header("Location: login.php");
            exit();
        }
        
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $login_debug['found_in'] .= ', staf';
            $login_debug['user_id'] = $user['id'];
            $login_debug['hash_from_db'] = substr($user['password'], 0, 30) . '...';
            
            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                $login_success = true;
                $user_data = $user;
                $login_table = 'staf';
                $login_debug['password_verify'] = 'SUCCESS';
            } else {
                $login_debug['password_verify'] = 'FAILED';
                
                // Log failed staf login
                file_put_contents('debug_login_attempts.txt', "STAF LOGIN FAILED: " . json_encode($login_debug, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);
            }
        } else {
            $login_debug['found_in'] .= ', staf - NOT FOUND';
        }
        $stmt->close();
    }

    // Proses hasil login
    if ($login_success && $user_data) {
        // Set session variables
        $_SESSION['user_id'] = $user_data['id'];
        $_SESSION['user_type'] = $login_table;
        $_SESSION['username'] = $user_data['username'];
        
        // Log successful login
        $login_debug['status'] = 'SUCCESS';
        $login_debug['user_type'] = $login_table;
        file_put_contents('debug_login_attempts.txt', "LOGIN SUCCESS: " . json_encode($login_debug, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);
        
        // Update last login time (optional)
        // $update_query = "UPDATE $login_table SET last_login = NOW() WHERE id = ?";
        // $update_stmt = $conn->prepare($update_query);
        // if ($update_stmt) {
        //     $update_stmt->bind_param("i", $user_data['id']);
        //     $update_stmt->execute();
        //     $update_stmt->close();
        // }
        
        // Clear any previous error messages
        unset($_SESSION['error']);
        
        // Redirect to dashboard
        header("Location: ../dashboard.php");
        exit();
    } else {
        // Login failed
        $login_debug['status'] = 'FAILED';
        
        if (strpos($login_debug['found_in'], 'NOT FOUND') !== false) {
            $_SESSION['error'] = "Username tidak ditemukan.";
            $login_debug['error_type'] = 'USER_NOT_FOUND';
        } else {
            $_SESSION['error'] = "Password salah.";
            $login_debug['error_type'] = 'WRONG_PASSWORD';
        }
        
        // Log failed login attempt
        file_put_contents('debug_login_attempts.txt', "LOGIN FAILED: " . json_encode($login_debug, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);
        
        header("Location: login.php");
        exit();
    }
}

// Jika bukan POST request atau tidak ada tombol login, redirect ke login page
header("Location: login.php");
exit();
?>