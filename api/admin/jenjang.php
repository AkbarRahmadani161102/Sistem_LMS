<?php
include_once '../util/db.php';

if (isset($_POST['create'])) {
    $nama = $_POST['nama'];
    $sql = "INSERT INTO jenjang (nama) VALUES ('$nama')";
    $db->query($sql) or die($db->error);
}
if (isset($_POST['update'])) {
    $id_jenjang = $_POST['update'];
    $nama = $_POST['nama'];
    $sql = "UPDATE jenjang SET nama = '$nama' WHERE id_jenjang = '$id_jenjang'";
    $db->query($sql) or die($db->error);
}
if(isset($_POST['delete'])) {
    $id_jenjang = $_POST['delete'];
    $sql = "DELETE FROM jenjang WHERE id_jenjang = '$id_jenjang'";
    $db->query($sql) or die($db->error);
}
redirect('../../client/admin/jenjang.php');
