<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include 'include/config.php';

// Debugging - Simpan semua detail request untuk membantu troubleshooting
error_log('DELETE REQUEST - GET: ' . print_r($_GET, true));
error_log('DELETE REQUEST - HTTP REFERER: ' . $_SERVER['HTTP_REFERER']);

// Cek apakah parameter id ada
if (!isset($_GET['id'])) {
    $_SESSION['pesan'] = "error|ID tidak ditemukan dalam URL!";
    error_log('DELETE ERROR - ID tidak ditemukan dalam URL');
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

// Pastikan id adalah numerik
if (!is_numeric($_GET['id'])) {
    $_SESSION['pesan'] = "error|ID tidak valid (bukan angka)!";
    error_log('DELETE ERROR - ID bukan angka: ' . $_GET['id']);
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

$id = $_GET['id'];

try {
    // Ambil data file
    $stmt = $conn->prepare("SELECT * FROM arsip WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Log untuk troubleshooting
    error_log('DELETE INFO - Mencari record dengan ID: ' . $id);
    error_log('DELETE INFO - Jumlah records ditemukan: ' . $result->num_rows);
    
    if($result->num_rows === 0) {
        $_SESSION['pesan'] = "error|Data tidak ditemukan di database!";
        error_log('DELETE ERROR - Data dengan ID ' . $id . ' tidak ditemukan');
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
    
    $data = $result->fetch_assoc();
    $filepath = $data['path_file'];
    
    // Log file path untuk troubleshooting
    error_log('DELETE INFO - Filepath: ' . $filepath);
    
    // Hapus dari database
    $stmt = $conn->prepare("DELETE FROM arsip WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if($stmt->execute()) {
        // Hapus file fisik jika ada
        if(!empty($filepath) && file_exists($filepath)) {
            if(unlink($filepath)) {
                error_log('DELETE INFO - File fisik berhasil dihapus: ' . $filepath);
            } else {
                error_log('DELETE WARNING - Gagal menghapus file fisik: ' . $filepath);
            }
        } else {
            error_log('DELETE WARNING - File fisik tidak ditemukan: ' . $filepath);
        }
        
        $_SESSION['pesan'] = "success|Data berhasil dihapus!";
        error_log('DELETE SUCCESS - Data dengan ID ' . $id . ' berhasil dihapus');
    } else {
        $_SESSION['pesan'] = "error|Gagal menghapus data dari database!";
        error_log('DELETE ERROR - Gagal menghapus data dari database: ' . $stmt->error);
    }
    
} catch(Exception $e) {
    $_SESSION['pesan'] = "error|Error: " . $e->getMessage();
    error_log('DELETE EXCEPTION: ' . $e->getMessage());
}

// Redirect kembali ke halaman sebelumnya
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>