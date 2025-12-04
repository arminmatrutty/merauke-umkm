<?php
require_once __DIR__ . '/../includes/functions.php';
require_role('investor');
require_once __DIR__ . '/../includes/header.php';

if (!isset($_GET['id'])) {
  header('Location: dashboard.php');
  exit;
}

$id_proposal = (int) $_GET['id'];

/* ============================================================
   🔥 Tambahkan Hitungan Views (DILIHAT OLEH INVESTOR)
   ============================================================ */
$koneksi->query("UPDATE proposal SET views = views + 1 WHERE id_proposal = $id_proposal");

/* ============================================================
   Ambil Data Proposal + Profil UMKM
   ============================================================ */
$proposal = $koneksi->query("
  SELECT p.*, u.nama_usaha, u.bidang_usaha, u.alamat, u.deskripsi, u.id_umkm,
         us.id_user AS id_user_umkm
  FROM proposal p
  JOIN umkm_profile u ON p.id_umkm = u.id_umkm
  JOIN users us ON u.id_user = us.id_user
  WHERE p.id_proposal = $id_proposal
")->fetch_assoc();

if (!$proposal) {
  echo "<div class='container mt-5'><div class='alert alert-danger'>Proposal tidak ditemukan.</div></div>";
  require_once __DIR__ . '/../includes/footer.php';
  exit;
}

/* ============================================================
   Jika Investor Tekan "Investasikan Sekarang"
   ============================================================ */
if (isset($_POST['invest_now'])) {
  $id_investor = $_SESSION['id_user'];
  $tanggal = date('Y-m-d');
  $waktu = date('H:i:s');

  $stmt = $koneksi->prepare("
    INSERT INTO jadwal_investasi (id_proposal, id_investor, tanggal, waktu, status)
    VALUES (?, ?, ?, ?, 'menunggu konfirmasi admin')
  ");
  $stmt->bind_param("iiss", $id_proposal, $id_investor, $tanggal, $waktu);
  $stmt->execute();

  // Notifikasi untuk UMKM
  create_notification(
    $koneksi,
    $proposal['id_user_umkm'],
    "Proposal Diterima oleh Investor",
    "Proposal '{$proposal['judul_proposal']}' telah diterima dan disetujui oleh investor untuk dijadwalkan pertemuan."
  );

  echo "<script>
    alert('Investasi berhasil diajukan! Pelaku UMKM telah menerima notifikasi.');
    window.location.href = 'dashboard.php';
  </script>";
  exit;
}
?>

<style>
  .proposal-header {
    background: linear-gradient(135deg, #007bff, #00bcd4);
    color: white;
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 30px;
  }
  .proposal-img {
    width: 100%;
    max-height: 350px;
    object-fit: cover;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.15);
  }
  .info-card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 3px 15px rgba(0,0,0,0.08);
    background: white;
  }
  .info-title {
    font-weight: 700;
    color: #004aad;
  }
  .info-label {
    color: #888;
    font-size: 0.9rem;
  }
  .btn-invest {
    background: linear-gradient(45deg, #00bcd4, #007bff);
    border: none;
    border-radius: 10px;
    padding: 10px 25px;
    transition: transform 0.2s ease;
  }
  .btn-invest:hover {
    transform: translateY(-2px);
    background: linear-gradient(45deg, #009dc0, #006ce0);
  }
</style>

<div class="container mt-4 mb-5">

  <div class="proposal-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
    <div>
      <h3 class="fw-bold mb-1"><?= htmlspecialchars($proposal['judul_proposal']) ?></h3>
      <p class="mb-0">
        Usaha oleh <strong><?= htmlspecialchars($proposal['nama_usaha']) ?></strong> |
        <?= htmlspecialchars($proposal['bidang_usaha']) ?>
      </p>
    </div>
    <a href="dashboard.php" class="btn btn-light text-primary mt-3 mt-md-0">
      <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
  </div>

  <div class="row g-4">
    <div class="col-md-7">
      
      <img src="<?= $proposal['foto_usaha'] 
        ? '../uploads/proposal/' . htmlspecialchars($proposal['foto_usaha']) 
        : '../assets/img/no-image.png' ?>" 
        alt="Foto Usaha"
        class="proposal-img mb-4">

      <div class="card info-card p-4 mb-4">
        <h5 class="info-title mb-3"><i class="fas fa-info-circle me-2"></i> Deskripsi Usaha</h5>
        <p><?= nl2br(htmlspecialchars($proposal['deskripsi'])) ?></p>

        <h5 class="info-title mt-4 mb-3"><i class="fas fa-lightbulb me-2"></i> Keunikan Usaha</h5>
        <p><?= nl2br(htmlspecialchars($proposal['keunikan_usaha'] ?: '-')) ?></p>

        <h5 class="info-title mt-4 mb-3"><i class="fas fa-bullhorn me-2"></i> Strategi Pemasaran</h5>
        <p><?= nl2br(htmlspecialchars($proposal['strategi_pemasaran'] ?: '-')) ?></p>
      </div>
    </div>

    <div class="col-md-5">
      <div class="card info-card p-4 mb-4">

        <h5 class="info-title mb-3">
          <i class="fas fa-chart-line me-2"></i> Rincian Investasi
        </h5>

        <div class="mb-3">
          <div class="info-label">Modal Dibutuhkan</div>
          <h5 class="fw-bold text-success">
            Rp <?= number_format($proposal['modal_dibutuhkan'], 0, ',', '.') ?>
          </h5>
        </div>

        <div class="mb-3">
          <div class="info-label">Estimasi Keuntungan</div>
          <p><?= htmlspecialchars($proposal['estimasi_keuntungan']) ?></p>
        </div>

        <div class="mb-3">
          <div class="info-label">Tujuan Penggunaan Dana</div>
          <p><?= nl2br(htmlspecialchars($proposal['tujuan_penggunaan'])) ?></p>
        </div>

        <form method="POST">
          <button type="submit" name="invest_now" class="btn btn-invest text-white px-4 mb-3">
            <i class="fas fa-hand-holding-usd me-2"></i>
            Investasikan Sekarang
          </button>
        </form>

        <a href="generate_proposal_pdf.php?id=<?= $id_proposal ?>" 
           target="_blank" 
           class="btn btn-danger text-white px-4">
          <i class="fas fa-file-pdf me-2"></i>
          Download Proposal (PDF)
        </a>

      </div>

      <div class="card info-card p-4">
        <h5 class="info-title mb-3">
          <i class="fas fa-map-marker-alt me-2"></i> Lokasi UMKM
        </h5>

        <p><i class="fas fa-store me-2 text-primary"></i> 
          <?= htmlspecialchars($proposal['nama_usaha']) ?>
        </p>

        <p>
          <i class="fas fa-map me-2 text-primary"></i> 
          <?= htmlspecialchars($proposal['alamat']) ?>
        </p>
      </div>
    </div>

  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
