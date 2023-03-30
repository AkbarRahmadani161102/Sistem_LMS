<?php

include_once '../util/db.php';

if (isset($_POST['create'])) {
    $nama_mapel = escape($_POST['nama_mapel']);
    $jenjang = escape($_POST['jenjang']);
    $sql = "INSERT INTO mapel (nama, id_jenjang) VALUES('$nama_mapel', '$jenjang')";
    $db->query($sql) or die($db->error);
}
if (isset($_POST['update'])) {
    $id_mapel = escape($_POST['update']);
    $nama_mapel = escape($_POST['nama_mapel']);
    $jenjang = escape($_POST['jenjang']);
    $sql = "UPDATE mapel SET nama = '$nama_mapel', id_jenjang = '$jenjang' WHERE id_mapel = $id_mapel";
    $db->query($sql) or die($db->error);
}
if(isset($_POST['delete'])) {
    $id_mapel = escape($_POST['delete']);
    $sql = "DELETE FROM mapel WHERE id_mapel = '$id_mapel'";
    $db->query($sql) or die($db->error);

    $sql = "DELETE FROM detail_mapel WHERE id_mapel = '$id_mapel'";
    $db->query($sql) or die($db->error);

    $sql = "DELETE FROM jadwal WHERE id_mapel = '$id_mapel'";
    $db->query($sql) or die($db->error);
}
redirect('../../client/admin/mapel.php');