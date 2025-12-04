<?php
require_once __DIR__ . '/includes/functions.php';
if (session_status() == PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/includes/header.php';

// Pastikan sudah login
if (!isset($_SESSION['id_user'])) {
  header("Location: login.php");
  exit;
}

$id_user = $_SESSION['id_user'];
$role = $_SESSION['role'];
$success = $error = "";

// Ambil data sesuai role
switch ($role) {
  case 'umkm':
    $data = $koneksi->query("SELECT * FROM umkm_profile WHERE id_user = $id_user")->fetch_assoc();
    break;
  case 'investor':
    $data = $koneksi->query("SELECT * FROM investor_profile WHERE id_user = $id_user")->fetch_assoc();
    break;
  default:
    $data = null;
}

if (isset($_POST['update'])) {
  $nama = trim($_POST['nama']);
  $alamat = trim($_POST['alamat']);
  $no_hp = trim($_POST['no_hp']);
  $foto = $data['foto_profil'] ?? null;

  // Upload foto baru jika ada
  if (!empty($_FILES['foto']['name'])) {
    $targetDir = "uploads/profil/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
    $fileName = time() . "_" . basename($_FILES["foto"]["name"]);
    $targetFile = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (in_array($fileType, $allowed) && $_FILES["foto"]["size"] < 3*1024*1024) {
      if (move_uploaded_file($_FILES["foto"]["tmp_name"], $targetFile)) {
        $foto = $fileName;
      } else {
        $error = "Gagal mengupload foto baru.";
      }
    } else {
      $error = "Format file harus JPG/PNG/WEBP, ukuran maksimal 3MB.";
    }
  }

  if (!$error) {
    if ($role == 'umkm') {
      $stmt = $koneksi->prepare("UPDATE umkm_profile SET alamat=?, no_hp=?, foto_profil=?, tanggal_update=NOW() WHERE id_user=?");
      $stmt->bind_param("sssi", $alamat, $no_hp, $foto, $id_user);
    } elseif ($role == 'investor') {
      $stmt = $koneksi->prepare("UPDATE investor_profile SET alamat=?, no_hp=?, foto_profil=?, tanggal_update=NOW() WHERE id_user=?");
      $stmt->bind_param("sssi", $alamat, $no_hp, $foto, $id_user);
    }

    if (isset($stmt) && $stmt->execute()) {
      $success = "Profil berhasil diperbarui!";
      $_SESSION['nama'] = $nama;
    } else {
      $error = "Terjadi kesalahan saat memperbarui profil.";
    }
  }
}
?>

<div class="container mt-4 mb-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-primary text-white py-3">
          <h4 class="mb-0 fw-bold"><i class="fas fa-pen me-2"></i> Edit Profil</h4>
        </div>
        <div class="card-body p-4">
          <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
            <a href="profile.php" class="btn btn-primary">Kembali ke Profil</a>
          <?php else: ?>
            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

            <form method="post" enctype="multipart/form-data">
              <div class="text-center mb-3">
                <img src="<?= !empty($data['foto_profil']) ? '/merauke_umkm/uploads/profil/'.$data['foto_profil'] : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png' ?>" 
                     alt="Foto Profil" class="rounded-circle shadow-sm mb-2" width="120" height="120">
                <div>
                  <input type="file" name="foto" accept=".jpg,.jpeg,.png,.webp" class="form-control w-50 mx-auto">
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($_SESSION['nama']) ?>" readonly>
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">Alamat</label>
                <textarea name="alamat" class="form-control" rows="2"><?= htmlspecialchars($data['alamat'] ?? '') ?></textarea>
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">Nomor HP</label>
                <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($data['no_hp'] ?? '') ?>">
              </div>

              <div class="text-end mt-4">
                <button type="submit" name="update" class="btn btn-primary px-4">
                  <i class="fas fa-save me-1"></i> Simpan Perubahan
                </button>
                <a href="profile.php" class="btn btn-secondary">Batal</a>
              </div>
            </form>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
