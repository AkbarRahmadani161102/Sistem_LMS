<?php
include_once '../util/db.php';

if (isset($_POST['create'])) {
    $nama = escape($_POST['nama']);
    $biaya_pendidikan = escape($_POST['biaya_pendidikan']);
    $biaya_per_pertemuan = escape($_POST['biaya_per_pertemuan']);
    $sql = "INSERT INTO jenjang (nama, biaya_pendidikan, biaya_per_pertemuan) VALUES ('$nama', '$biaya_pendidikan', '$biaya_per_pertemuan')";
    $db->query($sql);
    push_toast('Jenjang baru berhasil ditambahkan');
}
if (isset($_POST['update'])) {
    $id_jenjang = escape($_POST['update']);
    $nama = escape($_POST['nama']);
    $biaya_pendidikan = escape($_POST['biaya_pendidikan']);
    $biaya_per_pertemuan = escape($_POST['biaya_per_pertemuan']);
    $sql = "UPDATE jenjang SET nama = '$nama', biaya_pendidikan = '$biaya_pendidikan', biaya_per_pertemuan = '$biaya_per_pertemuan' WHERE id_jenjang = '$id_jenjang'";
    $db->query($sql);
    push_toast('Data jenjang berhasil diubah');
}
if (isset($_POST['delete'])) {
    try {
        $id_jenjang = escape($_POST['delete']);
        $sql = "DELETE FROM jenjang WHERE id_jenjang = '$id_jenjang'";
        $db->query($sql);
        push_toast('Data jenjang berhasil dihapus');
    } catch (\Throwable $th) {
        push_toast('Gagal menghapus', 'error', 'Constraint integrity error');
    }
}
redirect('../../client/admin/jenjang.php');
