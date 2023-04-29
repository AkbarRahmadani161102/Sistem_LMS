<?php
include_once '../util/db.php';

function sync_gaji_instruktur()
{
    global $db;

    $current_date = new DateTime();
    $current_date->modify('-1 month');
    $month = $current_date->format('m');
    $sql = "SELECT dj.id_instruktur, SUM(biaya_per_pertemuan) nominal FROM detail_jadwal dj
    JOIN jadwal j ON dj.id_jadwal = j.id_jadwal
    JOIN kelas k ON j.id_kelas = j.id_kelas
    JOIN jenjang je ON k.id_jenjang = je.id_jenjang
    WHERE MONTH(tgl_pertemuan) = $month
    AND dj.status_kehadiran_instruktur = 'Hadir'
    AND dj.id_instruktur NOT IN (SELECT id_instruktur FROM gaji WHERE MONTH(tgl_pertemuan) = $month)
    GROUP BY dj.id_instruktur";

    $data_instruktur = $db->query($sql);

    foreach ($data_instruktur as $value) {
        $id_instruktur = $value['id_instruktur'];
        $nominal = $value['nominal'];
        $sql = "INSERT INTO gaji (id_instruktur, nominal) VALUES ('$id_instruktur', '$nominal')";
        $db->query($sql);
    }
}

function sync_pertemuan()
{
    global $db;

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

    foreach (HARI as $index_hari => $hari) {
        $sql = "SELECT * FROM jadwal WHERE id_jadwal NOT IN (SELECT id_jadwal FROM detail_jadwal) AND id_instruktur IS NOT NULL AND hari = '$hari' ORDER BY jam_mulai";
        $result = $db->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id_jadwal = $row['id_jadwal'];
                $id_instruktur = $row['id_instruktur'];

                foreach ($array_tgl[$index_hari] as $tgl_pertemuan) {
                    $sql = "INSERT INTO detail_jadwal (id_jadwal, id_instruktur, tgl_pertemuan) VALUES ('$id_jadwal', '$id_instruktur', '$tgl_pertemuan')";
                    $db->query($sql);
                }

                $msg = "Pertemuan bulan $month tahun $year telah ditambahkan, silahkan konfirmasi kehadiran anda";

                $sql = "SELECT COUNT(*) jumlah_notifikasi FROM notifikasi_instruktur WHERE deskripsi = '$msg' AND MONTH(tgl_dibuat) = $month AND id_instruktur = '$id_instruktur'";
                $data_notifikasi = $db->query($sql);
                $data_notifikasi = $data_notifikasi->fetch_assoc();
                if ($data_notifikasi['jumlah_notifikasi'] <= 0) {
                    $sql = "INSERT INTO notifikasi_instruktur (id_instruktur, deskripsi) VALUES('$id_instruktur', '$msg')";
                    $db->query($sql);
                }
            }
        }
    }
}

function sync_tunggakan()
{
    global $db;
    $bulan_entity = date('n');
    $bulan = date('m');
    $hari = 10;
    $tahun = date('Y');

    $tanggal_trigger = "$tahun-$bulan-$hari";
    $tenggat = date('Y-m-t', strtotime($tanggal_trigger));

    $sql = "SELECT s.id_siswa, j.nama nama_jenjang, j.biaya_pendidikan FROM siswa s
    JOIN detail_kelas dk ON s.id_siswa = dk.id_siswa
    JOIN kelas k ON dk.id_kelas = k.id_kelas
    JOIN jenjang j ON k.id_jenjang = j.id_jenjang AND s.id_siswa NOT IN (SELECT id_siswa FROM tunggakan WHERE MONTH(tenggat_pembayaran) = '$bulan_entity')";

    $data_siswa = $db->query($sql);
    $data_siswa->fetch_assoc();
    foreach ($data_siswa as $siswa) {
        $biaya_pendidikan =  $siswa['biaya_pendidikan'];
        $id_siswa = $siswa['id_siswa'];
        $sql = "INSERT INTO tunggakan (id_siswa, tenggat_pembayaran, nominal, tgl_dibuat)
        VALUES('$id_siswa', '$tenggat', '$biaya_pendidikan', '$tenggat')";
        $db->query($sql);
    }
}

sync_gaji_instruktur();
sync_pertemuan();
sync_tunggakan();