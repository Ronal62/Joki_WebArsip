<?php
include 'include/config.php';

// Cek apakah ID sudah diberikan
if(isset($_GET['id'])) {
    // Ambil ID dari parameter URL
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Ambil informasi file foto terlebih dahulu
    $query_foto = "SELECT foto FROM petugas WHERE id = '$id'";
    $result_foto = mysqli_query($conn, $query_foto);
    
    if(mysqli_num_rows($result_foto) > 0) {
        $row = mysqli_fetch_assoc($result_foto);
        $foto_path = $row['foto'];
        
        // Hapus file foto jika ada
        if(!empty($foto_path) && file_exists($foto_path)) {
            unlink($foto_path); // Hapus file fisik
        }
        
        // Hapus data dari database
        $query_delete = "DELETE FROM petugas WHERE id = '$id'";
        
        if(mysqli_query($conn, $query_delete)) {
            // Redirect ke halaman petugas dengan pesan sukses
            header("Location: petugas.php?pesan=hapus");
            exit();
        } else {
            // Redirect dengan pesan error
            header("Location: petugas.php?pesan=gagal");
            exit();
        }
    } else {
        // ID tidak ditemukan
        header("Location: petugas.php?pesan=tidak_ditemukan");
        exit();
    }
} else {
    // Tidak ada ID yang diberikan
    header("Location: petugas.php");
    exit();
}
?>