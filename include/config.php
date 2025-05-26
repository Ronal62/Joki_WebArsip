<?php
// config.php
$host = "localhost";
$user = "root"; 
$password = ""; // Sesuaikan dengan password MySQL Anda
$database = "siketannn"; // Ganti dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");

// function generate password
// function generatePassword($length = 10) {
//     $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
//     $password = '';
//     for ($i = 0; $i < $length; $i++) {
//         $password .= $chars[random_int(0, strlen($chars) - 1)];
//     }
//     return $password;
// }

// require dirname(__DIR__) . '/vendor/autoload.php';
?>