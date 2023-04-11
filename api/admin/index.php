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

if (isset($_GET['presensi_siswa_per_hari'])) {
    $sql = "SELECT COUNT(*) jumlah_siswa, status FROM (SELECT dj.tgl_pertemuan, id_siswa, status FROM absensi_siswa a
            JOIN detail_jadwal dj ON a.id_detail_jadwal = dj.id_detail_jadwal
            WHERE dj.tgl_pertemuan = CURRENT_DATE()
            GROUP BY id_siswa) grouped_absensi 
            GROUP BY status";

    $result = $db->query($sql) or die($db->error);
    $result->fetch_assoc();

    $arr = [];
    foreach ($result as $value) {
        $arr[] = [
            $value['status'] => $value['jumlah_siswa']
        ];
    }
    echo json_encode($arr);
}

if (isset($_GET['presensi_instruktur_per_hari'])) {
    $sql = "SELECT COUNT(*) jumlah_instruktur, status_kehadiran_instruktur as status
    FROM (SELECT IF(status_kehadiran_instruktur IS NULL, 'Berhalangan', status_kehadiran_instruktur) status_kehadiran_instruktur FROM detail_jadwal WHERE tgl_pertemuan = CURRENT_DATE() GROUP BY id_instruktur) grouped_instruktur
    GROUP BY status_kehadiran_instruktur";

    $result = $db->query($sql) or die($db->error);
    $result->fetch_assoc();

    $arr = [];
    foreach ($result as $value) {
        $arr[] = [
            $value['status'] => $value['jumlah_instruktur']
        ];
    }
    echo json_encode($arr);
}
