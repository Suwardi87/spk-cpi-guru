<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../config/db.php';

// Proses Hapus
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM guru WHERE id = $id");
    header("Location: guru.php");
    exit;
}

// Proses Tambah/Edit
if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];

    if ($id == '') {
        mysqli_query($conn, "INSERT INTO guru (nama, jabatan) VALUES ('$nama', '$jabatan')");
    } else {
        mysqli_query($conn, "UPDATE guru SET nama='$nama', jabatan='$jabatan' WHERE id=$id");
    }
    header("Location: guru.php");
    exit;
}

$edit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM guru WHERE id=$id"));
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Guru & Pegawai</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .card, .table { background-color: white; }
    .card-header, .table-s { background-color: #00695c; color: white; font-weight: bold; }
    .btn-primary { background-color: #00695c; border-color: #00695c; border-radius: 8px; }
    .btn-secondary { background-color: #868e96; border-color: #868e96; border-radius: 8px; }
    .btn-warning { background-color: #ffc107; border-color: #ffc107; border-radius: 8px; }
    .btn-danger { background-color: #dc3545; border-color: #dc3545; border-radius: 8px; }
    .form-control { border-radius: 8px; }
    .table-hover tbody tr:hover { background-color: #cfd2d6; }
    .navbar { background-color: #00695c !important; }
    .navbar-brand, .navbar-text { color: #fff !important; }
    h3 { margin-top: 32px; }
    thead.table-dark th, thead.table-s th {
        background-color: #00695c !important;
        color: #fff !important;
    }
  </style>
</head>
<body>

<nav class="navbar px-3" style="background-color:#00695c; color:white;">
  <a href="dashboard.php" class="navbar-brand text-white">← Kembali ke Dashboard</a>
  <span class="text-white">Login sebagai: <?= $_SESSION['username'] ?> (<?= $_SESSION['role'] ?>)</span>
</nav>

<div class="container mt-4">
  <h3>Data Guru & Pegawai</h3>

  <!-- Form Tambah/Edit -->
  <div class="card mb-4">
    <div class="card-header">
      <?= $edit ? "Edit Data" : "Tambah Data" ?>
    </div>
    <div class="card-body">
      <form method="post">
        <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">
        <div class="mb-3">
          <label>Nama</label>
          <input type="text" name="nama" class="form-control" required value="<?= $edit['nama'] ?? '' ?>">
        </div>
        <div class="mb-3">
          <label>Jabatan</label>
          <input type="text" name="jabatan" class="form-control" required value="<?= $edit['jabatan'] ?? '' ?>">
        </div>
        <button class="btn btn-primary" name="simpan">Simpan</button>
        <?php if ($edit): ?>
          <a href="guru.php" class="btn btn-secondary">Batal</a>
        <?php endif; ?>
      </form>
    </div>
  </div>

  <!-- Tabel Data -->
  <table class="table table-bordered table-hover">
    <thead class="table-s">
      <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Jabatan</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = 1;
      $data = mysqli_query($conn, "SELECT * FROM guru ORDER BY id ASC");
      while ($row = mysqli_fetch_assoc($data)):
      ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['nama'] ?></td>
        <td><?= $row['jabatan'] ?></td>
        <td>
          <a href="guru.php?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
          <a href="guru.php?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

</div>

</body>
</html>

