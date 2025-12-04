<?php
// Konfigurasi database
$host     = "localhost";   // biasanya localhost
$user     = "root";        // default user XAMPP
$pass     = "";            // default password XAMPP (kosong)
$db       = "db_crowdfunding_umkm";        // ganti sesuai nama database kamu

// Membuat koneksi
$conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Optional: supaya hasil query pakai UTF-8
mysqli_set_charset($conn, "utf8");
?>
