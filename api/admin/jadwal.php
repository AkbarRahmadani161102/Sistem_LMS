<?php
include_once '../util/db.php';

if (isset($_POST['create'])) {
    $id_mapel = $_POST['mapel'];
    $id_kelas = $_POST['kelas'];
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    if ($jam_mulai < $jam_selesai) {
        $sql = "INSERT INTO jadwal (id_mapel, id_kelas, hari, jam_mulai, jam_selesai) VALUES ('$id_mapel', '$id_kelas', '$hari', '$jam_mulai', '$jam_selesai')";
        $db->query($sql) or die($db->error);
    }

}

if (isset($_POST['update'])) {
    $id_jadwal = $_POST['update'];
    $id_mapel = $_POST['mapel'];
    $id_kelas = $_POST['kelas'];
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    if ($jam_mulai < $jam_selesai) {
        $sql = "UPDATE jadwal SET id_mapel = '$id_mapel', id_kelas = '$id_kelas', hari =  '$hari', jam_mulai = '$jam_mulai', jam_selesai = '$jam_selesai' WHERE id_jadwal = '$id_jadwal'";
        $db->query($sql) or die($db->error);
    }
}

if (isset($_POST['delete'])) {
    $id_jadwal = $_POST['delete'];
    $sql = "DELETE FROM jadwal WHERE id_jadwal = '$id_jadwal'";
    $db->query($sql) or die($db->error);
}
redirect('../../client/admin/jadwal.php');
