<?php
include_once '../util/db.php';

if(isset($_POST['create'])) {
$jenjang = $_POST['jenjang'];
$nama_kelas = $_POST['nama_kelas'];
$status = $_POST['status'];
$sql = "INSERT INTO kelas (id_jenjang, nama, status) VALUES('$jenjang', '$nama_kelas', '$status')";
$db->query($sql) or die($db->error);
}

redirect('../../client/admin/kelas.php');