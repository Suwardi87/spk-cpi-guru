<?php
require_once '../assets/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

include '../config/db.php';
include '../functions/cpi.php';

$dompdf = new Dompdf();

// Ambil data guru, kriteria, dan penilaian
$guru = getGuru($conn);
$kriteria = getKriteria($conn);
$penilaian = getPenilaian($conn);

// Hitung CPI
$cpiHasil = hitungCPI($penilaian, $kriteria);

// Urutkan berdasarkan total CPI tertinggi
usort($guru, function($a, $b) use ($cpiHasil) {
    return $cpiHasil[$b['id']]['total'] <=> $cpiHasil[$a['id']]['total'];
});

// Buat tampilan HTML untuk PDF
$html = '<h3 style="text-align:center;">Laporan Guru Berdasarkan CPI</h3>';

$html .= '<h4>5 Guru dengan CPI Tertinggi</h4>';
$html .= '<table border="1" cellpadding="5" cellspacing="0" width="100%">';
$html .= '<thead>
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>Jabatan</th>';

foreach ($kriteria as $k) {
    $html .= "<th>{$k['nama_kriteria']}</th>";
}

$html .= '<th>Total CPI</th><th>Status</th></tr></thead><tbody>';

$no = 1;
foreach (array_slice($guru, 0, 5) as $g) {
    $id = $g['id'];
    $status = $cpiHasil[$id]['total'] >= 80 ? 'Layak Reward' : 'Belum Layak';

    $html .= "<tr>
                <td>{$no}</td>
                <td>{$g['nama']}</td>
                <td>{$g['jabatan']}</td>";

    foreach ($kriteria as $k) {
        $nilai = $cpiHasil[$id]['kalkulasi'][$k['id']]['hasil'] ?? '-';
        $html .= "<td>" . (is_numeric($nilai) ? number_format($nilai, 2) : '-') . "</td>";
    }

    $html .= "<td>" . number_format($cpiHasil[$id]['total'], 2) . "</td>
              <td>{$status}</td>
            </tr>";
    $no++;
}

$html .= '</tbody></table>';

// Tampilkan sisa guru
$html .= '<h4>Guru Lainnya</h4>';
$html .= '<table border="1" cellpadding="5" cellspacing="0" width="100%">';
$html .= '<thead>
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>Jabatan</th>';

foreach ($kriteria as $k) {
    $html .= "<th>{$k['nama_kriteria']}</th>";
}

$html .= '<th>Total CPI</th><th>Status</th></tr></thead><tbody>';

foreach (array_slice($guru, 5) as $g) {
    $id = $g['id'];
    $status = $cpiHasil[$id]['total'] >= 80 ? 'Layak Reward' : 'Belum Layak';

    $html .= "<tr>
                <td>{$no}</td>
                <td>{$g['nama']}</td>
                <td>{$g['jabatan']}</td>";

    foreach ($kriteria as $k) {
        $nilai = $cpiHasil[$id]['kalkulasi'][$k['id']]['hasil'] ?? '-';
        $html .= "<td>" . (is_numeric($nilai) ? number_format($nilai, 2) : '-') . "</td>";
    }

    $html .= "<td>" . number_format($cpiHasil[$id]['total'], 2) . "</td>
              <td>{$status}</td>
            </tr>";
    $no++;
}

$html .= '</tbody></table>';

// Cetak PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("laporan-guru-cpi.pdf", ["Attachment" => 0]);
?>

