<?php

include_once '../util/db.php';

if (isset($_POST['create'])) {
    $id_siswa = $_SESSION['user_id'];
    $keterangan = escape($_POST['keterangan']);
    $judul = escape($_POST['judul']);

    $sql = "INSERT INTO pengajuan (id_siswa, judul, keterangan) VALUES('$id_siswa', '$judul', '$keterangan')";
    $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Pengajuan baru berhasil diunggah', 'icon_color' => 'greenlight'];
    $db->query($sql);
} else if (isset($_POST['delete'])) {
    try {
        $id_pengajuan = escape($_POST['delete']);
        $sql = "DELETE FROM pengajuan WHERE id_pengajuan = '$id_pengajuan'";
        $db->query($sql);
        $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Data pengajuan berhasil dihapus', 'icon_color' => 'greenlight'];
    } catch (\Throwable $th) {
        $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Gagal menghapus', 'icon_color' => 'red', 'text' => 'Constraint integrity error'];
    }
}
redirect('../../client/siswa/pengajuan.php');
