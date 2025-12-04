<?php
require_once __DIR__ . '/../includes/functions.php';
require_role('admin');
require_once __DIR__ . '/../includes/header.php';

// Hitung statistik dasar
$total_umkm = $koneksi->query("SELECT COUNT(*) AS jml FROM umkm_profile")->fetch_assoc()['jml'];
$total_investor = $koneksi->query("SELECT COUNT(*) AS jml FROM investor_profile")->fetch_assoc()['jml'];
$total_proposal = $koneksi->query("SELECT COUNT(*) AS jml FROM proposal")->fetch_assoc()['jml'];
$pending_proposal = $koneksi->query("SELECT COUNT(*) AS jml FROM proposal WHERE status='menunggu'")->fetch_assoc()['jml'];
$jadwal_pending = $koneksi->query("SELECT COUNT(*) AS jml FROM jadwal_investasi WHERE status='menunggu konfirmasi admin'")->fetch_assoc()['jml'];
?>

<style>
  .dashboard-header {
    background: linear-gradient(135deg, #007bff, #00bcd4);
    color: white;
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 30px;
  }
  .stat-card {
    border-radius: 15px;
    border: none;
    padding: 25px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    transition: transform 0.2s;
  }
  .stat-card:hover {
    transform: translateY(-4px);
  }
  .stat-icon {
    font-size: 30px;
    color: white;
    padding: 15px;
    border-radius: 12px;
    margin-bottom: 10px;
  }
  .bg-umkm { background: linear-gradient(45deg, #007bff, #00bcd4); }
  .bg-investor { background: linear-gradient(45deg, #00b09b, #96c93d); }
  .bg-proposal { background: linear-gradient(45deg, #f39c12, #f1c40f); }
  .bg-pending { background: linear-gradient(45deg, #e74c3c, #ff7675); }
  .section-card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    margin-bottom: 30px;
  }
  .table thead {
    background: #007bff;
    color: white;
  }
  .btn-action {
    border-radius: 8px;
    padding: 5px 10px;
  }
</style>

<div class="container mt-4 mb-5">
  <div class="dashboard-header">
    <h3 class="fw-bold mb-2">Dashboard Admin</h3>
    <p>Kelola seluruh aktivitas, proposal, dan pengguna di platform Crowdfunding UMKM Merauke 👨‍💼</p>
  </div>

  <!-- Statistik -->
  <div class="row text-center mb-4">
    <div class="col-md-3 mb-3">
      <div class="card stat-card">
        <div class="stat-icon bg-umkm"><i class="fas fa-store"></i></div>
        <h6>Pelaku UMKM</h6>
        <h4 class="fw-bold"><?= $total_umkm ?></h4>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card stat-card">
        <div class="stat-icon bg-investor"><i class="fas fa-hand-holding-usd"></i></div>
        <h6>Investor</h6>
        <h4 class="fw-bold"><?= $total_investor ?></h4>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card stat-card">
        <div class="stat-icon bg-proposal"><i class="fas fa-file-alt"></i></div>
        <h6>Total Proposal</h6>
        <h4 class="fw-bold"><?= $total_proposal ?></h4>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card stat-card">
        <div class="stat-icon bg-pending"><i class="fas fa-clock"></i></div>
        <h6>Proposal Menunggu</h6>
        <h4 class="fw-bold"><?= $pending_proposal ?></h4>
      </div>
    </div>
  </div>

  <!-- Proposal Baru -->
  <div class="card section-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="fw-bold text-primary"><i class="fas fa-file-signature me-2"></i> Proposal Baru</h5>
      <a href="manage_proposal.php" class="btn btn-sm btn-primary">Lihat Semua</a>
    </div>

    <?php
    $proposals = $koneksi->query("
      SELECT p.*, u.nama_usaha 
      FROM proposal p
      JOIN umkm_profile u ON p.id_umkm = u.id_umkm
      WHERE p.status='menunggu'
      ORDER BY p.tanggal_pengajuan DESC LIMIT 5
    ");
    ?>

    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Usaha</th>
            <th>Judul Proposal</th>
            <th>Modal</th>
            <th>Tanggal</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($proposals->num_rows > 0): $no=1; while($p = $proposals->fetch_assoc()): ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($p['nama_usaha']) ?></td>
              <td><?= htmlspecialchars($p['judul_proposal']) ?></td>
              <td>Rp <?= number_format($p['modal_dibutuhkan'],0,',','.') ?></td>
              <td><?= date('d M Y', strtotime($p['tanggal_pengajuan'])) ?></td>
              <td>
                <a href="review_proposal.php?id=<?= $p['id_proposal'] ?>" class="btn btn-sm btn-success btn-action">
                  <i class="fas fa-eye me-1"></i> Review
                </a>
              </td>
            </tr>
          <?php endwhile; else: ?>
            <tr><td colspan="6" class="text-center text-muted">Tidak ada proposal baru.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Jadwal Pending -->
  <div class="card section-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="fw-bold text-primary"><i class="fas fa-calendar-check me-2"></i> Jadwal Menunggu Konfirmasi</h5>
      <a href="manage_meetings.php" class="btn btn-sm btn-primary">Lihat Semua</a>
    </div>

    <?php
    $jadwal = $koneksi->query("
      SELECT j.*, i.perusahaan, p.judul_proposal
      FROM jadwal_investasi j
      JOIN investor_profile i ON j.id_investor = i.id_investor
      JOIN proposal p ON j.id_proposal = p.id_proposal
      WHERE j.status='menunggu konfirmasi admin'
      ORDER BY j.tanggal DESC LIMIT 5
    ");
    ?>

    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Investor</th>
            <th>Proposal</th>
            <th>Tanggal</th>
            <th>Waktu</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($jadwal->num_rows > 0): $n=1; while($j = $jadwal->fetch_assoc()): ?>
            <tr>
              <td><?= $n++ ?></td>
              <td><?= htmlspecialchars($j['perusahaan']) ?></td>
              <td><?= htmlspecialchars($j['judul_proposal']) ?></td>
              <td><?= date('d M Y', strtotime($j['tanggal'])) ?></td>
              <td><?= htmlspecialchars($j['waktu']) ?></td>
              <td>
                <a href="verify_schedule.php?id=<?= $j['id_jadwal'] ?>" class="btn btn-sm btn-success btn-action">
                  <i class="fas fa-check me-1"></i> Tetapkan
                </a>
              </td>
            </tr>
          <?php endwhile; else: ?>
            <tr><td colspan="6" class="text-center text-muted">Tidak ada jadwal baru.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Kelola User -->
  <div class="card section-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="fw-bold text-primary"><i class="fas fa-users-cog me-2"></i> Kelola Pengguna</h5>
      <a href="manage_users.php" class="btn btn-sm btn-primary">Lihat Semua</a>
    </div>

    <?php
    $users = $koneksi->query("SELECT id_user, nama_lengkap, email, role FROM users ORDER BY id_user DESC LIMIT 5");
    ?>
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($users->num_rows > 0): $i=1; while($u = $users->fetch_assoc()): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= htmlspecialchars($u['nama_lengkap']) ?></td>
              <td><?= htmlspecialchars($u['email']) ?></td>
              <td><span class="badge bg-secondary"><?= ucfirst($u['role']) ?></span></td>
            </tr>
          <?php endwhile; else: ?>
            <tr><td colspan="4" class="text-center text-muted">Belum ada pengguna.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
