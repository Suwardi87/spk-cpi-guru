<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

include '../config/db.php';

function getKriteria($conn) {
    $result = mysqli_query($conn, "SELECT * FROM kriteria");
    $kriteria = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $kriteria[] = $row;
    }
    return $kriteria;
}

function getKaryawan($conn) {
    $result = mysqli_query($conn, "SELECT * FROM karyawan");
    $karyawan = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $karyawan[] = $row;
    }
    return $karyawan;
}

function getPenilaian($conn) {
    $data = [];
    $result = mysqli_query($conn, "SELECT * FROM penilaian");
    while ($row = mysqli_fetch_assoc($result)) {
        $data[$row['id_karyawan']][$row['id_kriteria']] = $row['nilai'];
    }
    return $data;
}

function hitungCPI($penilaian, $kriteria) {
    $hasil = [];
    foreach ($penilaian as $id_karyawan => $nilaiPerKriteria) {
        $total = 0;
        foreach ($kriteria as $k) {
            $nilai = isset($nilaiPerKriteria[$k['id']]) ? $nilaiPerKriteria[$k['id']] : 0;
            $kalkulasi = $nilai * $k['bobot'];
            $total += $kalkulasi;
            $hasil[$id_karyawan]['kalkulasi'][$k['id']] = [
                'nilai' => $nilai,
                'bobot' => $k['bobot'],
                'hasil' => $kalkulasi
            ];
        }
        $hasil[$id_karyawan]['total'] = $total;
    }
    return $hasil;
}

$kriteria = getKriteria($conn);
$karyawan = getKaryawan($conn);
$penilaian = getPenilaian($conn);
$cpiHasil = hitungCPI($penilaian, $kriteria);

// Sort karyawan by CPI total in descending order
usort($karyawan, function($a, $b) use ($cpiHasil) {
    return $cpiHasil[$b['id']]['total'] <=> $cpiHasil[$a['id']]['total'];
});

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil CPI - Langkah Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-dark px-3">
  <a href="dashboard.php" class="navbar-brand text-white">← Dashboard</a>
  <span class="text-white">Login sebagai: <?= $_SESSION['username'] ?> (<?= $_SESSION['role'] ?>)</span>
</nav>

<div class="container mt-4">
    <h3>🔹 Langkah 1: Daftar Kriteria dan Bobot</h3>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr><th>Kriteria</th><th>Bobot</th></tr>
        </thead>
        <tbody>
            <?php foreach ($kriteria as $k): ?>
                <tr><td><?= $k['nama_kriteria'] ?></td><td><?= $k['bobot'] ?></td></tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>🔹 Langkah 2: Matriks Penilaian Karyawan</h3>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Nama</th>
                <?php foreach ($kriteria as $k): ?><th><?= $k['nama_kriteria'] ?></th><?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($karyawan as $k): ?>
                <tr>
                    <td><?= $k['nama'] ?></td>
                    <?php foreach ($kriteria as $kr): ?>
                        <td><?= $penilaian[$k['id']][$kr['id']] ?? '-' ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>🔹 Langkah 3: Kalkulasi CPI (Nilai × Bobot)</h3>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Nama</th>
                <?php foreach ($kriteria as $k): ?><th><?= $k['nama_kriteria'] ?> (Nilai × Bobot)</th><?php endforeach; ?>
                <th>Total CPI</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($karyawan as $k): ?>
                <tr>
                    <td><?= $k['nama'] ?></td>
                    <?php foreach ($kriteria as $kr): ?>
                        <?php 
                        $calc = $cpiHasil[$k['id']]['kalkulasi'][$kr['id']] ?? ['nilai'=>0,'bobot'=>0,'hasil'=>0];
                        ?>
                        <td><?= $calc['nilai'] ?> × <?= $calc['bobot'] ?> = <strong><?= number_format($calc['hasil'], 2) ?></strong></td>
                    <?php endforeach; ?>
                    <td><strong><?= number_format($cpiHasil[$k['id']]['total'], 2) ?></strong></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>🔹 Langkah 4: Status Reward</h3>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr><th>Nama</th><th>CPI</th><th>Status</th></tr>
        </thead>
        <tbody>
            <?php foreach ($karyawan as $k): ?>
                <?php $cpi = $cpiHasil[$k['id']]['total']; ?>
                <tr>
                    <td><?= $k['nama'] ?></td>
                    <td><?= number_format($cpi, 2) ?></td>
                    <td>
                        <?= $cpi >= 80 ? '<span class="badge bg-success">Layak Reward</span>' : '<span class="badge bg-danger">Belum Layak</span>' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>🔹 Langkah 5: Urutan Berdasarkan CPI</h3>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr><th>Urutan</th><th>Nama</th><th>CPI</th></tr>
        </thead>
        <tbody>
            <?php $rank = 1; ?>
            <?php foreach ($karyawan as $k): ?>
                <tr>
                    <td><?= $rank++ ?></td>
                    <td><?= $k['nama'] ?></td>
                    <td><?= number_format($cpiHasil[$k['id']]['total'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>

