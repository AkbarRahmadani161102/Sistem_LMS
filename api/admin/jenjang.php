<?php
include_once '../util/db.php';

if (isset($_POST['create'])) {
    $nama = $_POST['nama'];
    $sql = "INSERT INTO jenjang (nama) VALUES ('$nama')";
    $db->query($sql) or die($db->error);
    $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Jenjang baru berhasil ditambahkan', 'icon_color' => 'greenlight'];
}
if (isset($_POST['update'])) {
    $id_jenjang = $_POST['update'];
    $nama = $_POST['nama'];
    $sql = "UPDATE jenjang SET nama = '$nama' WHERE id_jenjang = '$id_jenjang'";
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
