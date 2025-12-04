<?php
// includes/header.php
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Crowdfunding UMKM Merauke</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="/merauke_umkm/assets/css/custom.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
      font-family: 'Poppins', sans-serif;
    }
    .navbar {
      background: linear-gradient(90deg, #004aad, #007bff);
      box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    .navbar-brand {
      font-weight: 700;
      letter-spacing: 0.5px;
    }
    .nav-link {
      color: #fff !important;
      font-weight: 500;
      transition: 0.2s;
    }
    .nav-link:hover {
      color: #d1e3ff !important;
    }
    footer {
      background: linear-gradient(90deg, #004aad, #007bff);
      color: #fff;
      padding: 25px 0;
      margin-top: 50px;
    }
    footer a {
      color: #dcecff;
      text-decoration: none;
    }
    footer a:hover {
      color: #fff;
      text-decoration: underline;
    }
  </style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="/merauke_umkm/">
     <img src="/merauke_umkm/logo.png" 
     alt="Logo Kota Merauke" 
     width="42" 
     height="42" 
     class="me-2" 
     style="object-fit: contain;">

      <span>CrowdUMKM</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav ms-auto align-items-center">
        <?php if(isset($_SESSION['nama'])): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
              <i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars($_SESSION['nama']) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
              <li><a class="dropdown-item" href="/merauke_umkm/profile.php"><i class="fas fa-id-card me-2 text-primary"></i> Profil</a></li>
              <li><a class="dropdown-item" href="/merauke_umkm/logout.php"><i class="fas fa-sign-out-alt me-2 text-danger"></i> Logout</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="btn btn-light text-primary ms-2" href="/merauke_umkm/login.php">
              <i class="fas fa-right-to-bracket me-1"></i> Login
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
