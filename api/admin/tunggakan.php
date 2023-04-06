<?php
include_once "../util/db.php";

if (isset($_POST['sync'])) {
    $bulan_entity = date('n');
    $bulan = date('m');
    $hari = 10;
    $tahun = date('Y');

    $tanggal_trigger = "$tahun-$bulan-$hari";
    "<br>";
    $tenggat = date('Y-m-t', strtotime($tanggal_trigger));

    $sql = "SELECT s.id_siswa, j.nama nama_jenjang, j.biaya_pendidikan FROM siswa s
    JOIN detail_kelas dk ON s.id_siswa = dk.id_siswa
    JOIN kelas k ON dk.id_kelas = k.id_kelas
    JOIN jenjang j ON k.id_jenjang = j.id_jenjang AND s.id_siswa NOT IN (SELECT id_siswa FROM tunggakan WHERE MONTH(tenggat_pembayaran) = '$bulan_entity')";

    $data_siswa = $db->query($sql) or die($db->error);
    $data_siswa->fetch_assoc();
    foreach ($data_siswa as $siswa) {
        $biaya_pendidikan =  $siswa['biaya_pendidikan'];
        $id_siswa = $siswa['id_siswa'];
        $sql = "INSERT INTO tunggakan (id_siswa, tenggat_pembayaran, nominal, tgl_dibuat)
        VALUES('$id_siswa', '$tenggat', '$biaya_pendidikan', '$tenggat')";
        $db->query($sql) or die($db->error);
    }
}

if (isset($_POST['update'])) {
    $status = $_POST['status'];
    $id_tunggakan = $_POST['update'];
    $tgl_pembayaran = $_POST['tgl_pembayaran'];

    if ($status === 'Lunas') {
        $sql = "UPDATE tunggakan SET status = '$status', tgl_pembayaran = '$tgl_pembayaran' WHERE id_tunggakan = '$id_tunggakan'";
    }

    $db->query($sql) or die($db->error);
    $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Data berhasil diubah', 'icon_color' => 'greenlight'];
}
$_SESSION['toast'] = ['icon' => 'success', 'title' => 'Data berhasil disinkronkan', 'icon_color' => 'greenlight'];

redirect('../../client/admin/tunggakan.php');
