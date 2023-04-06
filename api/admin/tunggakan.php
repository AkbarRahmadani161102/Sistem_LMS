<?php
include_once "../util/db.php";

if(isset($_POST['sync'])) {
    $sql = "SELECT s.nama nama_siswa, k.nama nama_kelas, j.nama nama_jenjang FROM siswa s
    JOIN detail_kelas dk ON s.id_siswa = dk.id_siswa
    JOIN kelas k ON dk.id_kelas = k.id_kelas
    JOIN jenjang j ON k.id_jenjang = j.id_jenjang";

    $data_siswa = $db->query($sql) or die($db->error);
    $data_siswa->fetch_assoc();

    $bulan = date('m');
    $hari = 10;
    $tahun = date('Y');

    echo $tanggal_trigger = "$tahun-$bulan-$hari";
    echo "<br>";
    echo $tenggat = date('Y-m-t', strtotime($tanggal_trigger));
    // $tenggat_bulan = $bulan + 3;

    // foreach ($data_siswa as $key => $siswa) {
    //     echo $siswa['nama_siswa'];
    //     echo $siswa['nama_kelas'];
    //     echo $siswa['nama_jenjang'];
    // }
}
?>