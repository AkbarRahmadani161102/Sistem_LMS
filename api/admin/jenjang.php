<?php
include_once '../util/db.php';

if (isset($_POST['create'])) {
    $nama = escape($_POST['nama']);
    $biaya_pendidikan = escape($_POST['biaya_pendidikan']);
    $biaya_per_pertemuan = escape($_POST['biaya_per_pertemuan']);
    $sql = "INSERT INTO jenjang (nama, biaya_pendidikan, biaya_per_pertemuan) VALUES ('$nama', '$biaya_pendidikan', '$biaya_per_pertemuan')";
    $db->query($sql);
    $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Jenjang baru berhasil ditambahkan', 'icon_color' => 'greenlight'];
}
if (isset($_POST['update'])) {
    $id_jenjang = escape($_POST['update']);
    $nama = escape($_POST['nama']);
    $biaya_pendidikan = escape($_POST['biaya_pendidikan']);
    $biaya_per_pertemuan = escape($_POST['biaya_per_pertemuan']);
    $sql = "UPDATE jenjang SET nama = '$nama', biaya_pendidikan = '$biaya_pendidikan', biaya_per_pertemuan = '$biaya_per_pertemuan' WHERE id_jenjang = '$id_jenjang'";
    $db->query($sql);
    $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Data jenjang berhasil diubah', 'icon_color' => 'greenlight'];
}
if (isset($_POST['delete'])) {
    try {
        $id_jenjang = escape($_POST['delete']);
        $sql = "DELETE FROM jenjang WHERE id_jenjang = '$id_jenjang'";
        $db->query($sql);
        $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Data jenjang berhasil dihapus', 'icon_color' => 'greenlight'];
    } catch (\Throwable $th) {
        $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Gagal menghapus', 'icon_color' => 'red', 'text' => 'Constraint integrity error'];
    }
}
redirect('../../client/admin/jenjang.php');
