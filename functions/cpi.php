<?php
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

function getGuru($conn)
{
    $result = mysqli_query($conn, "SELECT * FROM guru");
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

function konversiSkor($id_kriteria, $nilai)
{
    $skor = 0;
    switch ($id_kriteria) {
        case 1:
            $skor = $nilai >= 100 ? 4 : ($nilai >= 95 ? 3 : ($nilai >= 90 ? 2 : 1));
            break;
        case 2:
        case 3:
            $skor = $nilai >= 90 ? 4 : ($nilai >= 80 ? 3 : ($nilai >= 70 ? 2 : 1));
            break;
        case 4:
        case 5:
            $skor = $nilai >= 95 ? 5 : ($nilai >= 90 ? 4 : ($nilai >= 80 ? 3 : ($nilai >= 70 ? 2 : 1)));
            break;
    }
    return $skor;
}

function getMaxMin($penilaian, $kriteria)
{
    $maxMin = [];
    foreach ($kriteria as $k) {
        $id = $k['id'];
        $nilaiKriteria = array_map(function($nilaiPerKriteria) use ($id) {
            return isset($nilaiPerKriteria[$id]) ? konversiSkor($id, $nilaiPerKriteria[$id]) : null;
        }, $penilaian);
        $nilaiKriteria = array_filter($nilaiKriteria);
        $maxMin[$id] = [
            'max' => !empty($nilaiKriteria) ? max($nilaiKriteria) : 0,
            'min' => !empty($nilaiKriteria) ? min($nilaiKriteria) : 0
        ];
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
            $nilai = $nilaiPerKriteria[$id_kriteria] ?? 0;
            $bobot = $k['bobot'];
            $tren = strtolower($k['tren']);

            // Step 1: Konversi ke skor
            $skor = konversiSkor($id_kriteria, $nilai);

            // Step 2: Normalisasi skor
            $max = $maxMin[$id_kriteria]['max'];
            $min = $maxMin[$id_kriteria]['min'];

            $normal = 0;
            if ($tren === 'positif' && $min != 0) {
                $normal = ($skor / $min) * 100;
            } elseif ($tren === 'negatif' && $skor != 0) {
                $normal = ($min / $skor) * 100;
            }

            // Step 3: Hasil akhir per kriteria
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
?>

