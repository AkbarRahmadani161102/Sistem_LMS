<?php
include_once '../util/db.php';

if (isset($_POST['create'])) {
    $id_detail_jadwal = $_POST['create'];
    $judul_penilaian = $_POST['judul_penilaian'];
    $keterangan_penilaian = $_POST['keterangan_penilaian'];

    $sql = "INSERT INTO penilaian (id_detail_jadwal, judul_penilaian, keterangan_penilaian) VALUES('$id_detail_jadwal', '$judul_penilaian', '$keterangan_penilaian')";
    $db->query($sql) or die($db->error);

    $id_penilaian = $db->insert_id;

    foreach ($_POST['nilai_siswa'] as $id_siswa => $nilai_siswa) {
        $sql = "INSERT INTO detail_penilaian (id_penilaian, id_siswa, nilai) VALUES('$id_penilaian', '$id_siswa', '$nilai_siswa')";
        $db->query($sql) or die($db->error);
    }

    $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Data penilaian berhasil ditambahkan', 'icon_color' => 'greenlight'];
}
if (isset($_POST['reset'])) {
    $id_penilaian = $_POST['reset'];
    
    $sql = "DELETE FROM detail_penilaian WHERE id_penilaian = '$id_penilaian'";
    $db->query($sql) or die($db->error);
    $sql = "DELETE FROM penilaian WHERE id_penilaian = '$id_penilaian'";
    $db->query($sql) or die($db->error);

    $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Data penilaian berhasil direset', 'icon_color' => 'greenlight'];
}

redirect('../../client/instruktur/penilaian.php');
