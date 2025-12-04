<?php
require_once __DIR__ . '/../includes/functions.php';
require_role('admin');

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $action = $_GET['action'];

    if ($action === 'approve') {
        $koneksi->query("UPDATE proposal SET status='disetujui', tanggal_respon=NOW() WHERE id_proposal=$id");
        write_log($koneksi, $_SESSION['id_user'], "Menyetujui proposal id=$id");
        header("Location: manage_proposals.php");
        exit;
    } elseif ($action === 'reject') {
        $koneksi->query("UPDATE proposal SET status='ditolak', tanggal_respon=NOW() WHERE id_proposal=$id");
        write_log($koneksi, $_SESSION['id_user'], "Menolak proposal id=$id");
        header("Location: manage_proposals.php");
        exit;
    }
}

require_once __DIR__ . '/../includes/header.php';
$data = $koneksi->query("
  SELECT p.*, u.nama_usaha, us.nama_lengkap
  FROM proposal p
  JOIN umkm_profile u ON p.id_umkm = u.id_umkm
  JOIN users us ON u.id_user = us.id_user
  ORDER BY p.tanggal_pengajuan DESC
");
?>
<h3>Kelola Proposal UMKM</h3>
<table class="table table-bordered align-middle mt-3">
  <thead class="table-light">
    <tr><th>#</th><th>UMKM</th><th>Judul</th><th>Status</th><th>Aksi</th></tr>
  </thead>
  <tbody>
  <?php $no=1; while($p=$data->fetch_assoc()): ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= htmlspecialchars($p['nama_usaha']) ?><br><small><?= htmlspecialchars($p['nama_lengkap']) ?></small></td>
      <td><?= htmlspecialchars($p['judul_proposal']) ?></td>
      <td>
        <span class="badge bg-<?= $p['status']=='disetujui'?'success':($p['status']=='ditolak'?'danger':'warning') ?>">
          <?= ucfirst($p['status']) ?>
        </span>
      </td>
      <td>
        <a href="view_proposal.php?id=<?= $p['id_proposal'] ?>" class="btn btn-info btn-sm">Lihat</a>
        <?php if($p['status']=='menunggu'): ?>
          <a href="?action=approve&id=<?= $p['id_proposal'] ?>" class="btn btn-success btn-sm">Setujui</a>
          <a href="?action=reject&id=<?= $p['id_proposal'] ?>" class="btn btn-danger btn-sm">Tolak</a>
        <?php endif; ?>
      </td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
