<?php
require_once '../util/db.php';
header('Content-Type: application/json');

if (isset($_GET['pertumbuhan_siswa'])) {
    $tahun = $_GET['pertumbuhan_siswa'];
    $sql = "SELECT COUNT(*) jumlah_siswa, tgl_dibuat, MONTH(tgl_dibuat) bulan, YEAR(tgl_dibuat) tahun, MONTHNAME(tgl_dibuat) nama_bulan FROM siswa WHERE YEAR(tgl_dibuat) = $tahun  GROUP BY bulan ORDER BY tgl_dibuat";
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
    $tahun = $_GET['pertumbuhan_instruktur'];
    $sql = "SELECT COUNT(*) jumlah_instruktur, tgl_dibuat, MONTH(tgl_dibuat) bulan, YEAR(tgl_dibuat) tahun, MONTHNAME(tgl_dibuat) nama_bulan FROM instruktur WHERE YEAR(tgl_dibuat) = $tahun  GROUP BY bulan ORDER BY tgl_dibuat";
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
