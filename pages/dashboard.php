<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

include '../config/db.php';

$guru = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM guru"));
$kriteria = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM kriteria"));
$penilaian = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM penilaian"));
function getTopCPI($conn, $limit = 5) {
    $sql = "SELECT g.nama, SUM(k.bobot * p.nilai) AS cpi 
            FROM penilaian p 
            JOIN guru g ON p.id_guru = g.id 
            JOIN kriteria k ON p.id_kriteria = k.id 
            GROUP BY g.id 
            ORDER BY cpi DESC 
            LIMIT $limit";
    return mysqli_fetch_all(mysqli_query($conn, $sql), MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Sistem Penilaian Guru</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-image: #f8f9fa;
    }
    .card {
      margin-bottom: 20px;
      border-radius: 15px;
      height: 320px;
    }
    .card-header {
      background-color: #00695c;
      color: white;
      font-weight: bold;
    }
    .header-banner {
      background-image: url('https://cdn.pixabay.com/photo/2016/03/27/22/22/school-1280559_960_720.jpg');
      background-size: cover;
      background-position: center;
      height: 200px;
      border-radius: 15px;
      margin-bottom: 30px;
      position: relative;
    }
    .header-banner h1 {
      position: absolute;
      bottom: 20px;
      left: 30px;
      color: white;
      background: rgba(0, 0, 0, 0.4);
      padding: 10px 20px;
      border-radius: 10px;
    }
    .denah-img {
      max-width: 100%;
      height: auto;
      border-radius: 10px;
      border: 1px solid #ccc;
    }
    .logo {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      margin-right: 10px;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg px-3" style="background-color:#00695c;color:white;">
  <div class="container-fluid">
    <a class="navbar-brand text-white" href="dashboard.php"> <img src="../assets/logo.jpg" class="logo" alt="logo">Sistem Penilaian Guru</a>
    <ul class="navbar-nav me-auto">
      <li class="nav-item"><a class="nav-link text-white" href="dashboard.php">Dashboard</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="guru.php" <?= $_SESSION['role'] !== 'admin' ? 'style="display: none; opacity: 0.5;"' : '' ?>>Kelola Guru</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="kriteria.php" <?= $_SESSION['role'] !== 'admin' ? 'style="display: none; opacity: 0.5;"' : '' ?>>Kelola Kriteria</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="nilai.php" <?= $_SESSION['role'] !== 'guru' ? 'style="display: none; opacity: 0.5;"' : '' ?>>Kelola Nilai</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="hasil.php">Hasil CPI</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="cetak_laporan.php">Cetak Hasil</a></li>
    </ul>
    <span class="navbar-text text-white">
      Login sebagai: <?= htmlspecialchars($_SESSION['username']) ?> (<?= $_SESSION['role'] ?>)
    </span>
    <a href="../logout.php" class="btn btn-danger ms-3">Logout</a>
  </div>
</nav>

<div class="container mt-4">

  <!-- Banner Gambar -->
  <div class="header-banner">
    <h1>Selamat Datang di Sistem Penilaian Guru</h1>
  </div>

  <!-- Konten Dashboard -->
  <div class="row row-cols-1 row-cols-md-2 g-4">

    <?php if ($_SESSION['role'] === 'admin'): ?>
    <div class="col">
      <div class="card h-100">
        <div class="card-header">Data Guru</div>
        <div class="card-body">
          <p>Kelola data guru dan pegawai sekolah.</p>
          <p>Jumlah: <strong><?= $guru ?></strong></p>
          <a href="guru.php" class="btn btn-success">Kelola</a>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card h-100">
        <div class="card-header">Data Kriteria</div>
        <div class="card-body">
          <p>Kelola kriteria penilaian.</p>
          <p>Jumlah: <strong><?= $kriteria ?></strong></p>
          <a href="kriteria.php" class="btn btn-success">Kelola</a>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <?php if ($_SESSION['role'] === 'guru'): ?>
    <div class="col">
      <div class="card h-100">
        <div class="card-header">Input Nilai</div>
        <div class="card-body">
          <p>Input nilai penilaian berdasarkan kriteria.</p>
          <p>Total Penilaian: <strong><?= $penilaian ?></strong></p>
          <a href="nilai.php" class="btn btn-warning">Input</a>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <div class="col">
      <div class="card h-100">
        <div class="card-header">Hasil CPI</div>
        <div class="card-body">
          <p>Lihat hasil perhitungan metode CPI.</p>
          <a href="hasil.php" class="btn btn-info">Lihat Hasil</a>
        </div>
      </div>
    </div>

  </div>
  
</div>

</body>
</html>

