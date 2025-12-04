<?php
require_once 'config/database.php';
$msg = '';

if (isset($_POST['forgot'])) {
  $email = $_POST['email'];

  $cek = $koneksi->prepare("SELECT * FROM users WHERE email=?");
  $cek->bind_param("s", $email);
  $cek->execute();
  $result = $cek->get_result();

  if ($result->num_rows > 0) {
    header("Location: reset_password.php?email=" . urlencode($email));
    exit;
  } else {
    $msg = "❌ Email tidak ditemukan. Pastikan email sudah terdaftar.";
  }

  $cek->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Lupa Kata Sandi | Crowdfunding UMKM Merauke</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

    body {
      font-family: 'Poppins', sans-serif;
      background: url('https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=1600&q=80') no-repeat center center/cover;
      backdrop-filter: blur(6px);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .forgot-box {
      background: rgba(0, 0, 0, 0.7);
      padding: 40px 35px;
      border-radius: 20px;
      box-shadow: 0 15px 40px rgba(0,0,0,0.4);
      width: 100%;
      max-width: 430px;
      color: #fff;
    }

    .forgot-box h3 {
      font-weight: 600;
      color: #ffc107;
      text-align: center;
      margin-bottom: 10px;
    }

    .forgot-box p {
      text-align: center;
      color: #e0e0e0;
      font-size: 14px;
      margin-bottom: 25px;
    }

    .form-control {
      background: rgba(255,255,255,0.15);
      color: #fff;
      border: none;
      border-radius: 10px;
    }

    .form-control::placeholder { color: #ccc; }
    .form-control:focus {
      background: rgba(255,255,255,0.25);
      box-shadow: 0 0 10px rgba(255,255,255,0.3);
      color: #fff;
    }

    .btn-gradient {
      background: #ffc107;
      color: #000;
      font-weight: 600;
      border: none;
      border-radius: 10px;
      padding: 10px;
      transition: all 0.3s ease;
    }

    .btn-gradient:hover {
      background: #ffca2c;
      transform: scale(1.02);
    }

    a {
      color: #ffc107;
      text-decoration: none;
    }

    a:hover { text-decoration: underline; }

    .alert {
      background: rgba(255,255,255,0.1);
      color: #fff;
      border: none;
    }
  </style>
</head>
<body>
  <div class="forgot-box">
    <h3>Lupa Kata Sandi</h3>
    <p>Masukkan email akun Anda untuk mengatur ulang kata sandi.</p>

    <?php if($msg): ?>
      <div class="alert alert-warning text-center py-2"><?= $msg ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="mb-4">
        <div class="input-group">
          <span class="input-group-text bg-transparent border-0 text-white"><i class="bi bi-envelope-fill"></i></span>
          <input type="email" name="email" class="form-control" placeholder="Masukkan Email Anda" required>
        </div>
      </div>
      <button name="forgot" class="btn-gradient w-100">Lanjutkan</button>
    </form>

    <p class="text-center mt-3">Sudah ingat kata sandi? <a href="login.php">Login</a></p>
    <p class="text-center mb-0"><a href="index.php">← Kembali ke Beranda</a></p>
  </div>
</body>
</html>
