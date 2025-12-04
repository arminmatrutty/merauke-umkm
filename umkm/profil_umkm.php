<?php
require_once __DIR__ . '/../includes/functions.php';
require_role('umkm');
require_once __DIR__ . '/../includes/header.php';

$id_user = $_SESSION['id_user'] ?? null;
if (!$id_user) {
    header('Location: ../auth/login.php');
    exit;
}

// ambil data profil jika ada
$umkm = $koneksi->query("SELECT * FROM umkm_profile WHERE id_user = $id_user")->fetch_assoc();

// form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_usaha   = $koneksi->real_escape_string($_POST['nama_usaha']);
    $bidang_usaha = $koneksi->real_escape_string($_POST['bidang_usaha']);
    $deskripsi    = $koneksi->real_escape_string($_POST['deskripsi']);
    $alamat       = $koneksi->real_escape_string($_POST['alamat']);
    $no_hp        = $koneksi->real_escape_string($_POST['no_hp']);

    // handle upload foto
    $foto_usaha = $umkm['foto_usaha'] ?? null;
    if (isset($_FILES['foto_usaha']) && $_FILES['foto_usaha']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['foto_usaha']['name'], PATHINFO_EXTENSION);
        $foto_file = uniqid('umkm_') . '.' . $ext;

        $upload_dir = __DIR__ . '/../uploads/umkm/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $upload_path = $upload_dir . $foto_file;

        if (move_uploaded_file($_FILES['foto_usaha']['tmp_name'], $upload_path)) {
            $foto_usaha = $foto_file;
        } else {
            $error = "Gagal mengunggah foto. Periksa izin folder uploads/umkm.";
        }
    }

    $tanggal_update = date('Y-m-d H:i:s');

    if ($umkm) {
        $sql = "UPDATE umkm_profile SET 
            nama_usaha='$nama_usaha',
            bidang_usaha='$bidang_usaha',
            deskripsi='$deskripsi',
            alamat='$alamat',
            no_hp='$no_hp',
            foto_usaha='$foto_usaha',
            tanggal_update='$tanggal_update'
            WHERE id_user=$id_user";
        if ($koneksi->query($sql)) {
            header('Location: dashboard.php'); // redirect ke dashboard
            exit;
        } else {
            $error = "Terjadi kesalahan: " . $koneksi->error;
        }
    } else {
        $sql = "INSERT INTO umkm_profile 
            (id_user, nama_usaha, bidang_usaha, deskripsi, alamat, no_hp, foto_usaha, tanggal_update)
            VALUES
            ($id_user, '$nama_usaha', '$bidang_usaha', '$deskripsi', '$alamat', '$no_hp', '$foto_usaha', '$tanggal_update')";
        if ($koneksi->query($sql)) {
            header('Location: dashboard.php'); // redirect ke dashboard
            exit;
        } else {
            $error = "Terjadi kesalahan: " . $koneksi->error;
        }
    }
}
?>

<style>
/* Modern card styling */
.profil-card {
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    padding: 30px;
    background: #ffffff;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.profil-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
}
.form-label {
    font-weight: 600;
}
input[type="text"], textarea {
    border-radius: 12px;
    border: 1px solid #ddd;
    padding: 12px;
    transition: 0.3s;
}
input[type="text"]:focus, textarea:focus {
    border-color: #007bff;
    box-shadow: 0 0 8px rgba(0,123,255,0.2);
}
.btn-primary {
    border-radius: 12px;
    padding: 10px 25px;
}
.foto-preview {
    max-width: 200px;
    max-height: 200px;
    margin-top: 10px;
    border-radius: 12px;
    object-fit: cover;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
</style>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="profil-card">
                <h3 class="mb-4 text-primary"><i class="fas fa-building me-2"></i>Lengkapi Profil UMKM</h3>

                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Nama Usaha</label>
                        <input type="text" name="nama_usaha" class="form-control" value="<?= htmlspecialchars($umkm['nama_usaha'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bidang Usaha</label>
                        <input type="text" name="bidang_usaha" class="form-control" value="<?= htmlspecialchars($umkm['bidang_usaha'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="4" required><?= htmlspecialchars($umkm['deskripsi'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="alamat" class="form-control" value="<?= htmlspecialchars($umkm['alamat'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. HP</label>
                        <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($umkm['no_hp'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto Usaha</label>
                        <input type="file" name="foto_usaha" class="form-control" id="fotoUsahaInput">
                        <?php if(!empty($umkm['foto_usaha'])): ?>
                            <img src="../uploads/umkm/<?= htmlspecialchars($umkm['foto_usaha']) ?>" class="foto-preview" id="fotoPreview">
                        <?php else: ?>
                            <img class="foto-preview" id="fotoPreview" style="display:none;">
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Profil</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Preview foto sebelum upload
const fotoInput = document.getElementById('fotoUsahaInput');
const fotoPreview = document.getElementById('fotoPreview');

fotoInput.addEventListener('change', function(){
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            fotoPreview.src = e.target.result;
            fotoPreview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
