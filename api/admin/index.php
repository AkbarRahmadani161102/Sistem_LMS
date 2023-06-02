<?php
require_once '../util/db.php';
header('Content-Type: application/json');

if (isset($_GET['pertumbuhan_finansial'])) {
    $tahun = $_GET['pertumbuhan_finansial'];
    $sql = "SELECT MONTH(tgl_perubahan) bulan, kredit, saldo FROM perubahan_saldo_kredit WHERE YEAR(tgl_perubahan) = $tahun";
    $result = $db->query($sql) or die($db->error);
    $result->fetch_assoc();
    $data_finansial = [];
    foreach ($result as $key => $value) {
        $data_finansial[] = [
            'bulan' => BULAN[$value['bulan'] - 1],
            'kredit' => $value['kredit'],
            'saldo' => $value['saldo'],
        ];
    }

    $sql = "SELECT MONTH(tgl_perubahan) bulan, AVG(kredit) rata_rata_kredit, AVG(saldo) rata_rata_saldo FROM perubahan_saldo_kredit WHERE YEAR(tgl_perubahan) = $tahun GROUP BY YEAR(tgl_perubahan)";
    $result = $db->query($sql) or die($db->error);
    $data_rata_rata_finansial = [];
    foreach ($result as $key => $value) {
        $data_rata_rata_finansial[] = [
            'kredit' => $value['rata_rata_kredit'],
            'saldo' => $value['rata_rata_saldo'],
        ];
    }
    echo json_encode(['finansial' => [...$data_finansial], 'rataRata' => [...$data_rata_rata_finansial]]);
}

if (isset($_GET['pertumbuhan_pendaftar'])) {
    $tahun = $_GET['pertumbuhan_pendaftar'];
    $sql = "SELECT COUNT(*) jumlah_siswa, tgl_dibuat, MONTH(tgl_dibuat) bulan, YEAR(tgl_dibuat) tahun, MONTHNAME(tgl_dibuat) nama_bulan FROM siswa WHERE YEAR(tgl_dibuat) = $tahun  GROUP BY bulan ORDER BY tgl_dibuat";
    $result = $db->query($sql) or die($sql);
    $result->fetch_assoc();
    $data_siswa = [];
    foreach ($result as $key => $value) {
        $data_siswa[] = [
            'jumlah_siswa' => $value['jumlah_siswa'],
            'tgl_dibuat' => $value['tgl_dibuat'],
            'bulan' => BULAN[$value['bulan'] - 1],
        ];
    }

    $sql = "SELECT COUNT(*) jumlah_instruktur, tgl_dibuat, MONTH(tgl_dibuat) bulan, YEAR(tgl_dibuat) tahun, MONTHNAME(tgl_dibuat) nama_bulan FROM instruktur WHERE YEAR(tgl_dibuat) = $tahun  GROUP BY bulan ORDER BY tgl_dibuat";
    $result = $db->query($sql) or die($sql);
    $result->fetch_assoc();
    $data_instruktur = [];
    foreach ($result as $key => $value) {
        $data_instruktur[] = [
            'jumlah_instruktur' => $value['jumlah_instruktur'],
            'tgl_dibuat' => $value['tgl_dibuat'],
            'bulan' => BULAN[$value['bulan'] - 1],
        ];
    }

    echo json_encode(['siswa' => [...$data_siswa], 'instruktur' => [...$data_instruktur]]);
}

if (isset($_GET['presensi_siswa_per_hari'])) {
    $sql = "SELECT COUNT(*) jumlah_siswa, status FROM (SELECT dj.tgl_pertemuan, id_siswa, status FROM absensi_siswa a
            JOIN detail_jadwal dj ON a.id_detail_jadwal = dj.id_detail_jadwal
            WHERE dj.tgl_pertemuan = CURRENT_DATE()
            GROUP BY id_siswa) grouped_absensi 
            GROUP BY status";

    $result = $db->query($sql);
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

    $result = $db->query($sql);
    $result->fetch_assoc();

    $arr = [];
    foreach ($result as $value) {
        $arr[] = [
            $value['status'] => $value['jumlah_instruktur']
        ];
    }
    echo json_encode($arr);
}
