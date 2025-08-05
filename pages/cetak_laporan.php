<?php
require_once '../assets/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

include '../config/db.php';
include '../functions/cpi.php';

$dompdf = new Dompdf();
$guru = getGuru($conn);
$kriteria = getKriteria($conn);
$penilaian = getPenilaian($conn);
$cpiHasil = hitungCPI($penilaian, $kriteria);

usort($guru, function($a, $b) use ($cpiHasil) {
    return $cpiHasil[$b['id']]['total'] <=> $cpiHasil[$a['id']]['total'];
});

$html = '
<style>
    body { font-family: Arial, sans-serif; font-size: 12px; }
    h3, h4 { text-align: center; margin: 5px 0; }
    table { border-collapse: collapse; width: 100%; margin-top: 10px; }
    table, th, td { border: 1px solid black; }
    th { background-color: #f2f2f2; }
    td, th { padding: 5px; text-align: center; }
    .header-sekolah { text-align: center; margin-bottom: 10px; }
    .footer { margin-top: 40px; text-align: right; }
</style>

<div class="header-sekolah">
    <h2>SEKOLAH MENENGAH PERTAMA NEGERI 5 KUBUNG</h2>
    <p>Jl. Lintas Sumatera KM.3, Saok Laweh, Kec. Kubung, Kabupaten Solok, Sumatera Barat</p>
    <hr>
</div>

<h3>LAPORAN PENILAIAN KINERJA GURU BERDASARKAN CPI</h3>
<h4>5 Guru dengan CPI Tertinggi</h4>

<table>
<thead>
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

$html .= '<h4>Guru Lainnya</h4>
<table>
<thead>
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

// Footer: Tanggal & Tanda Tangan
$tanggal = date('d F Y');
$html .= "
<div class='footer'>
    <p>Kota Edukasi, {$tanggal}</p>
    <p>Kepala Sekolah</p>
    <br><br><br>
    <p><u>Drs. Ahmad Kepala, M.Pd</u><br>NIP: 19650410 199001 1 001</p>
</div>
";

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("laporan-guru-cpi.pdf", ["Attachment" => 0]);
?>
