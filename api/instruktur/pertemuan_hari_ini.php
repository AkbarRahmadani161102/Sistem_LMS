<?php

include_once '../util/db.php';
// $id_instruktur = $_SESSION['user_id'];
// $sql = "SELECT * FROM jadwal j, detail_jadwal dk WHERE j.id_jadwal = dk.id_jadwal AND j.id_instruktur = '$id_instruktur' GROUP BY tgl_pertemuan";
// $data_pertemuan = $db->query($sql) or die($db->error);

// if (isset($_GET['presence'])) {
//     $id_detail_jadwal = $_GET['presence'];
//     $sql = "SELECT s.* FROM detail_jadwal dj
//     JOIN jadwal j ON dj.id_jadwal = j.id_jadwal
//     JOIN kelas k ON j.id_kelas = k.id_kelas
//     JOIN detail_kelas dk ON dk.id_kelas = k.id_kelas
//     JOIN siswa s ON dk.id_siswa = s.id_siswa
//     WHERE id_detail_jadwal = '$id_detail_jadwal'";
//     $data_siswa = $db->query($sql) or die($db->error);
//     $data_siswa->fetch_assoc();
// }

if(isset($_POST['presence'])) {
    $id_detail_jadwal = $_POST['presence'];
    $data_presensi = $_POST['kehadiran'];
    foreach ($data_presensi as $id_siswa => $status) {
        $sql = "INSERT INTO absensi_siswa (id_detail_jadwal, id_siswa, status) VALUES('$id_detail_jadwal', $id_siswa, '$status')";
        // echo $sql;
        $db->query($sql) or die($db->error);
    }
}
redirect('../../client/instruktur/pertemuan_hari_ini.php');
?>