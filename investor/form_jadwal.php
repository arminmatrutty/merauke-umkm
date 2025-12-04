<?php
require_once __DIR__ . '/../includes/functions.php';
require_role('investor');
require_once __DIR__ . '/../includes/header.php';

if (!isset($_GET['id'])) {
  header('Location: dashboard.php');
  exit;
}

$id_proposal = (int) $_GET['id'];
$id_user = $_SESSION['id_user'];
$investor = $koneksi->query("SELECT * FROM investor_profile WHERE id_user = $id_user")->fetch_assoc();
$proposal = $koneksi->query("
  SELECT p.id_proposal, p.judul_proposal, p.modal_dibutuhkan, u.nama_usaha
  FROM proposal p
  JOIN umkm_profile u ON p.id_umkm = u.id_umkm
  WHERE p.id_proposal = $id_proposal
")->fetch_assoc();

if (!$proposal) {
  echo "<div class='container mt-5'><div class='alert alert-danger'>Proposal tidak ditemukan.</div></div>";
  require_once __DIR__ . '/../includes/footer.php';
  exit;
}

$success = $error = "";
if (isset($_POST['submit'])) {
  $tanggal = $_POST['tanggal'];
  $waktu = $_POST['waktu'];
  $catatan = trim($_POST['catatan']);

  if ($tanggal && $waktu) {
    $stmt = $koneksi->prepare("
      INSERT INTO jadwal_investasi (id_investor, id_proposal, tanggal, waktu, catatan, status)
      VALUES (?, ?, ?, ?, ?, 'menunggu konfirmasi admin')
    ");
    $stmt->bind_param("iisss", $investor['id_investor'], $id_proposal, $tanggal, $waktu, $catatan);

    if ($stmt->execute()) {
      $success = "Jadwal pertemuan berhasil diajukan! Menunggu konfirmasi dari admin.";
    } else {
      $error = "Terjadi kesalahan saat menyimpan data. Silakan coba lagi.";
    }
    $stmt->close();
  } else {
    $error = "Tanggal dan waktu wajib diisi.";
  }
}
?>

<style>
  .form-card {
    max-width: 750px;
    margin: auto;
    background: rgba(255,255,255,0.95);
    border-radius: 20px;
    padding: 30px;
    border: none;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
  }
  body.dark-mode .form-card {
    background: rgba(30,30,30,0.95);
  }
  .form-title {
    font-weight: 700;
    color: #004aad;
  }
  .btn-submit {
    background: linear-gradient(45deg, #007bff, #00bcd4);
    border: none;
    border-radius: 10px;
    padding: 10px 25px;
    transition: transform 0.2s ease;
  }
  .btn-submit:hover {
    transform: translateY(-2px);
    background: linear-gradient(45deg, #005ecb, #00a4b4);
  }
  .proposal-summary {
    background: linear-gradient(135deg, #007bff, #00bcd4);
    border-radius: 15px;
    color: white;
    padding: 20px;
    margin-bottom: 20px;
  }
</style>

<div class="container mt-4 mb-5">
  <div class="proposal-summary">
    <h5 class="mb-1"><i class="fas fa-briefcase me-2"></i><?= htmlspecialchars($proposal['judul_proposal']) ?></h5>
    <p class="mb-0">Pelaku usaha: <strong><?= htmlspecialchars($proposal['nama_usaha']) ?></strong></p>
    <small>Modal dibutuhkan: Rp <?= number_format($proposal['modal_dibutuhkan'], 0, ',', '.') ?></small>
  </div>

  <div class="form-card">
    <h4 class="form-title mb-4"><i class="fas fa-calendar-check me-2"></i> Jadwalkan Pertemuan Investasi</h4>

    <?php if ($success): ?>
      <div class="alert alert-success text-center">
        <i class="fas fa-check-circle me-1"></i> <?= $success ?>
      </div>
      <div class="text-center">
        <a href="dashboard.php" class="btn btn-primary mt-3"><i class="fas fa-home me-1"></i> Kembali ke Dashboard</a>
      </div>
    <?php else: ?>
      <?php if ($error): ?><div class="alert alert-danger"><i class="fas fa-exclamation-circle me-1"></i> <?= $error ?></div><?php endif; ?>

      <form method="post">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Tanggal Pertemuan *</label>
            <input type="date" name="tanggal" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Waktu Pertemuan *</label>
            <input type="time" name="waktu" class="form-control" required>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Catatan (opsional)</label>
          <textarea name="catatan" class="form-control" rows="3" placeholder="Contoh: Pertemuan di kantor atau via Zoom..."></textarea>
        </div>

        <div class="text-end">
          <button type="submit" name="submit" class="btn btn-submit text-white">
            <i class="fas fa-paper-plane me-2"></i> Kirim Jadwal
          </button>
        </div>
      </form>
    <?php endif; ?>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>