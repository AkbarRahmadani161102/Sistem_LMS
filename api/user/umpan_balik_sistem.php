<?php
include_once '../util/db.php';

$role = $_SESSION['role'];
$nama = $_SESSION['nama'];

if (isset($_POST['create'])) {
    $tipe_user;
    if ($role === 'siswa')
        $tipe_user = 1;
    else if ($role = 'instruktur')
        $tipe_user = 2;
    else if ($role = 'admin')
        $tipe_user = 3;
    else
        $tipe_user = 1;
        
    $deskripsi = $_POST['deskripsi'];
    $sql = "INSERT INTO kuesioner_app (nama, tipe_user, deskripsi) VALUES('$nama', '$tipe_user','$deskripsi')";

    $db->query($sql) or die($db->error);
    redirect("../../client/$role/index.php");
}
