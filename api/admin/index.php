<?php
require_once '../util/db.php';
header('Content-Type: application/json');

if (isset($_GET['pertumbuhan_siswa'])) {
    $sql = "SELECT COUNT(*) jumlah_siswa, tgl_dibuat, MONTH(tgl_dibuat) bulan, MONTHNAME(tgl_dibuat) nama_bulan FROM siswa GROUP BY bulan ORDER BY tgl_dibuat";
    $result = $db->query($sql) or die($sql);
    $result->fetch_assoc();
    $arr = [];
    foreach ($result as $key => $value) {
        $arr[] = [
            'jumlah_siswa' => $value['jumlah_siswa'],
            'tgl_dibuat' => $value['tgl_dibuat'],
            'bulan' => $value['nama_bulan'],
        ];
    }

    echo json_encode($arr);
}

if (isset($_GET['pertumbuhan_instruktur'])) {
    $sql = "SELECT COUNT(*) jumlah_instruktur, tgl_dibuat, MONTH(tgl_dibuat) bulan, MONTHNAME(tgl_dibuat) nama_bulan FROM instruktur GROUP BY bulan ORDER BY tgl_dibuat";
    $result = $db->query($sql) or die($sql);
    $result->fetch_assoc();
    $arr = [];
    foreach ($result as $key => $value) {
        $arr[] = [
            'jumlah_instruktur' => $value['jumlah_instruktur'],
            'tgl_dibuat' => $value['tgl_dibuat'],
            'bulan' => $value['nama_bulan'],
        ];
    }

    echo json_encode($arr);
}
