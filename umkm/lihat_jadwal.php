<?php
require_once __DIR__ . '/../includes/functions.php';
require_role('umkm');
require_once __DIR__ . '/../includes/header.php';

$id_user = $_SESSION['id_user'];
$umkm = $koneksi->query("SELECT * FROM umkm_profile WHERE id_user = $id_user")->fetch_assoc();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// ambil data jadwal beserta proposal dan investor
$q = $koneksi->query("
  SELECT j.*, p.judul_proposal, p.modal_dibutuhkan, p.deskripsi, 
         i.perusahaan, i.jabatan, i.alamat AS alamat_investor, i.no_hp
  FROM jadwal_pertemuan j
  JOIN proposal p ON j.id_proposal = p.id_proposal
  JOIN investor_profile i ON j.id_investor = i.id_investor
  WHERE j.id_jadwal = $id AND p.id_umkm = {$umkm['id_umkm']}
");
$jadwal = $q->fetch_assoc();

if (!$jadwal) {
  echo "<div class='alert alert-danger m-4'>Data jadwal tidak ditemukan.</div>";
  require_once __DIR__ . '/../includes/footer.php';
  exit;
}
?>

<div class="container mt-4 mb-5">
  <h3 class="fw-bold text-primary mb-4">
    <i class="fas fa-calendar-alt me-2"></i> Detail Jadwal Pertemuan
  </h3>

  <div class="card shadow border-0 p-4 mb-4">
    <h5 class="fw-semibold text-dark mb-3"><i class="fas fa-file-signature me-2"></i> Informasi Proposal</h5>
    <p><strong>Judul:</strong> <?= htmlspecialchars($jadwal['judul_proposal']) ?></p>
    <p><strong>Modal Dibutuhkan:</strong> Rp <?= number_format($jadwal['modal_dibutuhkan'], 0, ',', '.') ?></p>
    <p><strong>Deskripsi Usaha:</strong><br><?= nl2br(htmlspecialchars($jadwal['deskripsi'])) ?></p>
  </div>

  <div class="card shadow border-0 p-4 mb-4">
    <h5 class="fw-semibold text-dark mb-3"><i class="fas fa-handshake me-2"></i> Informasi Investor</h5>
    <p><strong>Perusahaan:</strong> <?= htmlspecialchars($jadwal['perusahaan']) ?></p>
    <p><strong>Jabatan:</strong> <?= htmlspecialchars($jadwal['jabatan']) ?></p>
    <p><strong>Alamat:</strong> <?= htmlspecialchars($jadwal['alamat_investor']) ?></p>
    <p><strong>No. HP:</strong> <?= htmlspecialchars($jadwal['no_hp']) ?></p>
  </div>

  <div class="card shadow border-0 p-4">
    <h5 class="fw-semibold text-dark mb-3"><i class="fas fa-clock me-2"></i> Detail Jadwal Pertemuan</h5>
    <p><strong>Status:</strong>
      <span class="badge bg-<?= 
        $jadwal['status']=='ditetapkan' ? 'success' : 
        ($jadwal['status']=='selesai' ? 'secondary' : 'warning') ?>">
        <?= ucfirst($jadwal['status']) ?>
      </span>
    </p>
    <p><strong>Tanggal Usulan Investor:</strong> <?= date('d M Y H:i', strtotime($jadwal['tanggal_usulan'])) ?></p>
    <?php if($jadwal['tanggal_ditetapkan']): ?>
      <p><strong>Tanggal Pertemuan Ditetapkan:</strong> <?= date('d M Y H:i', strtotime($jadwal['tanggal_ditetapkan'])) ?></p>
    <?php else: ?>
      <p><span class="text-muted">Menunggu konfirmasi jadwal dari admin...</span></p>
    <?php endif; ?>

    <p><strong>Keterangan Tambahan:</strong><br>
      <?= $jadwal['keterangan'] ? nl2br(htmlspecialchars($jadwal['keterangan'])) : '<span class="text-muted">Tidak ada keterangan.</span>' ?>
    </p>
  </div>

  <div class="mt-4 text-end">
    <a href="status_proposal.php" class="btn btn-secondary">
      <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
