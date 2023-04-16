<?php

include_once '../util/db.php';

if (isset($_POST['update'])) {
    $status = $_POST['status'];
    $id_pengajuan = $_POST['update'];

    $sql = "SELECT * FROM pengajuan WHERE id_pengajuan = '$id_pengajuan'";
    $data_pengajuan = $db->query($sql)->fetch_assoc() or die($db->error);
    if($id_detail_jadwal = $data_pengajuan['id_detail_jadwal']) {
        $sql = "UPDATE detail_jadwal SET status_kehadiran_instruktur = NULL WHERE id_detail_jadwal = '$id_detail_jadwal'";
        $db->query($sql) or die($db->error);
    }

    $sql = "UPDATE pengajuan SET status = '$status', id_detail_jadwal = NULL WHERE id_pengajuan = '$id_pengajuan'";
    $db->query($sql) or die($db->error);
    $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Status pengajuan berhasil diubah', 'icon_color' => 'greenlight'];
}

redirect('../../client/admin/pengajuan.php');
