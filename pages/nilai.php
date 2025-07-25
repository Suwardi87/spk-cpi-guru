<?php
session_start();
if (!isset($_SESSION['username']) || ($_SESSION['role'] !== 'kepala_sekolah' && $_SESSION['role'] !== 'guru')) {
    header("Location: ../login.php");
    exit;
}

include '../config/db.php';

// Ambil data
$guruList = mysqli_query($conn, "SELECT * FROM guru");
$kriteriaList = mysqli_query($conn, "SELECT * FROM kriteria");

// Proses Simpan / Update
if (isset($_POST['simpan'])) {
    $id_guru = $_POST['id_guru'];
    foreach ($_POST['nilai'] as $id_kriteria => $nilai) {
        mysqli_query($conn, "REPLACE INTO penilaian (id_guru, id_kriteria, nilai) 
            VALUES ('$id_guru', '$id_kriteria', '$nilai')");
    }
    echo "<script>alert('Data berhasil disimpan!'); location.href='nilai.php';</script>";
    exit;
}

// Proses Hapus
if (isset($_GET['hapus'])) {
    $id_guru_hapus = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM penilaian WHERE id_guru = '$id_guru_hapus'");
    echo "<script>alert('Data berhasil dihapus!'); location.href='nilai.php';</script>";
    exit;
}

// Ambil semua penilaian
$penilaian = [];
$nilai_q = mysqli_query($conn, "SELECT * FROM penilaian");
while ($row = mysqli_fetch_assoc($nilai_q)) {
    $penilaian[$row['id_guru']][$row['id_kriteria']] = $row['nilai'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Penilaian Guru</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar px-3" style="background-color: #00695c;">
  <a href="dashboard.php" class="navbar-brand text-white">← Dashboard</a>
  <span class="text-white">Login sebagai: <?= $_SESSION['username'] ?> (<?= $_SESSION['role'] ?>)</span>
</nav>

<div class="container mt-4">
  <h3>Input / Edit Nilai Guru</h3>

  <div class="card mb-4">
    <div class="card-body">
      <form method="post">
        <!-- Pilih Guru -->
        <div class="mb-3">
          <label class="form-label">Pilih Guru</label>
          <select name="id_guru" class="form-select" required onchange="this.form.submit()">
            <option value="">-- Pilih Guru --</option>
            <?php mysqli_data_seek($guruList, 0); while ($g = mysqli_fetch_assoc($guruList)): ?>
              <option value="<?= $g['id'] ?>" <?= isset($_POST['id_guru']) && $_POST['id_guru'] == $g['id'] ? 'selected' : '' ?>>
                <?= $g['nama'] ?> - <?= $g['jabatan'] ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <!-- Input Nilai -->
        <?php if (!empty($_POST['id_guru'])): ?>
          <div class="mb-3">
            <label class="form-label">Nilai per Kriteria</label>
            <div class="row">
              <?php 
              $id_selected = $_POST['id_guru'];
              mysqli_data_seek($kriteriaList, 0); 
              while ($k = mysqli_fetch_assoc($kriteriaList)): 
                $current = $penilaian[$id_selected][$k['id']] ?? '';
              ?>
              <div class="col-md-6 mb-3">
                <label><?= $k['nama_kriteria'] ?></label>
                <input type="number" class="form-control" name="nilai[<?= $k['id'] ?>]" min="0" max="100" required value="<?= $current ?>">
              </div>
              <?php endwhile; ?>
            </div>
            <button name="simpan" class="btn btn-">Simpan</button>
          </div>
        <?php endif; ?>
      </form>
    </div>
  </div>

  <!-- Tabel Penilaian -->
  <h4>Data Penilaian Guru</h4>
  <div class="table-responsive">
    <table class="table table-bordered text-center align-middle" >
      <thead >
        <tr>
          <th>Guru</th>
          <?php mysqli_data_seek($kriteriaList, 0); while ($k = mysqli_fetch_assoc($kriteriaList)): ?>
            <th><?= $k['nama_kriteria'] ?></th>
          <?php endwhile; ?>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        mysqli_data_seek($guruList, 0);
        while ($g = mysqli_fetch_assoc($guruList)): 
          if (!isset($penilaian[$g['id']])) continue; // Skip jika tidak ada data
        ?>
        <tr>
          <td><?= $g['nama'] ?></td>
          <?php 
          mysqli_data_seek($kriteriaList, 0); 
          while ($k = mysqli_fetch_assoc($kriteriaList)): 
            echo "<td>" . ($penilaian[$g['id']][$k['id']] ?? '-') . "</td>";
          endwhile; 
          ?>
          <td>
            <a href="?hapus=<?= $g['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus semua nilai guru ini?')">Hapus</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
