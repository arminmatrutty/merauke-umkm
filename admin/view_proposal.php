<?php
// admin/view_proposal.php
require_once __DIR__ . '/../includes/functions.php';
require_role('admin');

$id = (int)($_GET['id'] ?? 0);
$res = $koneksi->query("SELECT p.*, u.nama_usaha, us.nama_lengkap FROM proposal p
                        JOIN umkm_profile u ON p.id_umkm = u.id_umkm
                        JOIN users us ON u.id_user = us.id_user
                        WHERE p.id_proposal=$id");
$row = $res->fetch_assoc();
require_once __DIR__ . '/../includes/header.php';
if (!$row) {
    echo "<div class='alert alert-warning'>Proposal tidak ditemukan.</div>";
} else {
    ?>
    <h3><?= htmlspecialchars($row['judul_proposal']) ?></h3>
    <p><strong>UMKM:</strong> <?= htmlspecialchars($row['nama_usaha']) ?> (<?= htmlspecialchars($row['nama_lengkap']) ?>)</p>
    <p><strong>Deskripsi:</strong><br><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></p>
    <p><strong>Dana yang dibutuhkan:</strong> Rp <?= number_format($row['dana_dibutuhkan'],0,',','.') ?></p>
    <?php if ($row['file_proposal']): ?>
      <p><strong>File proposal:</strong> <a target="_blank" href="/crowdfunding_umkm/<?= $row['file_proposal'] ?>">Download</a></p>
    <?php endif; ?>
    <p><strong>Status:</strong> <?= $row['status'] ?></p>
    <?php
}
require_once __DIR__ . '/../includes/footer.php';
