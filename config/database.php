<?php
// config/database.php
$host = "localhost";
$user = "root"; // ganti sesuai konfigurasi
$pass = "";     // ganti sesuai konfigurasi
$db   = "db_crowdfunding_umkm";

$koneksi = new mysqli($host, $user, $pass, $db);
if ($koneksi->connect_error) {
    die("Koneksi database gagal: " . $koneksi->connect_error);
}
$koneksi->set_charset("utf8mb4");
?>
