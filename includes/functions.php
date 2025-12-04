<?php
// includes/functions.php
session_start();
require_once __DIR__ . '/../config/database.php';

function is_logged_in() {
    return isset($_SESSION['id_user']);
}

function require_role($role) {
    if (!is_logged_in() || $_SESSION['role'] !== $role) {
        header("Location: /crowdfunding_umkm/login.php");
        exit();
    }
}

function create_notification($koneksi, $id_user, $judul, $pesan) {
    $stmt = $koneksi->prepare("INSERT INTO notifikasi (id_user, judul, pesan) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $id_user, $judul, $pesan);
    $stmt->execute();
    $stmt->close();
}

function write_log($koneksi, $id_user, $aktivitas) {
    $stmt = $koneksi->prepare("INSERT INTO log_aktivitas (id_user, aktivitas) VALUES (?, ?)");
    if ($id_user === null) {
        $stmt = $koneksi->prepare("INSERT INTO log_aktivitas (id_user, aktivitas) VALUES (NULL, ?)");
        $stmt->bind_param("s", $aktivitas);
    } else {
        $stmt->bind_param("is", $id_user, $aktivitas);
    }
    $stmt->execute();
    $stmt->close();
}

// safe file upload helper (for proposal files)
function upload_file($file_input_name, $upload_dir = __DIR__ . '/../assets/uploads/') {
    if (!isset($_FILES[$file_input_name]) || $_FILES[$file_input_name]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

    $tmp = $_FILES[$file_input_name]['tmp_name'];
    $orig = basename($_FILES[$file_input_name]['name']);
    $ext = pathinfo($orig, PATHINFO_EXTENSION);
    $allowed = ['pdf','doc','docx','jpg','jpeg','png'];
    if (!in_array(strtolower($ext), $allowed)) return null;

    $newname = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
    $dest = $upload_dir . $newname;
    if (move_uploaded_file($tmp, $dest)) {
        return 'assets/uploads/' . $newname; // relative path
    }
    return null;
}
?>
