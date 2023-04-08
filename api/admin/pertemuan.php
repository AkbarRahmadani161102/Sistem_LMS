<?php
include_once '../util/db.php';

if (isset($_POST['sync'])) {
    $array_hari = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];
    $array_tgl = [
        [], // Senin
        [], // Selasa
        [], // Rabu
        [], // Kamis
        [], // Jumat
        [], // Sabtu
        [], // Minggu
    ];

    $month = date('n');
    $year = date('Y');

    // Menghitung jumlah hari dalam bulan yang dipilih
    $num_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    // Mengulang sebanyak jumlah hari dalam bulan
    for ($day = 1; $day <= $num_days; $day++) {

        // Mendapatkan data tanggal dengan fungsi mktime()
        $date = mktime(0, 0, 0, $month, $day, $year);
        $date_number = date('N', $date);
        $date_string = date('Y-m-d', $date);

        // Menambahkan tanggal pada array sesuai urutan harinya
        array_push($array_tgl[$date_number - 1], $date_string);
    }

    foreach ($array_hari as $index_hari => $hari) {
        $sql = "SELECT * FROM jadwal WHERE id_jadwal NOT IN (SELECT id_jadwal FROM detail_jadwal) AND id_instruktur IS NOT NULL AND hari = '$hari'";
        $result = $db->query($sql) or die($db->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id_jadwal = $row['id_jadwal'];
                $id_instruktur = $row['id_instruktur'];

                foreach ($array_tgl[$index_hari] as $tgl_pertemuan) {
                    $sql = "INSERT INTO detail_jadwal (id_jadwal, id_instruktur, tgl_pertemuan) VALUES ('$id_jadwal', '$id_instruktur', '$tgl_pertemuan')";
                    $db->query($sql) or die($db->error);
                }

                $msg = "Pertemuan bulan $month tahun $tahun telah ditambahkan, silahkan konfirmasi kehadiran anda";

                $sql = "SELECT COUNT(*) jumlah_notifikasi FROM notifikasi_instruktur WHERE deskripsi = '$msg' AND MONTH(tgl_dibuat) = $month AND id_instruktur = '$id_instruktur'";
                $data_notifikasi = $db->query($sql) or die($db->error);
                $data_notifikasi = $data_notifikasi->fetch_assoc();
                if ($data_notifikasi['jumlah_notifikasi'] <= 0) {
                    $sql = "INSERT INTO notifikasi_instruktur (id_instruktur, deskripsi) VALUES('$id_instruktur', '$msg')";
                    $db->query($sql) or die($db->error);
                }
            }
        }
    }

    $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Data berhasil disinkronkan', 'icon_color' => 'greenlight'];
}
if (isset($_POST['reassign_instruktur'])) {
    $id_detail_jadwal = $_POST['reassign_instruktur'];
    $id_instruktur = $_POST['instruktur'];
    $tgl_pertemuan = $_POST['tgl_pertemuan'];
    $jam_mulai = $_POST['jam_mulai'];

    $sql = "SELECT * FROM jadwal j 
    JOIN detail_jadwal dj ON j.id_jadwal = dj.id_jadwal
    WHERE jam_mulai = '$jam_mulai'
    AND tgl_pertemuan = '$tgl_pertemuan'
    AND dj.id_instruktur = '$id_instruktur'";
    $data_instruktur = $db->query($sql) or die($db->error);
    $data_instruktur->fetch_assoc();
    $is_instructor_exist = $data_instruktur->num_rows > 0;

    if (!$is_instructor_exist) {
        $sql = "UPDATE detail_jadwal SET id_instruktur = '$id_instruktur' WHERE id_detail_jadwal = '$id_detail_jadwal'";
        $db->query($sql) or die($db->error);
        $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Instruktur berhasil ditetapkan', 'icon_color' => 'greenlight'];
    } else {
        $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Gagal menetapkan instruktur', 'icon_color' => 'red', 'text' => 'Instruktur yang bersangkutan sudah ada di jadwal lain di hari dan jam yang sama'];
    }
}
redirect('../../client/admin/pertemuan.php');
