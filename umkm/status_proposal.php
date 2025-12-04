<?php
require_once __DIR__ . '/../includes/functions.php';
require_role('umkm');
require_once __DIR__ . '/../includes/header.php';

$id_user = $_SESSION['id_user'];
$umkm = $koneksi->query("SELECT * FROM umkm_profile WHERE id_user = $id_user")->fetch_assoc();

$proposals = $koneksi->query("
  SELECT * FROM proposal 
  WHERE id_umkm = {$umkm['id_umkm']}
  ORDER BY tanggal_pengajuan DESC
");
?>

<div class="container mt-4">
  <h3 class="fw-bold text-primary mb-4">
    <i class="fas fa-tasks me-2"></i> Status Proposal Saya
  </h3>

  <div class="card shadow border-0 p-4">
    <?php if ($proposals->num_rows > 0): ?>
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Judul Proposal</th>
            <th>Tanggal Pengajuan</th>
            <th>Dilihat</th>
            <th>Status</th>
            <th>Catatan Admin</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no=1; while($p = $proposals->fetch_assoc()): ?>
            <tr>
              <td><?= $no++ ?></td>

              <td><?= htmlspecialchars($p['judul_proposal']) ?></td>

              <td><?= date('d M Y H:i', strtotime($p['tanggal_pengajuan'])) ?></td>

              <!-- KOLOM DILIHAT -->
              <td>
                <span class="badge bg-info text-dark">
                  <i class="fas fa-eye me-1"></i>
                  <?= $p['views'] ?> kali
                </span>
              </td>

              <td>
                <span class="badge bg-<?= 
                  $p['status']=='disetujui' ? 'success' :
                  ($p['status']=='revisi' ? 'warning' :
                  ($p['status']=='ditolak' ? 'danger' : 'secondary')) ?>">
                  <?= ucfirst($p['status']) ?>
                </span>
              </td>

              <td>
                <?= $p['catatan_revisi'] ? nl2br(htmlspecialchars($p['catatan_revisi'])) : '<span class="text-muted">-</span>' ?>
              </td>

              <td>
                <?php if($p['status'] == 'revisi'): ?>
                  <a href="revisi_proposal.php?id=<?= $p['id_proposal'] ?>" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit me-1"></i> Lihat Revisi
                  </a>

                <?php elseif($p['status'] == 'disetujui'): ?>
                  <?php
                    // Cek apakah ada jadwal pertemuan untuk proposal ini
                    $jadwal = $koneksi->query("
                      SELECT * FROM jadwal_pertemuan 
                      WHERE id_proposal = {$p['id_proposal']}
                      ORDER BY tanggal_ditetapkan DESC LIMIT 1
                    ")->fetch_assoc();
                  ?>

                  <?php if($jadwal): ?>
                    <a href="lihat_jadwal.php?id=<?= $jadwal['id_jadwal'] ?>" class="btn btn-success btn-sm">
                      <i class="fas fa-calendar-alt me-1"></i> Lihat Jadwal
                    </a>
                  <?php else: ?>
                    <span class="text-muted">Menunggu jadwal dari Admin...</span>
                  <?php endif; ?>

                <?php elseif($p['status'] == 'ditolak'): ?>
                  <span class="text-danger">Proposal ditolak</span>

                <?php else: ?>
                  <span class="text-muted">Menunggu verifikasi admin...</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="text-muted">Belum ada proposal yang diajukan.</p>
      <a href="form_proposal.php" class="btn btn-primary">
        <i class="fas fa-plus-circle me-1"></i> Ajukan Proposal Sekarang
      </a>
    <?php endif; ?>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
