<?php
require_once 'config/database.php';
$msg = '';
$email = isset($_GET['email']) ? $_GET['email'] : '';

if (!$email) {
  header("Location: forgot_password.php");
  exit;
}

if (isset($_POST['reset'])) {
  $password = md5($_POST['password']);
  $confirm = md5($_POST['confirm']);

  if ($password !== $confirm) {
    $msg = "❌ Kata sandi tidak cocok, silakan coba lagi.";
  } else {
    $stmt = $koneksi->prepare("UPDATE users SET password=? WHERE email=?");
    $stmt->bind_param("ss", $password, $email);
    if ($stmt->execute()) {
      $msg = "✅ Kata sandi berhasil diubah. Anda akan diarahkan ke halaman login...";
      echo "<meta http-equiv='refresh' content='2;url=login.php'>";
    } else {
      $msg = "❌ Gagal mengubah kata sandi. Silakan coba lagi.";
    }
    $stmt->close();
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Atur Ulang Kata Sandi | Crowdfunding UMKM Merauke</title>
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

    .reset-box {
      background: rgba(0, 0, 0, 0.7);
      padding: 40px 35px;
      border-radius: 20px;
      box-shadow: 0 15px 40px rgba(0,0,0,0.4);
      width: 100%;
      max-width: 430px;
      color: #fff;
    }

    .reset-box h3 {
      font-weight: 600;
      color: #ffc107;
      text-align: center;
      margin-bottom: 10px;
    }

    .reset-box p {
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
  <div class="reset-box">
    <h3>Atur Ulang Kata Sandi</h3>
    <p>Masukkan kata sandi baru Anda di bawah ini.</p>

    <?php if($msg): ?>
      <div class="alert alert-warning text-center py-2"><?= $msg ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="mb-3">
        <div class="input-group">
          <span class="input-group-text bg-transparent border-0 text-white"><i class="bi bi-lock-fill"></i></span>
          <input type="password" name="password" class="form-control" placeholder="Kata Sandi Baru" required>
        </div>
      </div>
      <div class="mb-4">
        <div class="input-group">
          <span class="input-group-text bg-transparent border-0 text-white"><i class="bi bi-lock"></i></span>
          <input type="password" name="confirm" class="form-control" placeholder="Konfirmasi Kata Sandi" required>
        </div>
      </div>
      <button name="reset" class="btn-gradient w-100">Simpan Perubahan</button>
    </form>

    <p class="text-center mt-3"><a href="login.php">← Kembali ke Login</a></p>
  </div>
</body>
</html>
