<?php
require_once '../assets/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

include '../config/db.php';
include '../functions/cpi.php';

$dompdf = new Dompdf();

$html = '<h3>Laporan Hasil CPI Karyawan</h3>';
$html .= '<table border="1" cellpadding="5" cellspacing="0" width="100%">';
$html .= '<tr>
            <th>No</th>
            <th>Nama</th>
            <th>Jabatan</th>
            <th>Nilai CPI</th>
            <th>Status</th>
          </tr>';

$q = mysqli_query($conn, "SELECT k.*, (SELECT SUM(p.nilai * k2.bobot) FROM penilaian p JOIN kriteria k2 ON p.id_kriteria = k2.id WHERE p.id_karyawan = k.id) AS cpi FROM karyawan k ORDER BY cpi DESC");

$no = 1;
while ($row = mysqli_fetch_assoc($q)) {
    $status = $row['cpi'] >= 80 ? "Layak Reward" : "Belum Layak";
    $html .= "<tr>
                <td>{$no}</td>
                <td>{$row['nama']}</td>
                <td>{$row['jabatan']}</td>
                <td>" . number_format($row['cpi'], 2) . "</td>
                <td>{$status}</td>
              </tr>";
    $no++;
}

$html .= '</table>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("laporan-cpi-karyawan.pdf", array("Attachment" => 0));
?>

