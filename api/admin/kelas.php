<?php
include_once '../util/db.php';

if (isset($_POST['create'])) {
    $jenjang = $_POST['jenjang'];
    $nama_kelas = $_POST['nama_kelas'];
    $status = $_POST['status'];
    $sql = "INSERT INTO kelas (id_jenjang, nama, status) VALUES('$jenjang', '$nama_kelas', '$status')";
    $db->query($sql) or die($db->error);
}

if (isset($_POST['update'])) {
    $id_kelas = $_POST['update'];
    $jenjang = $_POST['jenjang'];
    $nama_kelas = $_POST['nama_kelas'];
    $status = $_POST['status_kelas'];
    $sql = "UPDATE kelas SET id_jenjang = '$jenjang', nama = '$nama_kelas', status = '$status' WHERE id_kelas = '$id_kelas'";
    $db->query($sql) or die($db->error);

    if (isset($_POST['ketua_kelas'])) {
        $ketua_kelas = $_POST['ketua_kelas'];
        $sql = "UPDATE kelas SET id_ketua_kelas = '$ketua_kelas' WHERE id_kelas = '$id_kelas'";
        $db->query($sql) or die($db->error);
    }
}

if (isset($_POST['delete'])) {
    $id_kelas = $_POST['delete'];
    $sql = "DELETE FROM kelas WHERE id_kelas = '$id_kelas'";
    echo $sql;
    $db->query($sql) or die($db->error);
}

redirect('../../client/admin/kelas.php');
