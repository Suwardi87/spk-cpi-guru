<?php
session_start();
if (!isset($_SESSION['username']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'hrd')) {
    header("Location: ../login.php");
    exit;
}

include '../config/db.php';

// Ambil data
$karyawan = mysqli_query($conn, "SELECT * FROM karyawan");
$kriteria = mysqli_query($conn, "SELECT * FROM kriteria");

// Proses simpan
if (isset($_POST['simpan'])) {
    $id_karyawan = $_POST['id_karyawan'];

    foreach ($_POST['nilai'] as $id_kriteria => $nilai) {
        mysqli_query($conn, "REPLACE INTO penilaian (id_karyawan, id_kriteria, nilai) 
        VALUES ('$id_karyawan', '$id_kriteria', '$nilai')");
    }

    echo "<script>alert('Nilai berhasil disimpan'); location.href='nilai.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Input Nilai Karyawan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-dark px-3">
  <a href="dashboard.php" class="navbar-brand text-white">← Dashboard</a>
  <span class="text-white">Login sebagai: <?= $_SESSION['username'] ?> (<?= $_SESSION['role'] ?>)</span>
</nav>

<div class="container mt-4">
  <h3>Input Nilai Kinerja Karyawan</h3>

  <div class="card mt-3">
    <div class="card-body">
      <form method="post">

        <!-- Pilih Karyawan -->
        <div class="mb-3">
          <label class="form-label">Pilih Karyawan</label>
          <select name="id_karyawan" class="form-select" required>
            <option value="">-- Pilih --</option>
            <?php while ($k = mysqli_fetch_assoc($karyawan)): ?>
              <option value="<?= $k['id'] ?>">
                <?= $k['nama'] ?> - <?= $k['jabatan'] ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <!-- Input Nilai Kriteria -->
        <div class="mb-3">
          <label class="form-label">Nilai per Kriteria (0 - 100)</label>
          <div class="row">
            <?php 
            mysqli_data_seek($kriteria, 0); // reset pointer
            while ($k = mysqli_fetch_assoc($kriteria)): ?>
              <div class="col-md-6 mb-3">
                <label class="form-label"><?= $k['nama_kriteria'] ?></label>
                <input type="number" name="nilai[<?= $k['id'] ?>]" class="form-control" min="0" max="100" required>
              </div>
            <?php endwhile; ?>
          </div>
        </div>

        <!-- Tombol -->
        <button type="submit" name="simpan" class="btn btn-primary">Simpan Nilai</button>
      </form>
    </div>
  </div>
</div>

</body>
</html>
