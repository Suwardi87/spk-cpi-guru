<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php"); // relatif karena file ini ada di /pages
    exit;
}

include '../config/db.php';

// hitung jumlah data
$karyawan = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM karyawan"));
$kriteria = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM kriteria"));
$penilaian = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM penilaian"));

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - SPK CPI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card {
      margin-bottom: 20px;
    }
    .card-header {
      background-color: #343a40;
      color: white;
    }
    .card-body {
      padding: 20px;
    }
    .card-footer {
      background-color: #343a40;
      color: white;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="karyawan.php" <?= $_SESSION['role'] !== 'admin' ? 'style="pointer-events: none;opacity: 0.5;"' : '' ?>>Kelola Karyawan</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="kriteria.php" <?= $_SESSION['role'] !== 'admin' ? 'style="pointer-events: none;opacity: 0.5;"' : '' ?>>Kelola Kriteria</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="nilai.php" <?= $_SESSION['role'] !== 'supervisor' ? 'style="pointer-events: none;opacity: 0.5;"' : '' ?>>Input Nilai</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="hasil.php">Lihat Hasil CPI</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="cetak_laporan.php">Cetak PDF</a>
        </li>
      </ul>
      <span class="navbar-text ms-auto">
        Login sebagai: <?= $_SESSION['username'] ?> (<?= $_SESSION['role'] ?>)
      </span>
      <a href="../logout.php" class="btn btn-danger ms-3">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <div class="row">

    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          Kelola Karyawan
        </div>
        <div class="card-body">
          <p class="card-text">Kelola data karyawan</p>
          <p class="card-text">Jumlah Karyawan: <?= $karyawan ?></p>
          <a href="karyawan.php" class="btn btn-primary">Kelola</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          Kelola Kriteria
        </div>
        <div class="card-body">
          <p class="card-text">Kelola data kriteria penilaian</p>
          <p class="card-text">Jumlah Kriteria: <?= $kriteria ?></p>
          <a href="kriteria.php" class="btn btn-primary">Kelola</a>
        </div>
      </div>
    </div>

    <?php if ($_SESSION['role'] !== 'supervisor'): ?>
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">
            Input Nilai
          </div>
          <div class="card-body">
            <p class="card-text">Input nilai penilaian</p>
            <a href="nilai.php" class="btn btn-primary">Input</a>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          Lihat Hasil CPI
        </div>
        <div class="card-body">
          <p class="card-text">Lihat hasil CPI</p>
          <a href="hasil.php" class="btn btn-primary">Lihat</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          Cetak PDF
        </div>
        <div class="card-body">
          <p class="card-text">Cetak laporan CPI</p>
          <a href="cetak_laporan.php" class="btn btn-primary" target="_blank">Cetak</a>
        </div>
      </div>
    </div>

  </div>
</div>

</body>
</html>

