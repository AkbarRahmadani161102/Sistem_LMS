<?php

include_once '../util/db.php';

$id_user = $_SESSION['user_id'];
$role = $_SESSION['role'];

if (isset($_POST['change_status'])) {
    $id_notifikasi = escape($_POST['id_notifikasi']);
    $status = escape($_POST['change_status']);
    $sql = "UPDATE notifikasi_$role SET status = '$status' WHERE id_notifikasi_$role = '$id_notifikasi'";
    $db->query($sql) or die($db->error);
}
if (isset($_POST['delete'])) {
    $id_notifikasi = escape($_POST['delete']);
    $sql = "DELETE FROM notifikasi_$role WHERE id_notifikasi_$role = '$id_notifikasi'";
    $db->query($sql) or die($db->error);
    push_toast('Data notifikasi berhasil dihapus');
    redirect("../../client/user/notifikasi.php");
}
if (isset($_POST['check_all'])) {
    $sql = "UPDATE notifikasi_$role SET status = 'Selesai' WHERE id_$role = '$id_user'";
    $db->query($sql) or die($db->error);
}

redirect("../../client/$role/index.php");
