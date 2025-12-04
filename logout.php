<?php
session_start();

// Simpan nama user sebelum logout
$nama = $_SESSION['nama'] ?? 'User';

// Hapus session
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Logout...</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
    body {
        background: rgba(0,0,0,0.65);
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: 'Poppins', sans-serif;
    }

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

    @keyframes fadeIn {
        from { opacity: 0; }
        to   { opacity: 1; }
    }

    .popup-box {
        background: white;
        padding: 35px 28px;
        border-radius: 22px;
        width: 350px;
        text-align: center;
        animation: popUp 0.4s ease;
    }

    @keyframes popUp {
        from { transform: scale(0.7); opacity: 0; }
        to   { transform: scale(1); opacity: 1; }
    }

    .popup-box h4 {
        color: #dc3545;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .popup-box p {
        color: #333;
        font-size: 15px;
    }
</style>
</head>

<body>

<!-- POPUP LOGOUT -->
<div class="popup-bg">
    <div class="popup-box">
        <h4>✔ Logout Berhasil</h4>
        <p>Sampai jumpa kembali, <b><?= htmlspecialchars($nama) ?></b>!</p>
        <p style="font-size:13px;color:#777;">Mengalihkan ke halaman login...</p>
    </div>
</div>

<!-- Auto Redirect -->
<script>
    setTimeout(() => {
        window.location.href = "login.php";
    }, 1500);
</script>

</body>
</html>
