<?php
require_once __DIR__ . '/../includes/functions.php';
require_role('admin');

// Aksi untuk menetapkan jadwal
if (isset($_GET['action']) && $_GET['action'] == 'set' && isset($_GET['id'])) {
  $id = (int) $_GET['id'];

  // Update jadwal: tetapkan dan catat waktu penetapan
  $koneksi->query("UPDATE jadwal_investasi 
                   SET status='ditetapkan', tanggal_ditetapkan=NOW() 
                   WHERE id_jadwal=$id");

  write_log($koneksi, $_SESSION['id_user'], "Menetapkan jadwal investasi id=$id");

  // Ambil detail jadwal + proposal + UMKM
  $res = $koneksi->query("
    SELECT 
      j.id_proposal, 
      j.tanggal, 
      j.waktu, 
      j.tempat, 
      j.keterangan,
      p.judul_proposal, 
      up.id_user 
    FROM jadwal_investasi j
    JOIN proposal p ON j.id_proposal = p.id_proposal
    JOIN umkm_profile up ON p.id_umkm = up.id_umkm
    WHERE j.id_jadwal = $id
  ");
  $data = $res->fetch_assoc();

  if ($data) {
    // Format tanggal dan waktu agar lebih rapi
    $tanggal = date('d M Y', strtotime($data['tanggal']));
    $waktu = $data['waktu'];
    $tempat = $data['tempat'];
    $keterangan = $data['keterangan'] ?: '-';

    // Buat pesan lengkap
    $pesan = "Proposal '{$data['judul_proposal']}' telah disetujui dan dijadwalkan pertemuan oleh admin.\n\n" .
             "📅 *Tanggal*: {$tanggal}\n" .
             "🕒 *Waktu*: {$waktu}\n" .
             "📍 *Tempat*: {$tempat}\n" .
             "📝 *Keterangan*: {$keterangan}";

    // Kirim notifikasi ke pemilik UMKM
    create_notification(
      $koneksi,
      $data['id_user'],
      "Jadwal Investasi Ditetapkan",
      $pesan
    );
  }

  header("Location: manage_meetings.php");
  exit;
}

// Ambil semua jadwal investasi
require_once __DIR__ . '/../includes/header.php';
$q = $koneksi->query("
  SELECT j.*, p.judul_proposal, i.perusahaan
  FROM jadwal_investasi j
  JOIN proposal p ON j.id_proposal=p.id_proposal
  JOIN investor_profile i ON j.id_investor=i.id_investor
  ORDER BY j.tanggal DESC
");
?>
<h3>Kelola Jadwal Investasi</h3>
<table class="table table-bordered mt-3">
  <thead class="table-light">
    <tr>
      <th>#</th>
      <th>Proposal</th>
      <th>Investor</th>
      <th>Tanggal</th>
      <th>Waktu</th>
      <th>Status</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
  <?php if ($q->num_rows > 0): $no=1; while($r = $q->fetch_assoc()): ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= htmlspecialchars($r['judul_proposal']) ?></td>
      <td><?= htmlspecialchars($r['perusahaan']) ?></td>
      <td><?= date('d M Y', strtotime($r['tanggal'])) ?></td>
      <td><?= htmlspecialchars($r['waktu']) ?></td>
      <td>
        <span class="badge bg-<?= $r['status'] == 'ditetapkan' ? 'success' : 'warning' ?>">
          <?= ucfirst($r['status']) ?>
        </span>
      </td>
      <td>
        <?php if ($r['status'] == 'menunggu konfirmasi admin'): ?>
          <a href="?action=set&id=<?= $r['id_jadwal'] ?>" 
             class="btn btn-success btn-sm"
             onclick="return confirm('Setujui dan tetapkan jadwal ini?')">Tetapkan</a>
        <?php else: ?>
          <span class="text-muted">Sudah Ditetapkan</span>
        <?php endif; ?>
      </td>
    </tr>
  <?php endwhile; else: ?>
    <tr><td colspan="7" class="text-center text-muted">Tidak ada jadwal yang tersedia.</td></tr>
  <?php endif; ?>
  </tbody>
</table>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
