<?php

include_once '../util/db.php';

$id_user = $_SESSION['user_id'];
$role = $_SESSION['role'];

if(isset($_POST['change_status'])) {
    $id_notifikasi = $_POST['id_notifikasi'];
    $status = $_POST['change_status'];
    $sql = "UPDATE notifikasi_$role SET status = '$status' WHERE id_notifikasi_$role = '$id_notifikasi'";
    $db->query($sql) or die($db->error);
}

redirect("../../client/$role/index.php");
