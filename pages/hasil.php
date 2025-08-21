<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

include '../config/db.php';

function getKriteria($conn)
{
    $result = mysqli_query($conn, "SELECT * FROM kriteria");
    $kriteria = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $kriteria[] = $row;
    }
    return $kriteria;
}

function getGuru($conn, $role, $username)
{
    if ($role === 'guru') {
        // Ambil hanya guru yang sesuai dengan username login
        $stmt = $conn->prepare("SELECT * FROM guru WHERE nama = (SELECT nama FROM pengguna WHERE username = ? LIMIT 1)");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        // Admin / Kepala sekolah ambil semua
        $result = mysqli_query($conn, "SELECT * FROM guru");
    }

    $guru = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $guru[] = $row;
    }
    return $guru;
}

function getPenilaian($conn)
{
    $data = [];
    $result = mysqli_query($conn, "SELECT * FROM penilaian ORDER BY id_guru ASC");
    while ($row = mysqli_fetch_assoc($result)) {
        $data[$row['id_guru']][$row['id_kriteria']] = $row['nilai'];
    }
    return $data;
}

// --- fungsi konversi & hitung CPI (sama seperti sebelumnya) ---
function konversiSkor($id_kriteria, $nilai)
{
    if ($id_kriteria == 1) {
        if ($nilai >= 100) return 4;
        elseif ($nilai >= 95) return 3;
        elseif ($nilai >= 90) return 2;
        else return 1;
    }
    if ($id_kriteria == 2 || $id_kriteria == 3) {
        if ($nilai >= 90) return 4;
        elseif ($nilai >= 80) return 3;
        elseif ($nilai >= 70) return 2;
        else return 1;
    }
    if ($id_kriteria == 4 || $id_kriteria == 5) {
        if ($nilai >= 95) return 5;
        elseif ($nilai >= 90) return 4;
        elseif ($nilai >= 80) return 3;
        elseif ($nilai >= 70) return 2;
        else return 1;
    }
    return 0;
}

function getMaxMin($penilaian, $kriteria)
{
    $maxMin = [];
    foreach ($kriteria as $k) {
        $id = $k['id'];
        $nilaiKriteria = [];
        foreach ($penilaian as $nilaiPerKriteria) {
            if (isset($nilaiPerKriteria[$id])) {
                $nilaiKriteria[] = konversiSkor($id, $nilaiPerKriteria[$id]);
            }
        }
        $maxMin[$id]['max'] = !empty($nilaiKriteria) ? max($nilaiKriteria) : 0;
        $maxMin[$id]['min'] = !empty($nilaiKriteria) ? min($nilaiKriteria) : 0;
    }
    return $maxMin;
}

function hitungCPI($penilaian, $kriteria)
{
    $maxMin = getMaxMin($penilaian, $kriteria);
    $hasil = [];
    foreach ($penilaian as $id_guru => $nilaiPerKriteria) {
        $total = 0;
        foreach ($kriteria as $k) {
            $id_kriteria = $k['id'];
            $nilai = isset($nilaiPerKriteria[$id_kriteria]) ? $nilaiPerKriteria[$id_kriteria] : 0;
            $bobot = $k['bobot'];
            $tren = strtolower($k['tren']);
            $skor = konversiSkor($id_kriteria, $nilai);

            $max = $maxMin[$id_kriteria]['max'];
            $min = $maxMin[$id_kriteria]['min'];
            $normal = 0;
            if ($tren === 'positif' && $min != 0) {
                $normal = ($skor / $min) * 100;
            } elseif ($tren === 'negatif' && $skor != 0) {
                $normal = ($min / $skor) * 100;
            }

            $hasilNormal = $normal * $bobot;
            $hasil[$id_guru]['kalkulasi'][$id_kriteria] = [
                'nilai_mentah' => $nilai,
                'skor' => $skor,
                'normalisasi' => round($normal, 4),
                'bobot' => $bobot,
                'hasil_normal' => $hasilNormal,
                'hasil' => round($hasilNormal, 4)
            ];
            $total += $hasilNormal;
        }
        $hasil[$id_guru]['total'] = round($total, 4);
    }
    return $hasil;
}

// --- Ambil data sesuai role ---
$kriteria = getKriteria($conn);
$guru = getGuru($conn, $_SESSION['role'], $_SESSION['username']);
$penilaian = getPenilaian($conn);
$cpiHasil = hitungCPI($penilaian, $kriteria);
$maxMin = getMaxMin($penilaian, $kriteria);
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Hasil CPI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-success px-3 text-white">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="dashboard.php">← Kembali ke Dashboard</a>
            <span class="navbar-text ms-auto">
                Login sebagai: <?= htmlspecialchars($_SESSION['username']) ?> (<?= $_SESSION['role'] ?>)
            </span>
        </div>
    </nav>

    <div class="container mt-4">

        <!-- Langkah 1 -->
        <div class="card mb-3">
            <div class="card-header bg-success text-white">Langkah 1: Daftar Kriteria dan Bobot</div>
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Kriteria</th>
                        <th>Bobot</th>
                        <th>Tren</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kriteria as $k): ?>
                        <tr>
                            <td><?= $k['nama_kriteria'] ?></td>
                            <td><?= $k['bobot'] ?></td>
                            <td><?= ucfirst($k['tren']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Langkah 2 -->
        <div class="card mb-3">
            <div class="card-header bg-success text-white">Langkah 2: Matriks Penilaian Guru</div>
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <?php foreach ($kriteria as $k): ?>
                            <th><?= $k['nama_kriteria'] ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($_SESSION['role'] === 'guru') {
                        // cari guru login
                        $guruLogin = null;
                        foreach ($guru as $g) {
                            if ($g['nama'] === $_SESSION['username']) {
                                $guruLogin = $g;
                                break;
                            }
                        }

                        if ($guruLogin):
                    ?>
                            <tr>
                                <td><?= $guruLogin['nama'] ?></td>
                                <?php foreach ($kriteria as $kr): ?>
                                    <td><?= $penilaian[$guruLogin['id']][$kr['id']] ?? '-' ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?= count($kriteria) + 1 ?>" class="text-center text-muted">
                                    Data guru tidak ditemukan.
                                </td>
                            </tr>
                        <?php endif;
                    } else {
                        // role admin / kepala sekolah → tampil semua guru
                        foreach ($guru as $g): ?>
                            <tr>
                                <td><?= $g['nama'] ?></td>
                                <?php foreach ($kriteria as $kr): ?>
                                    <td><?= $penilaian[$g['id']][$kr['id']] ?? '-' ?></td>
                                <?php endforeach; ?>
                            </tr>
                    <?php endforeach;
                    }
                    ?>
                </tbody>
            </table>
        </div>



        <!-- Langkah 4 -->
        <div class="card mt-4">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Langkah 3: Kalkulasi CPI</h4>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light text-center">
                        <tr>
                            <th rowspan="2">Nama Guru</th>
                            <?php foreach ($kriteria as $k): ?>
                                <th><?= $k['nama_kriteria'] ?></th>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <?php foreach ($kriteria as $k): ?>
                                <th>Skor</th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($_SESSION['role'] === 'guru') {
                            // cari guru login
                            $guruLogin = null;
                            foreach ($guru as $g) {
                                if ($g['nama'] === $_SESSION['username']) {
                                    $guruLogin = $g;
                                    break;
                                }
                            }

                            if ($guruLogin):
                                $id = $guruLogin['id'];
                                $data = $cpiHasil[$id];
                        ?>
                                <tr>
                                    <td><?= $guruLogin['nama'] ?></td>
                                    <?php foreach ($kriteria as $k):
                                        $d = $data['kalkulasi'][$k['id']] ?? ['skor' => 0];
                                    ?>
                                        <td class="text-center"><?= $d['skor'] ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <td colspan="<?= count($kriteria) + 1 ?>" class="text-center text-muted">
                                        Data guru tidak ditemukan.
                                    </td>
                                </tr>
                            <?php endif;
                        } else {
                            // role admin/kepala sekolah → tampilkan semua
                            foreach ($guru as $g):
                                $id = $g['id'];
                                $data = $cpiHasil[$id];
                            ?>
                                <tr>
                                    <td><?= $g['nama'] ?></td>
                                    <?php foreach ($kriteria as $k):
                                        $d = $data['kalkulasi'][$k['id']] ?? ['skor' => 0];
                                    ?>
                                        <td class="text-center"><?= $d['skor'] ?></td>
                                    <?php endforeach; ?>
                                </tr>
                        <?php endforeach;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Langkah 3 -->
        <div class="card mb-3">
            <div class="card-header bg-success text-white">Langkah 4: Nilai Max & Min Tiap Kriteria</div>
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Kriteria</th>
                        <th>Max</th>
                        <th>Min</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kriteria as $k): ?>
                        <tr>
                            <td><?= $k['nama_kriteria'] ?></td>
                            <td><?= $maxMin[$k['id']]['max'] ?></td>
                            <td><?= $maxMin[$k['id']]['min'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Langkah 4 -->
        <div class="card mt-4">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Langkah 5: Kalkulasi CPI</h4>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light text-center">
                        <tr>
                            <th rowspan="2">Nama Guru</th>
                            <?php foreach ($kriteria as $k): ?>
                                <th><?= $k['nama_kriteria'] ?></th>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <?php foreach ($kriteria as $k): ?>
                                <th>× Bobot (<?= $k['bobot'] ?>)</th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($_SESSION['role'] === 'guru') {
                            // cari guru login
                            $guruLogin = null;
                            foreach ($guru as $g) {
                                if ($g['nama'] === $_SESSION['username']) {
                                    $guruLogin = $g;
                                    break;
                                }
                            }

                            if ($guruLogin):
                                $id = $guruLogin['id'];
                                $data = $cpiHasil[$id];
                        ?>
                                <tr>
                                    <td><?= $guruLogin['nama'] ?></td>
                                    <?php foreach ($kriteria as $k):
                                        $d = $data['kalkulasi'][$k['id']] ?? ['nilai' => 0, 'normalisasi' => 0, 'skor' => 0, 'bobot' => 0, 'hasil' => 0, 'hasil_normal' => 0];
                                    ?>
                                        <td class="text-center">
                                            <?= $d['normalisasi'] ?> × <?= number_format($d['bobot'], 2) ?> = <?= number_format($d['hasil_normal'], 2) ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <td colspan="<?= count($kriteria) + 1 ?>" class="text-center text-muted">
                                        Data guru tidak ditemukan.
                                    </td>
                                </tr>
                            <?php endif;
                        } else {
                            // role admin / kepala sekolah -> tampilkan semua
                            foreach ($guru as $g):
                                $id = $g['id'];
                                $data = $cpiHasil[$id];
                            ?>
                                <tr>
                                    <td><?= $g['nama'] ?></td>
                                    <?php foreach ($kriteria as $k):
                                        $d = $data['kalkulasi'][$k['id']] ?? ['nilai' => 0, 'normalisasi' => 0, 'skor' => 0, 'bobot' => 0, 'hasil' => 0, 'hasil_normal' => 0];
                                    ?>
                                        <td class="text-center">
                                            <?= $d['normalisasi'] ?> × <?= number_format($d['bobot'], 2) ?> = <?= number_format($d['hasil_normal'], 2) ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                        <?php endforeach;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>






        <!-- Langkah 6 -->
        <div class="card mb-5">
            <div class="card-header bg-success text-white">Langkah 6: Status Reward</div>
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>CPI</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Urutkan guru berdasarkan CPI tertinggi
                    $sortedGuru = $guru;
                    usort($sortedGuru, function ($a, $b) use ($cpiHasil) {
                        return $cpiHasil[$b['id']]['total'] <=> $cpiHasil[$a['id']]['total'];
                    });

                    // Ambil 5 teratas
                    $top5 = array_slice($sortedGuru, 0, 5);

                    // Cek role
                    if ($_SESSION['role'] === 'guru') {
                        // Cari guru yang login
                        $guruLogin = null;
                        foreach ($guru as $g) {
                            if ($g['nama'] === $_SESSION['username']) {
                                $guruLogin = $g;
                                break;
                            }
                        }

                        if ($guruLogin):
                            $id = $guruLogin['id'];
                            $cpi = $cpiHasil[$id]['total'];
                            $isLayak = in_array($guruLogin, $top5, true);
                    ?>
                            <tr>
                                <td><?= $guruLogin['nama'] ?></td>
                                <td><?= number_format($cpi, 2) ?></td>
                                <td>
                                    <?php if ($isLayak): ?>
                                        <span class="badge bg-success p-2">Layak Reward</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger p-2">Belum Layak</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">Data guru tidak ditemukan.</td>
                            </tr>
                        <?php endif;
                    } else {
                        // Role admin / kepala sekolah => tampilkan semua guru
                        foreach ($sortedGuru as $g):
                            $id = $g['id'];
                            $cpi = $cpiHasil[$id]['total'];
                            $isLayak = in_array($g, $top5, true);
                        ?>
                            <tr>
                                <td><?= $g['nama'] ?></td>
                                <td><?= number_format($cpi, 2) ?></td>
                                <td>
                                    <?php if ($isLayak): ?>
                                        <span class="badge bg-success p-2">Layak Reward</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger p-2">Belum Layak</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                    <?php endforeach;
                    } ?>
                </tbody>
            </table>

            <?php if ($_SESSION['role'] === 'guru' && isset($guruLogin)): ?>
                <div class="p-3">
                    <?php if ($isLayak): ?>
                        <div class="alert alert-success">
                            🎉 Selamat <strong><?= $guruLogin['nama'] ?></strong>!
                            Anda termasuk dalam <strong>5 guru terbaik</strong> berdasarkan hasil perhitungan CPI.
                            Anda berhak mendapatkan <strong>Reward</strong> sebagai bentuk apresiasi atas kinerja Anda.
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            ⚡ Hai <strong><?= $guruLogin['nama'] ?></strong>, saat ini Anda <strong>belum masuk 5 besar</strong> penerima reward.
                            Tetap semangat dan tingkatkan kinerja Anda untuk kesempatan reward pada periode berikutnya.
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>






    </div>
</body>

</html>