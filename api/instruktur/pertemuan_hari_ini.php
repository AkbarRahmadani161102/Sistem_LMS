<?php

include_once '../util/db.php';

if(isset($_POST['presence'])) {
    $id_detail_jadwal = $_POST['presence'];
    $data_presensi = $_POST['kehadiran'];
    foreach ($data_presensi as $id_siswa => $status) {
        $sql = "INSERT INTO absensi_siswa (id_detail_jadwal, id_siswa, status) VALUES('$id_detail_jadwal', $id_siswa, '$status')";
        $db->query($sql) or die($db->error);
        $sql = "UPDATE detail_jadwal SET status_kehadiran_instruktur = 'Hadir' WHERE id_detail_jadwal = '$id_detail_jadwal'";
        $db->query($sql) or die($db->error);
    }
    push_toast('Berhasil melakukan absensi');
}
redirect('../../client/instruktur/pertemuan_hari_ini.php');
