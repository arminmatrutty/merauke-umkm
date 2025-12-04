<?php
session_start();
if (isset($_SESSION['role'])) {
  // redirect jika sudah login
  if ($_SESSION['role'] === 'admin') header("Location: admin/dashboard.php");
  elseif ($_SESSION['role'] === 'umkm') header("Location: umkm/dashboard.php");
  else header("Location: investor/dashboard.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crowdfunding UMKM Merauke</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      color: #fff;
      background: linear-gradient(120deg, rgba(0,0,0,0.7), rgba(0,0,0,0.5)), 
                  url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1600&q=80') no-repeat center center/cover;
      min-height: 100vh;
      overflow-x: hidden;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 25px 60px;
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 10;
      background: rgba(0, 0, 0, 0.3);
      backdrop-filter: blur(6px);
    }

    header img {
      height: 60px;
    }

    .hero {
      text-align: center;
      padding-top: 160px;
      padding-bottom: 120px;
      color: #fff;
    }

    .hero h1 {
      font-size: 3rem;
      font-weight: 700;
      margin-bottom: 20px;
      color: #ffc107;
      text-shadow: 0 4px 10px rgba(0,0,0,0.4);
    }

    .hero p {
      font-size: 1.1rem;
      max-width: 700px;
      margin: 0 auto 40px;
      color: #e0e0e0;
    }

    .btn-login {
      background: #ffc107;
      border: none;
      color: #000;
      font-weight: 600;
      border-radius: 30px;
      padding: 12px 35px;
      font-size: 1.1rem;
      transition: 0.3s ease;
    }

    .btn-login:hover {
      background: #e0a800;
      transform: scale(1.08);
    }

    .features {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 25px;
      margin: 80px auto;
      max-width: 1000px;
    }

    .card-feature {
      background: rgba(255,255,255,0.1);
      border-radius: 20px;
      padding: 25px;
      width: 280px;
      text-align: center;
      color: #fff;
      backdrop-filter: blur(6px);
      transition: 0.3s;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    .card-feature:hover {
      transform: translateY(-5px);
      background: rgba(255,255,255,0.2);
    }

    .card-feature i {
      font-size: 2rem;
      color: #ffc107;
      margin-bottom: 15px;
    }

    footer {
      text-align: center;
      padding: 30px 0;
      color: #bbb;
      font-size: 0.9rem;
      background: rgba(0,0,0,0.3);
    }

    @media (max-width: 768px) {
      .hero h1 {
        font-size: 2.2rem;
      }
      header {
        padding: 15px 25px;
      }
    }
  </style>
  <script src="https://kit.fontawesome.com/a2d04b6f63.js" crossorigin="anonymous"></script>
</head>
<body>

  <header>
      <a class="navbar-brand d-flex align-items-center" href="/merauke_umkm/">
     <img src="/merauke_umkm/logo.png" 
     alt="Logo Kota Merauke" 
     width="42" 
     height="42" 
     class="me-2" 
     style="object-fit: contain;">
    <a></a></a>
  </header>

  <section class="hero">
    <h1>Sistem Crowdfunding UMKM Merauke</h1>
    <p>Platform digital yang membantu pelaku usaha mikro, kecil, dan menengah di Kabupaten Merauke dalam memperoleh dukungan pendanaan, kolaborasi, dan pengembangan bisnis secara transparan dan berkelanjutan.</p>
    <a href="login.php" class="btn btn-login">Mulai Sekarang</a>
  </section>

  <section class="features">
    <div class="card-feature">
      <i class="fas fa-hand-holding-usd"></i>
      <h5>Dukungan Finansial</h5>
      <p>Investasi mudah dan cepat untuk mendukung usaha UMKM Merauke berkembang.</p>
    </div>

    <div class="card-feature">
      <i class="fas fa-users"></i>
      <h5>Kolaborasi UMKM</h5>
      <p>Jalin kerja sama antar pelaku usaha untuk memperluas jaringan bisnis lokal.</p>
    </div>

    <div class="card-feature">
      <i class="fas fa-chart-line"></i>
      <h5>Transparansi & Laporan</h5>
      <p>Pantau perkembangan proyek dan pendanaan secara real time.</p>
    </div>
  </section>

  <footer>
    <p class="mb-1">&copy; <?= date('Y') ?> <strong>Crowdfunding UMKM Merauke</strong>. All Rights Reserved.</p>
  </footer>

</body>
</html>
