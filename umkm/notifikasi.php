<?php
require_once __DIR__ . '/../includes/functions.php';
require_role('umkm');
require_once __DIR__ . '/../includes/header.php';

$id_user = $_SESSION['id_user'];

// Ambil notifikasi user dan jadwal investasi terkait (jika ada)
$notif = $koneksi->query("
  SELECT n.*, j.tanggal AS jadwal_tanggal, j.waktu AS jadwal_waktu, j.catatan AS jadwal_catatan
  FROM notifikasi n
  LEFT JOIN umkm_profile up ON up.id_user = n.id_user
  LEFT JOIN proposal p ON p.id_umkm = up.id_umkm
  LEFT JOIN jadwal_investasi j ON j.id_proposal = p.id_proposal
  WHERE n.id_user = $id_user
  GROUP BY n.judul, n.isi
  ORDER BY n.tanggal DESC
");

// Ubah status jadi dibaca
$koneksi->query("UPDATE notifikasi SET status='dibaca' WHERE id_user = $id_user");
?>

<style>
  .notif-card {
    border-radius: 12px;
    border: none;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .notif-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
  }
  .notif-title { font-weight: 600; color: #0d6efd; }
  .notif-date { color: #6c757d; font-size: 0.9rem; }
  .notif-body { font-size: 0.95rem; color: #333; }
  .notif-extra {
    background-color: #f8f9fa;
    border-left: 4px solid #0d6efd;
    border-radius: 8px;
    padding: 10px 12px;
    margin-top: 8px;
    font-size: 0.9rem;
  }
</style>

<div class="container mt-4 mb-5">
  <h3 class="fw-bold text-primary mb-4"><i class="fas fa-bell me-2"></i> Notifikasi</h3>

  <?php if ($notif && $notif->num_rows > 0): ?>
    <div class="list-group">
      <?php while($n = $notif->fetch_assoc()): ?>
        <div class="list-group-item notif-card mb-2 <?= $n['status'] == 'belum_dibaca' ? 'bg-light' : 'bg-white' ?>">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="notif-title"><?= htmlspecialchars($n['judul']) ?></div>
              <div class="notif-date mb-1"><?= date('d M Y H:i', strtotime($n['tanggal'])) ?></div>
              <div class="notif-body"><?= nl2br(htmlspecialchars($n['isi'])) ?></div>

              <?php 
              // Tampilkan jadwal hanya jika notifikasi berkaitan dengan jadwal
              if (
                stripos($n['judul'], 'jadwal investasi') !== false 
                && !empty($n['jadwal_tanggal'])
              ): ?>
                <div class="notif-extra mt-2">
                  <strong>📅 Jadwal Investasi:</strong><br>
                  <?= date('d M Y', strtotime($n['jadwal_tanggal'])) ?> pukul <?= htmlspecialchars($n['jadwal_waktu']) ?><br>
                  <?php if (!empty($n['jadwal_catatan'])): ?>
                    <strong>Catatan:</strong> <?= nl2br(htmlspecialchars($n['jadwal_catatan'])) ?>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>

            <i class="fas fa-circle text-<?= $n['status']=='belum_dibaca' ? 'warning' : 'secondary' ?>" style="font-size:8px;"></i>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-info"><i class="fas fa-info-circle me-2"></i> Belum ada notifikasi saat ini.</div>
  <?php endif; ?>

  <div class="text-end mt-3">
    <a href="dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
