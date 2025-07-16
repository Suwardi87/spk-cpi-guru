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

function getGuru($conn) {
    $result = mysqli_query($conn, "SELECT * FROM guru");
    $guru = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $guru[] = $row;
    }
    return $guru;
}

function getPenilaian($conn) {
    $data = [];
    $result = mysqli_query($conn, "SELECT * FROM penilaian");
    while ($row = mysqli_fetch_assoc($result)) {
        $data[$row['id_guru']][$row['id_kriteria']] = $row['nilai'];
    }
    return $data;
}

function hitungCPI($penilaian, $kriteria) {
    $hasil = [];
    foreach ($penilaian as $id_guru => $nilaiPerKriteria) {
        $total = 0;
        foreach ($kriteria as $k) {
            $nilai = isset($nilaiPerKriteria[$k['id']]) ? $nilaiPerKriteria[$k['id']] : 0;
            $kalkulasi = $nilai * $k['bobot'];
            $total += $kalkulasi;
            $hasil[$id_guru]['kalkulasi'][$k['id']] = [
                'nilai' => $nilai,
                'bobot' => $k['bobot'],
                'hasil' => $kalkulasi
            ];
        }
        $hasil[$id_guru]['total'] = $total;
    }
    return $hasil;
}

$kriteria = getKriteria($conn);
$guru = getGuru($conn);
$penilaian = getPenilaian($conn);
$cpiHasil = hitungCPI($penilaian, $kriteria);

usort($guru, function($a, $b) use ($cpiHasil) {
    return $cpiHasil[$b['id']]['total'] <=> $cpiHasil[$a['id']]['total'];
});
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil CPI - Langkah Detail</title>
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
<nav class="navbar navbar-expand-lg px-3">
  <div class="container-fluid">
    <a href="dashboard.php" class="navbar-brand">← Kembali ke Dashboard</a>
    <span class="navbar-text ms-auto">
      Login sebagai: <?= htmlspecialchars($_SESSION['username']) ?> (<?= $_SESSION['role'] ?>)
    </span>
  </div>
</nav>

<div class="container mt-4">

    <div class="card">
        <div class="card-header">Langkah 1: Daftar Kriteria dan Bobot</div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-dark">
                    <tr><th>Kriteria</th><th>Bobot</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($kriteria as $k): ?>
                        <tr><td><?= $k['nama_kriteria'] ?></td><td><?= $k['bobot'] ?></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Langkah 2: Matriks Penilaian Guru</div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Nama</th>
                        <?php foreach ($kriteria as $k): ?><th><?= $k['nama_kriteria'] ?></th><?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($guru as $g): ?>
                        <tr>
                            <td><?= $g['nama'] ?></td>
                            <?php foreach ($kriteria as $kr): ?>
                                <td><?= $penilaian[$g['id']][$kr['id']] ?? '-' ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Langkah 3: Kalkulasi CPI (Nilai × Bobot)</div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Nama</th>
                        <?php foreach ($kriteria as $k): ?><th><?= $k['nama_kriteria'] ?> (Nilai × Bobot)</th><?php endforeach; ?>
                        <th>Total CPI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($guru as $g): ?>
                        <tr>
                            <td><?= $g['nama'] ?></td>
                            <?php foreach ($kriteria as $kr): ?>
                                <?php 
                                $calc = $cpiHasil[$g['id']]['kalkulasi'][$kr['id']] ?? ['nilai'=>0,'bobot'=>0,'hasil'=>0];
                                ?>
                                <td><?= $calc['nilai'] ?> × <?= $calc['bobot'] ?> = <strong><?= number_format($calc['hasil'], 2) ?></strong></td>
                            <?php endforeach; ?>
                            <td><strong><?= number_format($cpiHasil[$g['id']]['total'], 2) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Langkah 4: Status Reward</div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-dark">
                    <tr><th>Nama</th><th>CPI</th><th>Status</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($guru as $g): ?>
                        <?php $cpi = $cpiHasil[$g['id']]['total']; ?>
                        <tr>
                            <td><?= $g['nama'] ?></td>
                            <td><?= number_format($cpi, 2) ?></td>
                            <td>
                                <?= $cpi >= 80 ? '<span class="badge bg-success">Layak Reward</span>' : '<span class="badge bg-danger">Belum Layak</span>' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Langkah 5: Urutan Berdasarkan CPI</div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-dark">
                    <tr><th>Urutan</th><th>Nama</th><th>CPI</th></tr>
                </thead>
                <tbody>
                    <?php $rank = 1; ?>
                    <?php foreach ($guru as $g): ?>
                        <tr>
                            <td><?= $rank++ ?></td>
                            <td><?= $g['nama'] ?></td>
                            <td><?= number_format($cpiHasil[$g['id']]['total'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</body>
</html>
