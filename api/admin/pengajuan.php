<?php

include_once '../util/db.php';

if (isset($_POST['update'])) {
    $status = $_POST['status'];
    $id_pengajuan = $_POST['update'];
    $sql = "UPDATE pengajuan SET status = '$status' WHERE id_pengajuan = '$id_pengajuan'";
    $db->query($sql) or die($db->error);
    $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Status pengajuan berhasil diubah', 'icon_color' => 'greenlight'];
}

redirect('../../client/admin/pengajuan.php');
