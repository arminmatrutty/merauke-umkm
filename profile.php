<?php
require_once __DIR__ . '/includes/functions.php';
if (session_status() == PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/includes/header.php';

// pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
  header("Location: login.php");
  exit;
}

$id_user = $_SESSION['id_user'];
$role = $_SESSION['role'];

// ambil data profil sesuai role
switch ($role) {
  case 'admin':
    $data = ['role' => 'Admin', 'nama' => $_SESSION['nama'], 'email' => $_SESSION['email'] ?? '-', 'telepon' => '-', 'alamat' => '-'];
    break;
  case 'umkm':
    $q = $koneksi->query("SELECT * FROM umkm_profile WHERE id_user = $id_user");
    $data = $q->fetch_assoc();
    break;
  case 'investor':
    $q = $koneksi->query("SELECT * FROM investor_profile WHERE id_user = $id_user");
    $data = $q->fetch_assoc();
    break;
  default:
    $data = null;
}

if (!$data) {
  echo "<div class='alert alert-danger m-4'>Data profil tidak ditemukan.</div>";
  require_once __DIR__ . '/includes/footer.php';
  exit;
}
?>

<div class="container mt-4 mb-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-primary text-white py-3">
          <h4 class="mb-0 fw-bold"><i class="fas fa-user-circle me-2"></i> Profil Pengguna</h4>
        </div>
        <div class="card-body p-4">
          <div class="text-center mb-4">
            <img src="<?= !empty($data['foto_profil']) ? '/merauke_umkm/uploads/profil/'.$data['foto_profil'] : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png' ?>" 
                 alt="Foto Profil" class="rounded-circle shadow" width="120" height="120">
            <h5 class="mt-3 fw-semibold text-primary">
              <?= htmlspecialchars($_SESSION['nama']) ?>
            </h5>
            <span class="badge bg-info text-dark"><?= strtoupper($role) ?></span>
          </div>

          <table class="table table-borderless">
            <?php if ($role != 'admin'): ?>
              <tr>
                <th width="35%">Nama Lengkap</th>
                <td><?= htmlspecialchars($_SESSION['nama']) ?></td>
              </tr>
              <?php if($role == 'umkm'): ?>
                <tr><th>Nama Usaha</th><td><?= htmlspecialchars($data['nama_usaha'] ?? '-') ?></td></tr>
                <tr><th>Alamat</th><td><?= htmlspecialchars($data['alamat'] ?? '-') ?></td></tr>
                <tr><th>No. HP</th><td><?= htmlspecialchars($data['no_hp'] ?? '-') ?></td></tr>
                <tr><th>Tanggal Update</th><td><?= htmlspecialchars($data['tanggal_update'] ?? '-') ?></td></tr>
              <?php elseif($role == 'investor'): ?>
                <tr><th>Perusahaan</th><td><?= htmlspecialchars($data['perusahaan'] ?? '-') ?></td></tr>
                <tr><th>Jabatan</th><td><?= htmlspecialchars($data['jabatan'] ?? '-') ?></td></tr>
                <tr><th>Alamat</th><td><?= htmlspecialchars($data['alamat'] ?? '-') ?></td></tr>
                <tr><th>No. HP</th><td><?= htmlspecialchars($data['no_hp'] ?? '-') ?></td></tr>
              <?php endif; ?>
            <?php else: ?>
              <tr><th>Nama Admin</th><td><?= htmlspecialchars($data['nama']) ?></td></tr>
              <tr><th>Email</th><td><?= htmlspecialchars($data['email']) ?></td></tr>
              <tr><th>No. Telepon</th><td>-</td></tr>
            <?php endif; ?>
          </table>

          <div class="text-end mt-4">
            <a href="/merauke_umkm/" class="btn btn-outline-secondary">
              <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <a href="/merauke_umkm/edit_profile.php" class="btn btn-primary">
              <i class="fas fa-pen me-1"></i> Edit Profil
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
