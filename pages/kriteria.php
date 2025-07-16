<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../config/db.php';

// Proses Simpan
if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama_kriteria'];
    $bobot = $_POST['bobot'];

    if ($id == '') {
        mysqli_query($conn, "INSERT INTO kriteria (nama_kriteria, bobot) VALUES ('$nama', '$bobot')");
    } else {
        mysqli_query($conn, "UPDATE kriteria SET nama_kriteria='$nama', bobot='$bobot' WHERE id=$id");
    }
    header("Location: kriteria.php");
}

// Proses Hapus
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM kriteria WHERE id=$id");
    header("Location: kriteria.php");
}

// Proses Edit
$edit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM kriteria WHERE id=$id"));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Kriteria</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-dark px-3">
  <a href="dashboard.php" class="navbar-brand text-white">← Kembali ke Dashboard</a>
  <span class="text-white">Login sebagai: <?= $_SESSION['username'] ?> (<?= $_SESSION['role'] ?>)</span>
</nav>

<div class="container mt-4">

  <h3>Data Kriteria Penilaian</h3>

  <!-- Form Tambah/Edit -->
  <div class="card mb-4">
    <div class="card-header">
      <?= $edit ? "Edit Kriteria" : "Tambah Kriteria" ?>
    </div>
    <div class="card-body">
      <form method="post">
        <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">
        <div class="mb-3">
          <label>Nama Kriteria</label>
          <input type="text" name="nama_kriteria" class="form-control" required value="<?= $edit['nama_kriteria'] ?? '' ?>">
        </div>
        <div class="mb-3">
          <label>Bobot (0.0 - 1.0)</label>
          <input type="number" step="0.01" min="0" max="1" name="bobot" class="form-control" required value="<?= $edit['bobot'] ?? '' ?>">
        </div>
        <button class="btn btn-primary" name="simpan">Simpan</button>
        <?php if ($edit): ?>
          <a href="kriteria.php" class="btn btn-secondary">Batal</a>
        <?php endif; ?>
      </form>
    </div>
  </div>

  <!-- Tabel Kriteria -->
  <table class="table table-bordered table-hover">
    <thead class="table-dark">
      <tr>
        <th>No</th>
        <th>Nama Kriteria</th>
        <th>Bobot</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = 1;
      $data = mysqli_query($conn, "SELECT * FROM kriteria");
      while ($row = mysqli_fetch_assoc($data)):
      ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['nama_kriteria'] ?></td>
        <td><?= $row['bobot'] ?></td>
        <td>
          <a href="kriteria.php?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
          <a href="kriteria.php?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

</div>

</body>
</html>
