<?php
include_once "../util/db.php";

if (isset($_POST['sync'])) {
    $bulan = $_POST['bulan'];
    $hari = 10;
    $tahun = $_POST['tahun'];

    $tanggal_trigger = "$tahun-$bulan-$hari";
    $tenggat = date('Y-m-t', strtotime($tanggal_trigger));

    $sql = "SELECT s.id_siswa, j.nama nama_jenjang, j.biaya_pendidikan FROM siswa s
    JOIN detail_kelas dk ON s.id_siswa = dk.id_siswa
    JOIN kelas k ON dk.id_kelas = k.id_kelas
    JOIN jenjang j ON k.id_jenjang = j.id_jenjang AND s.id_siswa NOT IN (SELECT id_siswa FROM tunggakan WHERE MONTH(tgl_dibuat) = '$bulan' AND YEAR(tgl_dibuat) = '$tahun')";

    $data_siswa = $db->query($sql);
    $data_siswa->fetch_assoc();
    foreach ($data_siswa as $siswa) {
        $biaya_pendidikan =  $siswa['biaya_pendidikan'];
        $id_siswa = $siswa['id_siswa'];
        $nama_bulan = BULAN[$bulan - 1];
        $sql = "INSERT INTO tunggakan (id_siswa, tenggat_pembayaran, nominal, tgl_dibuat)
        VALUES('$id_siswa', '$tenggat', '$biaya_pendidikan', '$tanggal_trigger')";
        $db->query($sql);

        $msg = "Tunggakan bulan $nama_bulan tahun $tahun telah diperbarui, silahkan lakukan pembayaran";
        $sql = "INSERT INTO notifikasi_siswa (id_siswa, deskripsi) VALUES('$id_siswa', '$msg')";
        $db->query($sql);
    }

    push_toast('Data berhasil disinkronkan');
}

if (isset($_POST['update'])) {
    $status = $_POST['status'];
    $id_tunggakan = $_POST['update'];
    $tgl_pembayaran = $_POST['tgl_pembayaran'];

    if ($status === 'Lunas') {
        $sql = "UPDATE tunggakan SET status = '$status', tgl_pembayaran = '$tgl_pembayaran' WHERE id_tunggakan = '$id_tunggakan'";
        $db->query($sql);
    } else if ($status === 'LunasKeseluruhan') {
        $id_siswa = $_POST['update'];
        $sql = "SELECT id_tunggakan FROM tunggakan WHERE id_siswa = '$id_siswa' AND status IS NULL";
        $data_tunggakan = $db->query($sql);

        foreach ($data_tunggakan as $tunggakan) {
            $id_tunggakan = $tunggakan['id_tunggakan'];
            $sql = "UPDATE tunggakan SET status = 'Lunas', tgl_pembayaran = '$tgl_pembayaran' WHERE id_tunggakan = '$id_tunggakan'";
            $db->query($sql);
        }
    } else if ($status === 'Reset') {
        $sql = "UPDATE tunggakan SET status = NULL, tgl_pembayaran = '$tgl_pembayaran' WHERE id_tunggakan = '$id_tunggakan'";
        $db->query($sql);
    }

    push_toast('Data berhasil diubah');
}

if (isset($_POST['redirect'])) {
    $tahun = $_POST['tahun'];
    $bulan = $_POST['bulan'];

    redirect("../../client/admin/tunggakan.php?tahun=$tahun&bulan=$bulan");
}

?>
<script>
    history.back()
</script>