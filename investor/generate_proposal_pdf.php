<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/functions.php';
require_role('investor');
require_once __DIR__ . '/../includes/koneksi.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_GET['id'])) {
    die("ID proposal tidak ditemukan.");
}

$id = (int) $_GET['id'];

$proposal = $koneksi->query("
  SELECT p.*, u.nama_usaha, u.bidang_usaha, u.alamat, u.deskripsi, u.id_umkm
  FROM proposal p
  JOIN umkm_profile u ON p.id_umkm = u.id_umkm
  WHERE p.id_proposal = $id
")->fetch_assoc();

if (!$proposal) {
    die("Proposal tidak ditemukan.");
}

// Tentukan path foto usaha
$fotoPath = $proposal['foto_usaha'] 
    ? "../uploads/proposal/" . $proposal['foto_usaha']
    : "../assets/img/no-image.png";

// Convert foto ke base64 agar bisa masuk ke PDF
$fotoBase64 = "";
if (file_exists($fotoPath)) {
    $imageData = file_get_contents($fotoPath);
    $fotoBase64 = 'data:image/' . pathinfo($fotoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode($imageData);
}

// HTML untuk PDF
$html = "
<style>
  body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 12px;
    line-height: 1.5;
  }
  .header {
    text-align: center;
    margin-bottom: 15px;
  }
  .foto-usaha {
    width: 100%;
    max-height: 260px;
    object-fit: cover;
    border-radius: 10px;
    margin-bottom: 20px;
  }
  h2 {
    text-align: center;
    margin-bottom: 5px;
  }
  h3 {
    margin-top: 20px;
    border-bottom: 1px solid #ccc;
    padding-bottom: 5px;
  }
</style>

<div class='header'>
  <h2>Detail Proposal Usaha</h2>
  <p><strong>" . $proposal['judul_proposal'] . "</strong></p>
</div>

<img src='$fotoBase64' class='foto-usaha'>

<h3>Informasi UMKM</h3>
<p><strong>Nama Usaha:</strong> {$proposal['nama_usaha']}</p>
<p><strong>Bidang Usaha:</strong> {$proposal['bidang_usaha']}</p>
<p><strong>Alamat:</strong> {$proposal['alamat']}</p>

<h3>Deskripsi Usaha</h3>
<p>".nl2br($proposal['deskripsi'])."</p>

<h3>Keunikan Usaha</h3>
<p>".nl2br($proposal['keunikan_usaha'] ?: '-')."</p>

<h3>Strategi Pemasaran</h3>
<p>".nl2br($proposal['strategi_pemasaran'] ?: '-')."</p>

<h3>Rincian Investasi</h3>
<p><strong>Modal Dibutuhkan:</strong> Rp ".number_format($proposal['modal_dibutuhkan'], 0, ',', '.')."</p>
<p><strong>Estimasi Keuntungan:</strong> {$proposal['estimasi_keuntungan']}</p>
<p><strong>Tujuan Penggunaan Dana:</strong><br>".nl2br($proposal['tujuan_penggunaan'])."</p>
";

// DOMPDF OPTIONS (biar gambar tampil)
$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Download PDF
$dompdf->stream("proposal-{$proposal['judul_proposal']}.pdf", ["Attachment" => true]);
