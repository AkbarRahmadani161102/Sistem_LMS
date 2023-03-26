<?php
include_once '../util/db.php';

if (isset($_POST['create'])) {
    $id_mapel = $_POST['mapel'];
    $id_kelas = $_POST['kelas'];
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    if ($jam_mulai !== $jam_selesai) {
        $sql = "INSERT INTO jadwal (id_mapel, id_kelas, hari, jam_mulai, jam_selesai) VALUES ('$id_mapel', '$id_kelas', '$hari', '$jam_mulai', '$jam_selesai')";
        $db->query($sql) or die($db->error);
    }
    redirect('../../client/admin/jadwal.php');
}
