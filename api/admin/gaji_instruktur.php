<?php
include_once '../util/db.php';
if (isset($_POST['sync'])) {
    $tahun = $_POST['tahun'];
    $bulan = $_POST['bulan'];
    $hari = 10;
    $tanggal_trigger = "$tahun-$bulan-$hari";
    $tgl_dibuat = date('Y-m-t', strtotime($tanggal_trigger));

    $sql = "SELECT dj.id_instruktur, SUM(biaya_per_pertemuan) nominal FROM detail_jadwal dj
    JOIN jadwal j ON dj.id_jadwal = j.id_jadwal
    JOIN kelas k ON j.id_kelas = j.id_kelas
    JOIN jenjang je ON k.id_jenjang = je.id_jenjang
    WHERE MONTH(tgl_pertemuan) = $bulan AND YEAR(tgl_pertemuan) = $tahun
    AND dj.status_kehadiran_instruktur = 'Hadir'
    AND dj.id_instruktur NOT IN (SELECT id_instruktur FROM gaji WHERE MONTH(tgl_dibuat) = $bulan AND YEAR(tgl_dibuat) = $tahun)
    GROUP BY dj.id_instruktur";

    $data_instruktur = $db->query($sql);

    foreach ($data_instruktur as $value) {
        $id_instruktur = $value['id_instruktur'];
        $nominal = $value['nominal'];
        $sql = "INSERT INTO gaji (id_instruktur, nominal, tgl_dibuat) VALUES ('$id_instruktur', '$nominal', '$tgl_dibuat')";
        $db->query($sql);
    }
    $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Data berhasil disinkronkan', 'icon_color' => 'greenlight'];
}
if (isset($_POST['update'])) {
    $id_gaji = $_POST['update'];
    $tgl_penerimaan = $_POST['tgl_penerimaan'];

    if ($tgl_penerimaan === 'NULL')
        $sql = "UPDATE gaji SET tgl_penerimaan = NULL WHERE id_gaji = '$id_gaji'";
    else
        $sql = "UPDATE gaji SET tgl_penerimaan = '$tgl_penerimaan' WHERE id_gaji = '$id_gaji'";

    $db->query($sql);
    $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Data berhasil diubah', 'icon_color' => 'greenlight'];
}
if (isset($_POST['redirect'])) {
    $tahun = $_POST['tahun'];
    $bulan = $_POST['bulan'];

    redirect("../../client/admin/gaji_instruktur.php?tahun=$tahun&bulan=$bulan");
}
?>

<script>
    history.back()
</script>