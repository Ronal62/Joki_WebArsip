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
    $ext = pathinfo($filename, PATHINFO_EXTENSION);

    if (!file_exists($filepath)) {
        die('File tidak ditemukan');
    }

    switch(strtolower($ext)) {
        case 'pdf':
            header('Content-type: application/pdf');
            break;
        case 'jpg':
        case 'jpeg':
            header('Content-type: image/jpeg');
            break;
        default:
            die('Format tidak didukung');
    }
    
    header('Content-Disposition: inline; filename="'.basename($filename).'"');
    readfile($filepath);
    exit;
    
} catch(Exception $e) {
    die("Error: " . $e->getMessage());
}