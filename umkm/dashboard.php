<?php
require_once __DIR__ . '/../includes/functions.php';
require_role('umkm');
require_once __DIR__ . '/../includes/header.php';

$id_user = $_SESSION['id_user'] ?? null;

if (!isset($koneksi)) {
    die("Koneksi database tidak ditemukan!");
}

$umkm = null;
if ($id_user) {
    $stmt = $koneksi->query("SELECT * FROM umkm_profile WHERE id_user = $id_user");
    if ($stmt && $stmt->num_rows > 0) {
        $umkm = $stmt->fetch_assoc();
    }
}

$proposal = null;
$stat_total = $stat_disetujui = $stat_revisi = $stat_ditolak = 0;

if ($umkm) {
    $id_umkm = (int)$umkm['id_umkm'];

    $query = $koneksi->query("
        SELECT * FROM proposal 
        WHERE id_umkm = $id_umkm 
        ORDER BY tanggal_pengajuan DESC 
        LIMIT 1
    ");

    if ($query && $query->num_rows > 0) {
        $proposal = $query->fetch_assoc();
    }

    $getCount = function($where = '') use ($koneksi, $id_umkm) {
        $sql = "SELECT COUNT(*) AS jml FROM proposal WHERE id_umkm = $id_umkm $where";
        $r = $koneksi->query($sql);
        return $r ? (int)$r->fetch_assoc()['jml'] : 0;
    };

    $stat_total     = $getCount();
    $stat_disetujui = $getCount(" AND status='disetujui'");
    $stat_revisi    = $getCount(" AND status='revisi'");
    $stat_ditolak   = $getCount(" AND status='ditolak'");
}
?>

<style>
.dashboard-header {
  background: linear-gradient(135deg, #007bff, #00bcd4);
  color: white;
  border-radius: 16px;
  padding: 30px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.stat-card {
  border-radius: 15px;
  transition: 0.2s ease;
}
.stat-card:hover { transform: translateY(-4px); }
.stat-icon { font-size: 28px; padding: 15px; border-radius: 12px; color: white; }
.bg-proposal { background: linear-gradient(45deg, #007bff, #00bcd4); }
.bg-approve { background: linear-gradient(45deg, #28a745, #6cc070); }
.bg-revisi { background: linear-gradient(45deg, #ffc107, #ffb84d); color:#333; }
.bg-tolak { background: linear-gradient(45deg, #dc3545, #ff6b6b); }
.card-modern { border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
</style>

<div class="container mt-4 mb-5">

  <div class="dashboard-header mb-4">
    <h3>Selamat Datang, <?= htmlspecialchars($_SESSION['nama']) ?> 👋</h3>
    <p class="mb-0">
      Pantau perkembangan usaha Anda bersama <strong>CrowdUMKM Merauke</strong>.
    </p>
  </div>

  <?php if (!$umkm): ?>
    <div class="alert alert-warning">
      <strong>Profil UMKM belum lengkap.</strong> Silakan lengkapi profil Anda terlebih dahulu.
      <br>
      <a href="profil_umkm.php" class="btn btn-primary btn-sm mt-2">Lengkapi Profil</a>
    </div>

  <?php else: ?>

  <div class="row text-center mb-4">

    <div class="col-md-3 mb-3">
      <div class="card stat-card p-3">
        <div class="stat-icon bg-proposal mx-auto mb-2"><i class="fas fa-clipboard-list"></i></div>
        <h6>Total Proposal</h6>
        <h4 class="fw-bold text-primary"><?= $stat_total ?></h4>
      </div>
    </div>

    <div class="col-md-3 mb-3">
      <div class="card stat-card p-3">
        <div class="stat-icon bg-approve mx-auto mb-2"><i class="fas fa-check-circle"></i></div>
        <h6>Disetujui</h6>
        <h4 class="fw-bold text-success"><?= $stat_disetujui ?></h4>
      </div>
    </div>

    <div class="col-md-3 mb-3">
      <div class="card stat-card p-3">
        <div class="stat-icon bg-revisi mx-auto mb-2"><i class="fas fa-edit"></i></div>
        <h6>Revisi</h6>
        <h4 class="fw-bold text-warning"><?= $stat_revisi ?></h4>
      </div>
    </div>

    <div class="col-md-3 mb-3">
      <div class="card stat-card p-3">
        <div class="stat-icon bg-tolak mx-auto mb-2"><i class="fas fa-times-circle"></i></div>
        <h6>Ditolak</h6>
        <h4 class="fw-bold text-danger"><?= $stat_ditolak ?></h4>
      </div>
    </div>

  </div>


  <div class="row">

    <div class="col-md-8 mb-4">
      <div class="card card-modern p-4">
        <h5 class="fw-semibold mb-3 text-primary">
          <i class="fas fa-file-alt me-2"></i> Status Proposal Terakhir
        </h5>

        <?php if ($proposal): ?>
          <p><strong>Judul:</strong> <?= htmlspecialchars($proposal['judul_proposal']) ?></p>

          <p><strong>Status:</strong>
            <span class="badge bg-<?= 
              $proposal['status'] == 'disetujui' ? 'success' :
              ($proposal['status'] == 'ditolak' ? 'danger' :
              ($proposal['status'] == 'revisi' ? 'warning' : 'secondary')) ?> fs-6">
              <?= ucfirst($proposal['status']) ?>
            </span>
          </p>

          <!-- ✅ FITUR TAMBAHAN: JUMLAH VIEW -->
          <p><strong>Dilihat:</strong> 
            <span class="fw-bold text-primary"><?= $proposal['views'] ?> kali</span>
          </p>
          <!-- END -->

          <?php if ($proposal['status'] == 'revisi'): ?>
            <a href="revisi_proposal.php?id=<?= $proposal['id_proposal'] ?>" class="btn btn-warning">
              <i class="fas fa-pen me-1"></i> Lihat Revisi
            </a>

          <?php elseif ($proposal['status'] == 'disetujui'): ?>
            <div class="alert alert-success mt-3">
              Proposal telah <strong>disetujui</strong>.  
              Cek <a href="notifikasi.php">Notifikasi</a>.
            </div>

          <?php elseif ($proposal['status'] == 'ditolak'): ?>
            <div class="alert alert-danger mt-3">
              Proposal <strong>ditolak</strong>. Silakan ajukan ulang.
            </div>

          <?php else: ?>
            <div class="alert alert-secondary mt-3">
              Proposal sedang dalam proses <strong>peninjauan</strong>.
            </div>
          <?php endif; ?>

        <?php else: ?>
          <div class="text-center text-muted py-4">
            <i class="fas fa-folder-open fa-2x mb-3"></i>
            <p>Anda belum mengajukan proposal apapun.</p>
            <a href="form_proposal.php" class="btn btn-primary">
              <i class="fas fa-plus-circle me-1"></i> Ajukan Proposal Sekarang
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>


    <div class="col-md-4">
      <div class="card card-modern p-3 h-100">
        <h6 class="fw-semibold mb-3 text-primary">
          <i class="fas fa-bell me-2"></i> Pemberitahuan Terbaru
        </h6>

        <?php
        $notif = $koneksi->query("
          SELECT n1.* FROM notifikasi n1
          INNER JOIN (
            SELECT judul, isi, MAX(tanggal) AS max_tanggal
            FROM notifikasi
            WHERE id_user = $id_user
            GROUP BY judul, isi
          ) n2 
          ON n1.judul = n2.judul AND n1.isi = n2.isi AND n1.tanggal = n2.max_tanggal
          ORDER BY n1.tanggal DESC
          LIMIT 5
        ");

        if ($notif && $notif->num_rows > 0):
            while ($n = $notif->fetch_assoc()): ?>
              <div class="border-bottom pb-2 mb-2">
                <small class="text-muted"><?= date('d M Y H:i', strtotime($n['tanggal'])) ?></small>
                <div class="fw-semibold"><?= htmlspecialchars($n['judul']) ?></div>
                <small><?= htmlspecialchars($n['isi']) ?></small>
              </div>
        <?php endwhile;
        else: ?>
          <div class="text-center text-muted py-3">
            <i class="fas fa-bell-slash fa-2x mb-2"></i>
            <p>Belum ada pemberitahuan baru.</p>
          </div>
        <?php endif; ?>

      </div>
    </div>

  </div>

  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
