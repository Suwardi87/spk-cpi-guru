<?php
function hitungCPI($conn, $id_karyawan) {
    $sql = "SELECT k.bobot, p.nilai 
            FROM penilaian p 
            JOIN kriteria k ON p.id_kriteria = k.id 
            WHERE p.id_karyawan = $id_karyawan";
    $result = mysqli_query($conn, $sql);
    
    $cpi = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $cpi += $row['bobot'] * $row['nilai'];
    }

    return $cpi;
}
?>
