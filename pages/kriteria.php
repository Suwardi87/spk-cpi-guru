<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../config/db.php';

// Proses Simpan
if (isset($_POST['simpan'])) {
    $id     = intval($_POST['id']);
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_kriteria']);
    $bobot  = floatval($_POST['bobot']);

    if ($id == 0) {
        mysqli_query($conn, "INSERT INTO kriteria (nama_kriteria, bobot) VALUES ('$nama', '$bobot')");
    } else {
        mysqli_query($conn, "UPDATE kriteria SET nama_kriteria='$nama', bobot='$bobot' WHERE id=$id");
    }
    header("Location: kriteria.php");
    exit;
}

// Proses Hapus
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($conn, "DELETE FROM kriteria WHERE id=$id");
    header("Location: kriteria.php");
    exit;
}

// Ambil Data Edit
$edit = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $edit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM kriteria WHERE id=$id"));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Kriteria</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .card { margin-bottom: 20px; border-radius: 15px; }
    .card-header { background-color: #00695c; color: white; font-weight: bold; }
    .table-hover tbody tr:hover { background-color: #cfd2d6; }
    .navbar { background-color: #00695c !important; }
    .navbar-brand, .navbar-text { color: #fff !important; }
    .btn-primary, .btn-warning, .btn-danger, .btn-secondary { border-radius: 8px; }
    .form-control { border-radius: 8px; }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg px-3">
  <div class="container-fluid">
    <a href="dashboard.php" class="navbar-brand">← Kembali ke Dashboard</a>
    <span class="navbar-text ms-auto">
      Login sebagai: <?= htmlspecialchars($_SESSION['username']) ?> (<?= $_SESSION['role'] ?>)
    </span>
  </div>
</nav>

<div class="container mt-4">

  <h3 class="mb-3">Data Kriteria Penilaian</h3>

  <!-- Form Tambah/Edit -->
  <div class="card">
    <div class="card-header">
      <?= $edit ? "Edit Kriteria" : "Tambah Kriteria" ?>
    </div>
    <div class="card-body">
      <form method="post">
        <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">
        <div class="mb-3">
          <label class="form-label">Nama Kriteria</label>
          <input type="text" name="nama_kriteria" class="form-control" required value="<?= $edit['nama_kriteria'] ?? '' ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Bobot (0.0 - 1.0)</label>
          <input type="number" step="0.01" min="0" max="1" name="bobot" class="form-control" required value="<?= $edit['bobot'] ?? '' ?>">
        </div>
        <button class="btn btn-success" name="simpan">Simpan</button>
        <?php if ($edit): ?>
          <a href="kriteria.php" class="btn btn-secondary">Batal</a>
        <?php endif; ?>
      </form>
    </div>
  </div>

  <!-- Tabel Kriteria -->
  <div class="card">
    <div class="card-header">Daftar Kriteria</div>
    <div class="card-body p-0">
      <table class="table table-bordered table-hover mb-0">
        <thead class="table-dark">
          <tr>
            <th style="width:5%;">No</th>
            <th>Nama Kriteria</th>
            <th>Bobot</th>
            <th style="width:18%;">Aksi</th>
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
            <td><?= htmlspecialchars($row['nama_kriteria']) ?></td>
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
  </div>

</div>

</body>
</html>

