<?php

include_once '../util/db.php';

if (isset($_POST['create'])) {
    $id_siswa = $_SESSION['user_id'];
    $keterangan = escape($_POST['keterangan']);
    $judul = escape($_POST['judul']);

    $sql = "INSERT INTO pengajuan (id_siswa, judul, keterangan) VALUES('$id_siswa', '$judul', '$keterangan')";
    push_toast('Pengajuan baru berhasil diunggah');
    $db->query($sql);
} else if (isset($_POST['delete'])) {
    try {
        $id_pengajuan = escape($_POST['delete']);
        $sql = "DELETE FROM pengajuan WHERE id_pengajuan = '$id_pengajuan'";
        $db->query($sql);
        push_toast('Data pengajuan berhasil dihapus');
    } catch (\Throwable $th) {
        push_toast('Gagal menghapus', 'error', 'Constraint integrity error');
    }
}
redirect('../../client/siswa/pengajuan.php');
