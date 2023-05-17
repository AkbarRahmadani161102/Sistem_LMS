<?php

include_once '../util/db.php';

if (isset($_POST['ajukan_instruktur'])) {
    $data_instruktur = explode('|', $_POST['instruktur']);
    $id_detail_jadwal = escape($_POST['ajukan_instruktur']);
    $id_siswa = escape($_POST['id_siswa']);
    $id_instruktur = escape($data_instruktur[0]);
    $nama_instruktur =  escape($data_instruktur[1]);
    $kelas = escape($_POST['kelas']);
    $tgl_pertemuan = escape($_POST['tgl_pertemuan']);
    $mapel = escape($_POST['mapel']);
    $judul = "Pergantian Instruktur";
    $keterangan = "Instruktur pengganti yang diminta: $nama_instruktur \nKelas: $kelas \nMapel: $mapel \nTanggal: $tgl_pertemuan";
    $sql = "INSERT INTO pengajuan (id_siswa, judul, keterangan, id_detail_jadwal, id_instruktur) VALUES('$id_siswa', '$judul', '$keterangan', '$id_detail_jadwal', '$id_instruktur')";
    $db->query($sql) or die($db->error);

    $sql = "UPDATE detail_jadwal SET status_kehadiran_instruktur = 'Proses Pergantian' WHERE id_detail_jadwal = '$id_detail_jadwal'";
    $db->query($sql) or die($db->error);

    $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Pengajuan ditambahkan', 'icon_color' => 'greenlight'];
}
redirect('../../client/siswa/pertemuan.php');
