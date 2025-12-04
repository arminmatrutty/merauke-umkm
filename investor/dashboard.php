<?php
require_once __DIR__ . '/../includes/functions.php';
require_role('investor');
require_once __DIR__ . '/../includes/header.php';

// Ambil semua proposal yang sudah disetujui admin
$proposals = $koneksi->query("
  SELECT p.*, u.nama_usaha, u.bidang_usaha, u.alamat
  FROM proposal p
  JOIN umkm_profile u ON p.id_umkm = u.id_umkm
  WHERE p.status = 'disetujui'
  ORDER BY p.tanggal_pengajuan DESC
");
?>

<style>
  .page-header {
    background: linear-gradient(135deg, #007bff, #00bcd4);
    color: white;
    border-radius: 16px;
    padding: 25px 30px;
    margin-bottom: 30px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  }
  .proposal-card {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: all 0.25s ease;
  }
  .proposal-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
  }
  .proposal-img {
    height: 200px;
    object-fit: cover;
  }
  .proposal-body {
    padding: 20px;
  }
  .proposal-title {
    font-weight: 700;
    color: #004aad;
  }
  .proposal-desc {
    color: #555;
    min-height: 60px;
  }
  .btn-invest {
    background: linear-gradient(45deg, #00bcd4, #007bff);
    border: none;
    border-radius: 10px;
    padding: 8px 18px;
    transition: transform 0.2s;
  }
  .btn-invest:hover {
    transform: translateY(-2px);
    background: linear-gradient(45deg, #009dc0, #006ce0);
  }
  .no-proposal {
    text-align: center;
    color: #888;
    padding: 80px 0;
  }
  .search-box {
    max-width: 350px;
    margin-left: auto;
  }
</style>

<div class="container mt-4 mb-5">
  <!-- Header -->
  <div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
    <div>
      <h3 class="fw-bold mb-1">Dashboard Investor</h3>
      <p class="mb-0">Temukan berbagai peluang investasi dari pelaku usaha UMKM di Merauke 💼</p>
    </div>
    <form class="search-box mt-3 mt-md-0">
      <div class="input-group">
        <input type="text" id="searchInput" class="form-control" placeholder="Cari nama usaha...">
        <span class="input-group-text bg-white"><i class="fas fa-search text-primary"></i></span>
      </div>
    </form>
  </div>

  <!-- Daftar Proposal -->
  <div class="row" id="proposalList">
    <?php if ($proposals->num_rows > 0): ?>
      <?php while ($p = $proposals->fetch_assoc()): ?>
        <div class="col-md-4 mb-4 proposal-item">
          <div class="card proposal-card h-100">
           <img src="<?= !empty($p['foto_usaha']) 
              ? '/merauke_umkm/uploads/proposal/' . htmlspecialchars($p['foto_usaha']) 
              : '/merauke_umkm/assets/img/no-image.png' ?>" 
     alt="Foto Usaha" 
     class="proposal-img w-100 rounded shadow-sm border">
            <div class="proposal-body">
              <h5 class="proposal-title mb-2"><?= htmlspecialchars($p['judul_proposal']) ?></h5>
              <p class="proposal-desc small"><?= substr(htmlspecialchars($p['deskripsi']), 0, 100) ?>...</p>
              <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                  <span class="badge bg-success">Rp <?= number_format($p['modal_dibutuhkan'], 0, ',', '.') ?></span>
                  <small class="d-block text-muted"><?= htmlspecialchars($p['estimasi_keuntungan']) ?></small>
                </div>
                <a href="detail_proposal.php?id=<?= $p['id_proposal'] ?>" class="btn btn-invest btn-sm text-white">
                  <i class="fas fa-eye me-1"></i> Lihat Detail
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12">
        <div class="no-proposal">
          <i class="fas fa-folder-open fa-3x mb-3"></i>
          <p>Tidak ada proposal yang tersedia saat ini.</p>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<script>
  // Filter pencarian dinamis
  document.getElementById('searchInput').addEventListener('keyup', function() {
    const filter = this.value.toLowerCase();
    document.querySelectorAll('.proposal-item').forEach(item => {
      const title = item.querySelector('.proposal-title').textContent.toLowerCase();
      item.style.display = title.includes(filter) ? '' : 'none';
    });
  });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
