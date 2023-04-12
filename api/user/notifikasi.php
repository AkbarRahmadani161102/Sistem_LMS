<?php

include_once '../util/db.php';

$id_user = $_SESSION['user_id'];
$role = $_SESSION['role'];

if (isset($_POST['change_status'])) {
    $id_notifikasi = $_POST['id_notifikasi'];
    $status = $_POST['change_status'];
    $sql = "UPDATE notifikasi_$role SET status = '$status' WHERE id_notifikasi_$role = '$id_notifikasi'";
    $db->query($sql) or die($db->error);
}
if (isset($_POST['delete'])) {
    $id_notifikasi = $_POST['delete'];
    $sql = "DELETE FROM notifikasi_$role WHERE id_notifikasi_$role = '$id_notifikasi'";
    $db->query($sql) or die($db->error);
    $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Data notifikasi berhasil dihapus', 'icon_color' => 'greenlight'];
    redirect("../../client/user/notifikasi.php");
}

redirect("../../client/$role/index.php");
