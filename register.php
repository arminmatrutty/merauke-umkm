<?php
require_once 'config/database.php';
$msg = '';

if (isset($_POST['register'])) {
  $nama = $_POST['nama'];
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = md5($_POST['password']);
  $role = $_POST['role'];

  $cek = $koneksi->prepare("SELECT id_user FROM users WHERE username=? OR email=?");
  $cek->bind_param("ss", $username, $email);
  $cek->execute();
  $cek->store_result();

  if ($cek->num_rows > 0) {
    $msg = "❌ Username atau Email sudah digunakan.";
  } else {
    $stmt = $koneksi->prepare("INSERT INTO users (nama_lengkap, username, email, password, role, status) VALUES (?, ?, ?, ?, ?, 'aktif')");
    $stmt->bind_param("sssss", $nama, $username, $email, $password, $role);
    if ($stmt->execute()) {
      $msg = "✅ Akun berhasil dibuat. Silakan login.";
    } else {
      $msg = "❌ Gagal membuat akun.";
    }
    $stmt->close();
  }
  $cek->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Akun | Crowdfunding UMKM Merauke</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: url('https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=1350&q=80') no-repeat center center/cover;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .register-container {
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      padding: 40px;
      width: 400px;
      color: #fff;
      text-align: center;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
    }

    .register-container h2 {
      color: #ffc107;
      margin-bottom: 10px;
      font-size: 22px;
    }

    .register-container p {
      color: #f0f0f0;
      font-size: 14px;
      margin-bottom: 25px;
    }

    .register-container input,
    .register-container select {
      width: 100%;
      padding: 12px 15px;
      margin-bottom: 15px;
      border-radius: 10px;
      border: none;
      background: rgba(255, 255, 255, 0.9);
      color: #000;
      font-size: 14px;
    }

    .register-container input::placeholder {
      color: #555;
    }

    .register-container input:focus,
    .register-container select:focus {
      outline: none;
      box-shadow: 0 0 0 2px #ffc107;
    }

    .register-container button {
      width: 100%;
      padding: 12px;
      background: #ffc107;
      border: none;
      border-radius: 10px;
      font-weight: bold;
      color: #000;
      cursor: pointer;
      transition: 0.3s;
      font-size: 15px;
    }

    .register-container button:hover {
      background: #ffca2c;
    }

    .register-container .links {
      margin-top: 15px;
      font-size: 13px;
    }

    .register-container .links a {
      color: #ffc107;
      text-decoration: none;
    }

    .register-container .links a:hover {
      text-decoration: underline;
    }

    .back-home {
      display: block;
      margin-top: 20px;
      font-size: 13px;
      color: #ccc;
      text-decoration: none;
    }

    .back-home:hover {
      color: #ffc107;
    }

    .alert {
      background: rgba(255, 255, 255, 0.9);
      color: #000;
      border-radius: 10px;
      padding: 10px;
      margin-bottom: 15px;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="register-container">
    <h2>Sistem Crowdfunding<br>UMKM Merauke</h2>
    <p>Silakan isi form untuk membuat akun baru</p>

    <?php if($msg): ?>
      <div class="alert"><?= $msg ?></div>
    <?php endif; ?>

    <form method="post">
      <input type="text" name="nama" placeholder="Nama Lengkap" required>
      <input type="text" name="username" placeholder="Username" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Kata Sandi" required>

      <select name="role" required>
        <option value="">Pilih Peran Pengguna</option>
        <option value="umkm">Pelaku UMKM</option>
        <option value="investor">Investor</option>
      </select>

      <button name="register">Daftar Sekarang</button>
    </form>

    <div class="links">
      Sudah punya akun? <a href="login.php">Login</a>
    </div>

    <a href="index.php" class="back-home">← Kembali ke Beranda</a>
  </div>
</body>
</html>
