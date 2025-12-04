<?php
session_start();
require_once 'config/database.php';
$error = '';

if (isset($_POST['login'])) {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  $stmt = $koneksi->prepare("SELECT id_user, nama_lengkap, password, role, status FROM users WHERE username=? LIMIT 1");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $res = $stmt->get_result();
  $user = $res->fetch_assoc();
  $stmt->close();

  if ($user && $user['status'] === 'aktif' && md5($password) === $user['password']) {

    // SET SESSION
    $_SESSION['id_user'] = $user['id_user'];
    $_SESSION['nama']    = $user['nama_lengkap'];
    $_SESSION['role']    = $user['role'];

    // Popup sukses
    $success_popup = true;

    // Redirect otomatis setelah popup
    if ($user['role'] === 'admin') {
      $redirectURL = "admin/dashboard.php";
    } elseif ($user['role'] === 'umkm') {
      $redirectURL = "umkm/dashboard.php";
    } else {
      $redirectURL = "investor/dashboard.php";
    }

  } else {
    $error = "❌ Username atau password salah, atau akun belum aktif.";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | Crowdfunding UMKM Merauke</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      background: url('https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1600&q=80') 
                  no-repeat center center/cover;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Poppins', sans-serif;
      color: #fff;
    }

    .glass-box {
      background: rgba(0, 0, 0, 0.55);
      border: 1px solid rgba(255,255,255,0.2);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      padding: 40px 35px;
      max-width: 420px;
      width: 100%;
      color: white;
      box-shadow: 0px 8px 25px rgba(0,0,0,0.4);
      text-align: center;
      animation: fadeIn 0.8s ease;
      z-index: 2;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(15px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Input */
    .form-control {
      border-radius: 10px;
      border: none;
      outline: none;
      padding: 12px 15px;
      margin-bottom: 15px;
      background: rgba(255,255,255,0.15);
      color: #fff;
    }
    .form-control:focus {
      background: rgba(255,255,255,0.25);
      color: #fff;
    }

    /* Tombol tetap solid */
    .btn-login {
      background-color: #ffc107 !important;
      border: none;
      color: #000;
      font-weight: 600;
      border-radius: 10px;
      padding: 12px;
      width: 100%;
      transition: 0.3s ease-in-out;
    }
    .btn-login:hover {
      background-color: #e0a800 !important;
      transform: scale(1.03);
    }

    /* POPUP SUCCESS */
    .popup-bg {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.55);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 999;
      animation: fadeIn 0.3s ease;
    }

    .popup-box {
      background: white;
      padding: 35px 30px;
      border-radius: 20px;
      width: 350px;
      text-align: center;
      animation: popUp 0.4s ease;
    }

    @keyframes popUp {
      from { transform: scale(0.7); opacity: 0; }
      to   { transform: scale(1); opacity: 1; }
    }

    .popup-box h4 {
      color: #28a745;
      font-weight: 700;
      margin-bottom: 10px;
    }

    .popup-box p {
      color: #222;
      font-size: 15px;
    }

  </style>
</head>

<body>

  <?php if (!empty($success_popup)) : ?>
    <!-- POPUP BERHASIL LOGIN -->
    <div class="popup-bg">
      <div class="popup-box">
        <h4>✔ Login Berhasil</h4>
        <p>Selamat datang kembali, <?= $_SESSION['nama']; ?>!</p>
      </div>
    </div>

    <!-- Auto redirect -->
    <script>
      setTimeout(() => {
        window.location.href = "<?= $redirectURL ?>";
      }, 1500);
    </script>
  <?php endif; ?>


  <div class="glass-box shadow-lg">

    <h3>Sistem Crowdfunding UMKM Merauke</h3>
    <p class="mb-3" style="font-size:14px;color:#e0e0e0;">Silakan masuk menggunakan akun Anda</p>

    <?php if($error): ?>
      <div class="alert alert-danger mb-3" style="border-radius:10px;">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="post">
      <input type="text" class="form-control" name="username" placeholder="Username" required>
      <input type="password" class="form-control" name="password" placeholder="Kata Sandi" required>
      <button type="submit" name="login" class="btn btn-login mt-2">Masuk</button>
    </form>

    <div class="links mt-4">
      <a href="forgot_password.php" style="color:#ffc107;">Lupa Kata Sandi?</a> |
      <a href="register.php" style="color:#ffc107;">Daftar Akun Baru</a>
    </div>

    <p class="mt-4">
      <a href="index.php" style="color:#e9ecef;text-decoration:none;">← Kembali ke Beranda</a>
    </p>

  </div>

</body>
</html>
