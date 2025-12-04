<?php
require_once '../includes/koneksi.php';
session_start();

$id_investor = $_SESSION['id_investor'];
$id_proposal = $_POST['id_proposal'];

// Ambil data proposal dan user UMKM
$q = $koneksi->query("SELECT id_user, judul_proposal FROM proposal WHERE id_proposal = $id_proposal");
$data = $q->fetch_assoc();
$id_user_umkm = $data['id_user'];
$judul_proposal = $data['judul_proposal'];

// Cek apakah notifikasi yang sama sudah pernah dibuat
$cekNotif = $koneksi->query("
  SELECT id_notifikasi FROM notifikasi 
  WHERE id_user = $id_user_umkm 
    AND judul = 'Proposal Diterima oleh Investor'
    AND isi = 'Proposal \"$judul_proposal\" telah diterima oleh investor.'
");

if ($cekNotif->num_rows == 0) {
  // Jika belum ada, buat notifikasi baru
  $koneksi->query("
    INSERT INTO notifikasi (id_user, judul, isi, tanggal, status)
    VALUES (
      $id_user_umkm,
      'Proposal Diterima oleh Investor',
      'Proposal \"$judul_proposal\" telah diterima oleh investor.',
      NOW(),
      'belum_dibaca'
    )
  ");
}

// Update status proposal jadi diterima
$koneksi->query("UPDATE proposal SET status='diterima' WHERE id_proposal=$id_proposal");

echo "<script>alert('Proposal diterima dan notifikasi dikirim (tanpa duplikat)!'); window.location='daftar_proposal.php';</script>";
?>
