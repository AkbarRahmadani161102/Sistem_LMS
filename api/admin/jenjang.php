<?php
include_once '../util/db.php';

if (isset($_POST['create'])) {
    $nama = $_POST['nama'];
    $biaya_pendidikan = $_POST['biaya_pendidikan'];
    $sql = "INSERT INTO jenjang (nama, biaya_pendidikan) VALUES ('$nama', '$biaya_pendidikan')";
    $db->query($sql) or die($db->error);
    $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Jenjang baru berhasil ditambahkan', 'icon_color' => 'greenlight'];
}
if (isset($_POST['update'])) {
    $id_jenjang = $_POST['update'];
    $nama = $_POST['nama'];
    $biaya_pendidikan = $_POST['biaya_pendidikan'];
    $sql = "UPDATE jenjang SET nama = '$nama', biaya_pendidikan = '$biaya_pendidikan' WHERE id_jenjang = '$id_jenjang'";
    $db->query($sql) or die($db->error);
    $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Data jenjang berhasil diubah', 'icon_color' => 'greenlight'];
}
if (isset($_POST['delete'])) {
    try {
        $id_jenjang = $_POST['delete'];
        $sql = "DELETE FROM jenjang WHERE id_jenjang = '$id_jenjang'";
        $db->query($sql) or die($db->error);
        $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Data jenjang berhasil dihapus', 'icon_color' => 'greenlight'];
    } catch (\Throwable $th) {
        $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Gagal menghapus', 'icon_color' => 'red', 'text' => 'Constraint integrity error'];
    }
}
redirect('../../client/admin/jenjang.php');
