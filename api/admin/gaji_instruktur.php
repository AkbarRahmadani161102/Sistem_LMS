<?php
include_once '../util/db.php';
if (isset($_POST['sync'])) {
    $month = date('m');

    $sql = "SELECT * FROM gaji WHERE MONTH(tgl_dibuat) = $month LIMIT 1";
    $data_per_bulan = $db->query($sql) or die($db->error);

    if ($data_per_bulan->num_rows > 0) {
        $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Gagal menghapus', 'icon_color' => 'red', 'text' => 'Data gaji pada bulan ini telah ada'];
    } else {
        $sql = "SELECT dj.id_instruktur, SUM(biaya_per_pertemuan) nominal FROM detail_jadwal dj
        JOIN jadwal j ON dj.id_jadwal = j.id_jadwal
        JOIN kelas k ON j.id_kelas = j.id_kelas
        JOIN jenjang je ON k.id_jenjang = je.id_jenjang
        AND dj.status_kehadiran_instruktur = 'Hadir'
        WHERE MONTH(tgl_pertemuan) = $month
        GROUP BY dj.id_instruktur";

        $data_instruktur = $db->query($sql) or die($db->error);

        foreach ($data_instruktur as $value) {
            $id_instruktur = $value['id_instruktur'];
            $nominal = $value['nominal'];
            $sql = "INSERT INTO gaji (id_instruktur, nominal) VALUES ('$id_instruktur', '$nominal')";
            $db->query($sql) or die($db->error);
        }
        $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Data berhasil disinkronkan', 'icon_color' => 'greenlight'];
    }
}
if (isset($_POST['update'])) {
    $id_gaji = $_POST['update'];
    $tgl_penerimaan = $_POST['tgl_penerimaan'];

    $sql = "UPDATE gaji SET tgl_penerimaan = '$tgl_penerimaan' WHERE id_gaji = '$id_gaji'";
    $db->query($sql) or die($db->error);
    $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Data berhasil diubah', 'icon_color' => 'greenlight'];
}

redirect('../../client/admin/gaji_instruktur.php');