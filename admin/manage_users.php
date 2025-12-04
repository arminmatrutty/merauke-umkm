<?php
require_once __DIR__ . '/../includes/functions.php';
require_role('admin');
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid mt-4">
  <h3 class="fw-bold text-primary mb-4"><i class="fas fa-users me-2"></i> Kelola Pengguna</h3>

  <ul class="nav nav-tabs mb-3" id="userTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin" type="button" role="tab">Admin</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="investor-tab" data-bs-toggle="tab" data-bs-target="#investor" type="button" role="tab">Investor</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="umkm-tab" data-bs-toggle="tab" data-bs-target="#umkm" type="button" role="tab">Pelaku UMKM</button>
    </li>
  </ul>

  <div class="tab-content" id="userTabsContent">
    <?php
    $roles = ['admin' => 'Admin', 'investor' => 'Investor', 'umkm' => 'Pelaku UMKM'];
    foreach ($roles as $roleKey => $roleLabel):
      $users = $koneksi->query("SELECT id_user, COALESCE(nama_lengkap, username) AS nama_lengkap, email, role FROM users WHERE role = '$roleKey'");
    ?>
    <div class="tab-pane fade <?= $roleKey == 'admin' ? 'show active' : '' ?>" id="<?= $roleKey ?>" role="tabpanel">
      <div class="card border-0 shadow-sm p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-semibold text-dark"><i class="fas fa-user-circle me-2"></i> Daftar <?= $roleLabel ?></h5>
          <a href="add_user.php?role=<?= $roleKey ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Tambah <?= $roleLabel ?>
          </a>
        </div>
        <div class="table-responsive">
          <table class="table table-striped align-middle">
            <thead class="table-primary">
              <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($users->num_rows > 0): $i=1; while($u = $users->fetch_assoc()): ?>
                <tr>
                  <td><?= $i++ ?></td>
                  <td><?= htmlspecialchars($u['nama_lengkap']) ?></td>
                  <td><?= htmlspecialchars($u['email'] ?? '-') ?></td>
                  <td>
                    <a href="edit_user.php?id=<?= $u['id_user'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                    <a href="delete_user.php?id=<?= $u['id_user'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus pengguna ini?')"><i class="fas fa-trash"></i></a>
                  </td>
                </tr>
              <?php endwhile; else: ?>
                <tr><td colspan="4" class="text-center text-muted">Belum ada pengguna <?= strtolower($roleLabel) ?>.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
