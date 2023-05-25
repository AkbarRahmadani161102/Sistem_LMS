<?php
include_once '../util/db.php';
if (isset($_POST['sync'])) {
    $tahun = escape($_POST['tahun']);
    $bulan = escape($_POST['bulan']);
    $hari = 10;
    $tanggal_trigger = "$tahun-$bulan-$hari";
    $tgl_dibuat = date('Y-m-t', strtotime($tanggal_trigger));

    $sql = "SELECT *, COUNT(dj.id_detail_jadwal) total_pertemuan, SUM(je.biaya_per_pertemuan) nominal FROM detail_jadwal dj
    JOIN instruktur i ON dj.id_instruktur = i.id_instruktur
    JOIN jadwal j ON dj.id_jadwal = j.id_jadwal
    JOIN kelas k ON j.id_kelas = k.id_kelas
    JOIN jenjang je ON k.id_jenjang = je.id_jenjang
    WHERE dj.status_kehadiran_instruktur = 'Hadir'
    AND MONTH(tgl_pertemuan) = $bulan AND YEAR(tgl_pertemuan) = $tahun
    AND dj.id_instruktur NOT IN (SELECT id_instruktur FROM gaji WHERE MONTH(tgl_dibuat) = $bulan AND YEAR(tgl_dibuat) = $tahun)
    GROUP BY i.id_instruktur";

    $data_instruktur = $db->query($sql);

    foreach ($data_instruktur as $value) {
        $id_instruktur = $value['id_instruktur'];
        $nominal = $value['nominal'];
        $sql = "INSERT INTO gaji (id_instruktur, nominal, tgl_dibuat) VALUES ('$id_instruktur', '$nominal', '$tgl_dibuat')";
        $db->query($sql);
    }

    push_toast('Data berhasil disinkronkan');
}
if (isset($_POST['update'])) {
    $id_gaji = escape($_POST['update']);
    $tgl_penerimaan = escape($_POST['tgl_penerimaan']);

    if ($tgl_penerimaan === 'NULL')
        $sql = "UPDATE gaji SET tgl_penerimaan = NULL WHERE id_gaji = '$id_gaji'";
    else
        $sql = "UPDATE gaji SET tgl_penerimaan = '$tgl_penerimaan' WHERE id_gaji = '$id_gaji'";

    $db->query($sql);
    push_toast('Data berhasil diubah');
}
if (isset($_POST['redirect'])) {
    $tahun = escape($_POST['tahun']);
    $bulan = escape($_POST['bulan']);

    redirect("../../client/admin/gaji_instruktur.php?tahun=$tahun&bulan=$bulan");
}
?>

<script>
    history.back()
</script>