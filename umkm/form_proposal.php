<?php
require_once __DIR__ . '/../includes/functions.php';
require_role('umkm');
require_once __DIR__ . '/../includes/header.php';

$id_user = $_SESSION['id_user'];
$umkm = $koneksi->query("SELECT * FROM umkm_profile WHERE id_user = $id_user")->fetch_assoc();

$success = $error = "";

if (isset($_POST['submit'])) {
  $judul = trim($_POST['judul']);
  $deskripsi = trim($_POST['deskripsi']);
  $modal = (float) $_POST['modal'];
  $tujuan = trim($_POST['tujuan']);
  $estimasi = trim($_POST['estimasi']);
  $keunikan = trim($_POST['keunikan']);
  $strategi = trim($_POST['strategi']);
  $foto_usaha = null;

  if (!empty($_FILES['foto']['name'])) {
    $targetDir = "../uploads/proposal/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
    $fileName = time() . "_" . basename($_FILES["foto"]["name"]);
    $targetFile = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'webp'];

    if (in_array($fileType, $allowedTypes) && $_FILES["foto"]["size"] < 3*1024*1024) {
      if (move_uploaded_file($_FILES["foto"]["tmp_name"], $targetFile)) {
        $foto_usaha = $fileName;
      } else {
        $error = "Gagal mengunggah foto usaha.";
      }
    } else {
      $error = "Format file harus JPG/PNG/WEBP dan ukuran maksimal 3MB.";
    }
  }

  if ($judul && $deskripsi && $modal > 0 && !$error) {
    $stmt = $koneksi->prepare("
      INSERT INTO proposal 
      (id_umkm, judul_proposal, deskripsi, modal_dibutuhkan, tujuan_penggunaan, estimasi_keuntungan, keunikan_usaha, strategi_pemasaran, foto_usaha)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("issssssss", 
      $umkm['id_umkm'], $judul, $deskripsi, $modal, $tujuan, $estimasi, $keunikan, $strategi, $foto_usaha
    );

    if ($stmt->execute()) {
      $success = "Proposal berhasil diajukan! Silakan menunggu verifikasi dari Admin.";
    } else {
      $error = "Terjadi kesalahan saat menyimpan data. Coba lagi.";
    }
    $stmt->close();
  } elseif (!$error) {
    $error = "Harap lengkapi semua kolom wajib.";
  }
}
?>

<style>
  .proposal-container {
    max-width: 850px;
    margin: auto;
  }
  .proposal-card {
    background: rgba(255,255,255,0.9);
    border-radius: 20px;
    border: none;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    backdrop-filter: blur(10px);
  }
  body.dark-mode .proposal-card {
    background: rgba(30,30,30,0.95);
  }
  label {
    font-weight: 600;
    color: #004aad;
  }
  .form-control, textarea {
    border-radius: 10px;
  }
  .btn-primary {
    background: linear-gradient(45deg, #007bff, #00bcd4);
    border: none;
    border-radius: 10px;
    padding: 10px 25px;
    transition: transform 0.2s ease;
  }
  .btn-primary:hover {
    transform: translateY(-2px);
    background: linear-gradient(45deg, #005ecb, #00a4b4);
  }
  #preview {
    border-radius: 12px;
    margin-top: 10px;
    transition: all 0.3s ease;
  }
  #preview:hover {
    transform: scale(1.03);
  }
  .title-bar {
    text-align: center;
    padding-bottom: 20px;
  }
  .title-bar h3 {
    font-weight: 700;
    color: #007bff;
  }
  .title-bar p {
    color: #555;
  }
</style>

<div class="container mt-5 mb-5 proposal-container">
  <div class="proposal-card p-4 p-md-5">
    <div class="title-bar">
      <h3><i class="fas fa-file-alt me-2"></i> Form Pengajuan Proposal</h3>
      <p>Isi dengan lengkap dan jelas agar proposal Anda mudah diverifikasi oleh admin dan menarik bagi investor.</p>
    </div>

    <?php if ($success): ?>
      <div class="alert alert-success text-center">
        <i class="fas fa-check-circle me-1"></i> <?= $success ?>
      </div>
      <div class="text-center">
        <a href="dashboard.php" class="btn btn-primary mt-3"><i class="fas fa-home me-1"></i> Kembali ke Dashboard</a>
      </div>
    <?php else: ?>
      <?php if ($error): ?><div class="alert alert-danger"><i class="fas fa-exclamation-circle me-1"></i> <?= $error ?></div><?php endif; ?>

      <form method="post" enctype="multipart/form-data">
        <div class="row">
          <div class="col-md-12 mb-3">
            <label>Judul Proposal *</label>
            <input type="text" name="judul" class="form-control" placeholder="Contoh: Pengembangan Usaha Kopi Lokal" required>
          </div>

          <div class="col-md-12 mb-3">
            <label>Deskripsi Usaha *</label>
            <textarea name="deskripsi" class="form-control" rows="4" placeholder="Ceritakan usaha Anda secara ringkas dan menarik..." required></textarea>
          </div>

          <div class="col-md-6 mb-3">
            <label>Modal yang Dibutuhkan (Rp) *</label>
            <input type="number" name="modal" class="form-control" min="100000" placeholder="cth: 5000000" required>
          </div>

          <div class="col-md-6 mb-3">
            <label>Estimasi Keuntungan *</label>
            <input type="text" name="estimasi" class="form-control" placeholder="Contoh: 15% per bulan" required>
          </div>

          <div class="col-md-12 mb-3">
            <label>Tujuan Penggunaan Dana *</label>
            <textarea name="tujuan" class="form-control" rows="3" placeholder="Dana akan digunakan untuk pembelian alat produksi..." required></textarea>
          </div>

          <div class="col-md-6 mb-3">
            <label>Keunikan Usaha</label>
            <textarea name="keunikan" class="form-control" rows="3" placeholder="Apa yang membuat usaha Anda berbeda dari lainnya?"></textarea>
          </div>

          <div class="col-md-6 mb-3">
            <label>Strategi Pemasaran</label>
            <textarea name="strategi" class="form-control" rows="3" placeholder="Bagaimana Anda menjual produk Anda?"></textarea>
          </div>

          <div class="col-md-12 mb-3">
            <label>Foto Usaha (opsional)</label>
            <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png,.webp" onchange="previewImage(event)">
            <div class="text-center">
              <img id="preview" src="#" alt="" style="display:none;max-height:220px;">
            </div>
          </div>
        </div>

        <div class="text-end mt-3">
          <button type="submit" name="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane me-1"></i> Kirim Proposal
          </button>
        </div>
      </form>
    <?php endif; ?>
  </div>
</div>

<script>
function previewImage(event) {
  const preview = document.getElementById('preview');
  const file = event.target.files[0];
  if (file) {
    preview.src = URL.createObjectURL(file);
    preview.style.display = 'block';
  } else {
    preview.src = '';
    preview.style.display = 'none';
  }
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
