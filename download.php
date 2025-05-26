<?php
include 'include/config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID tidak valid');
}

$id = $_GET['id'];

try {
    $stmt = $conn->prepare("SELECT path_file, nama_file FROM arsip WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows === 0) {
        die('File tidak ditemukan');
    }
    
    $file = $result->fetch_assoc();
    $filepath = $file['path_file'];
    $filename = $file['nama_file'];
    
    if (!file_exists($filepath)) {
        die('File tidak ditemukan');
    }

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($filename).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filepath));
    readfile($filepath);
    exit;
    
} catch(Exception $e) {
    die("Error: " . $e->getMessage());
}