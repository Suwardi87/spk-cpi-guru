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
    mysqli_query($conn, "DELETE FROM karyawan WHERE id = $id");
    header("Location: karyawan.php");
}

// Proses Tambah/Edit
if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];

    if ($id == '') {
        mysqli_query($conn, "INSERT INTO karyawan (nama, jabatan) VALUES ('$nama', '$jabatan')");
    } else {
        mysqli_query($conn, "UPDATE karyawan SET nama='$nama', jabatan='$jabatan' WHERE id=$id");
    }
    header("Location: karyawan.php");
}

$edit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM karyawan WHERE id=$id"));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Karyawan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-dark px-3">
  <a href="dashboard.php" class="navbar-brand text-white">← Kembali ke Dashboard</a>
  <span class="text-white">Login sebagai: <?= $_SESSION['username'] ?> (<?= $_SESSION['role'] ?>)</span>
</nav>

<div class="container mt-4">

  <h3>Data Karyawan</h3>

  <!-- Form Tambah/Edit -->
  <div class="card mb-4">
    <div class="card-header">
      <?= $edit ? "Edit Karyawan" : "Tambah Karyawan" ?>
    </div>
    <div class="card-body">
      <form method="post">
        <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">
        <div class="mb-3">
          <label>Nama Karyawan</label>
          <input type="text" name="nama" class="form-control" required value="<?= $edit['nama'] ?? '' ?>">
        </div>
        <div class="mb-3">
          <label>Jabatan</label>
          <input type="text" name="jabatan" class="form-control" required value="<?= $edit['jabatan'] ?? '' ?>">
        </div>
        <button class="btn btn-primary" name="simpan">Simpan</button>
        <?php if ($edit): ?>
          <a href="karyawan.php" class="btn btn-secondary">Batal</a>
        <?php endif; ?>
      </form>
    </div>
  </div>

  <!-- Tabel Data -->
  <table class="table table-bordered table-hover">
    <thead class="table-dark">
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
      $data = mysqli_query($conn, "SELECT * FROM karyawan");
      while ($row = mysqli_fetch_assoc($data)):
      ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['nama'] ?></td>
        <td><?= $row['jabatan'] ?></td>
        <td>
          <a href="karyawan.php?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
          <a href="karyawan.php?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

</div>

</body>
</html>
