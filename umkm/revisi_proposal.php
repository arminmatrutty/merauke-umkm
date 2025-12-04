<?php
require_once __DIR__ . '/../includes/functions.php';
require_role('umkm');
require_once __DIR__ . '/../includes/header.php';

$id_user = $_SESSION['id_user'];
$umkm = $koneksi->query("SELECT * FROM umkm_profile WHERE id_user = $id_user")->fetch_assoc();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$proposal = $koneksi->query("SELECT * FROM proposal WHERE id_proposal = $id AND id_umkm = {$umkm['id_umkm']}")->fetch_assoc();

if (!$proposal) {
  echo "<div class='alert alert-danger m-4'>Proposal tidak ditemukan.</div>";
  require_once __DIR__ . '/../includes/footer.php';
  exit;
}

$success = $error = "";

// proses update revisi
if (isset($_POST['submit'])) {
  $judul = trim($_POST['judul']);
  $deskripsi = trim($_POST['deskripsi']);
  $modal = (float) $_POST['modal'];
  $tujuan = trim($_POST['tujuan']);
  $estimasi = trim($_POST['estimasi']);
  $keunikan = trim($_POST['keunikan']);
  $strategi = trim($_POST['strategi']);

  if ($judul && $deskripsi && $modal > 0) {
    $stmt = $koneksi->prepare("
      UPDATE proposal SET 
        judul_proposal=?, deskripsi=?, modal_dibutuhkan=?, tujuan_penggunaan=?, 
        estimasi_keuntungan=?, keunikan_usaha=?, strategi_pemasaran=?, 
        status='menunggu', tanggal_pengajuan=NOW()
      WHERE id_proposal=? AND id_umkm=?
    ");
    $stmt->bind_param("ssdssssii", $judul, $deskripsi, $modal, $tujuan, $estimasi, $keunikan, $strategi, $id, $umkm['id_umkm']);

    if ($stmt->execute()) {
      $success = "Proposal revisi berhasil dikirim! Menunggu peninjauan ulang dari Admin.";
    } else {
      $error = "Gagal memperbarui data. Coba lagi.";
    }
    $stmt->close();
  } else {
    $error = "Harap lengkapi semua kolom wajib.";
  }
}
?>

<div class="container mt-4">
  <h3 class="fw-bold text-primary mb-4"><i class="fas fa-edit me-2"></i> Revisi Proposal</h3>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
    <a href="dashboard.php" class="btn btn-primary">Kembali ke Dashboard</a>
  <?php else: ?>

    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <div class="card shadow border-0 p-4 mb-4">
      <h5 class="fw-semibold text-dark mb-3">Catatan Revisi dari Admin</h5>
      <?php if ($proposal['catatan_revisi']): ?>
        <div class="alert alert-warning"><?= nl2br(htmlspecialchars($proposal['catatan_revisi'])) ?></div>
      <?php else: ?>
        <p class="text-muted">Tidak ada catatan revisi dari admin.</p>
      <?php endif; ?>
    </div>

    <form method="post" class="card shadow border-0 p-4">
      <h5 class="fw-semibold text-dark mb-3">Perbaiki Data Proposal</h5>

      <div class="mb-3">
        <label class="form-label fw-semibold">Judul Proposal *</label>
        <input type="text" name="judul" class="form-control" required value="<?= htmlspecialchars($proposal['judul_proposal']) ?>">
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Deskripsi Usaha *</label>
        <textarea name="deskripsi" class="form-control" rows="4" required><?= htmlspecialchars($proposal['deskripsi']) ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Modal yang Dibutuhkan (Rp) *</label>
        <input type="number" name="modal" class="form-control" min="100000" required value="<?= htmlspecialchars($proposal['modal_dibutuhkan']) ?>">
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Tujuan Penggunaan Dana *</label>
        <textarea name="tujuan" class="form-control" rows="3" required><?= htmlspecialchars($proposal['tujuan_penggunaan']) ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Estimasi Keuntungan *</label>
        <textarea name="estimasi" class="form-control" rows="3" required><?= htmlspecialchars($proposal['estimasi_keuntungan']) ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Keunikan Usaha</label>
        <textarea name="keunikan" class="form-control" rows="3"><?= htmlspecialchars($proposal['keunikan_usaha']) ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Strategi Pemasaran</label>
        <textarea name="strategi" class="form-control" rows="3"><?= htmlspecialchars($proposal['strategi_pemasaran']) ?></textarea>
      </div>

      <div class="text-end">
        <button type="submit" name="submit" class="btn btn-primary px-4">
          <i class="fas fa-paper-plane me-1"></i> Kirim Revisi Proposal
        </button>
      </div>
    </form>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>