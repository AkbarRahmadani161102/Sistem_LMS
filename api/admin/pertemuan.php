<?php
include_once '../util/db.php';

$sql = "SELECT * FROM jadwal";
$data_jadwal = $db->query($sql) or die($db->error);
$data_jadwal->fetch_assoc();
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

$sql = "SELECT COUNT(*) FROM detail_jadwal WHERE MONTH(tgl_pertemuan) = '$month'";
$result = $db->query($sql) or die($db->error);
if ($result->fetch_array()[0] <= 0) {
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
        $sql = "SELECT * FROM jadwal WHERE hari = '$hari'";
        $result = $db->query($sql) or die($db->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id_jadwal = $row['id_jadwal'];
                $id_instruktur = $row['id_instruktur'];
                foreach ($array_tgl[$index_hari] as $tgl_pertemuan) {
                    $sql = "INSERT INTO detail_jadwal (id_jadwal, id_instruktur, tgl_pertemuan) VALUES ('$id_jadwal', '$id_instruktur', '$tgl_pertemuan')";
                    $db->query($sql) or die($db->error);
                }
            }
        }
    }
} else {
    echo "Data bulan ini sudah ada";
}

redirect('../../client/admin/pertemuan.php');



// print_r($tgl_senin);
// echo "<br>";
// print_r($tgl_selasa);
// echo "<br>";
// print_r($tgl_rabu);
// echo "<br>";
// print_r($tgl_kamis);
// echo "<br>";
// print_r($tgl_jumat);
// echo "<br>";
// print_r($tgl_sabtu);
// echo "<br>";
