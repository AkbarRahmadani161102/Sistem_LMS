<?php
include_once '../util/db.php';

if (isset($_POST['create'])) {
    $id_mapel = $_POST['mapel'];
    $id_kelas = $_POST['kelas'];
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    $sql = "SELECT * FROM jadwal WHERE id_kelas = '$id_kelas' AND hari = '$hari' AND jam_mulai = '$jam_mulai'";
    $data_jadwal = $db->query($sql) or die($db->error);
    if ($data_jadwal->num_rows === 0) {
        if ($jam_mulai < $jam_selesai) {
            $sql = "INSERT INTO jadwal (id_mapel, id_kelas, hari, jam_mulai, jam_selesai) VALUES ('$id_mapel', '$id_kelas', '$hari', '$jam_mulai', '$jam_selesai')";
            $db->query($sql) or die($db->error);
            $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Data jadwal berhasil ditambah', 'icon_color' => 'greenlight'];
            $id_jadwal = $db->insert_id;
            redirect("../../client/admin/jadwal.php?assign_instruktur=$id_jadwal");
        } else
            $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Gagal menambah', 'icon_color' => 'red', 'text' => 'Pastikan waktu jam mulai kurang dari jam selesai'];
    } else
        $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Gagal menambah', 'icon_color' => 'red', 'text' => 'Jadwal pada hari dan waktu yang sama telah ada'];
}

if (isset($_POST['assign_instruktur'])) {
    $id_jadwal = $_POST['assign_instruktur'];
    $id_instruktur = $_POST['instruktur'];
    $sql = "SELECT * FROM jadwal WHERE id_jadwal = '$id_jadwal'";
    $data_jadwal = $db->query($sql) or die($db->error);
    $data_jadwal = $data_jadwal->fetch_assoc();

    $sql = "SELECT * FROM jadwal WHERE id_instruktur = '$id_instruktur'";
    $data_instruktur = $db->query($sql) or die($db->error);
    $data_instruktur = $data_instruktur->fetch_assoc();

    // Mengecek apabila instruktur memiliki jadwal di hari dan jam yang sama
    $jadwal_hari = $data_jadwal['hari'];
    $jadwal_jam_mulai = $data_jadwal['jam_mulai'];
    $instruktur_hari = isset($data_instruktur['hari']) ? $data_instruktur['hari'] : '';
    $instruktur_jam_mulai = isset($data_instruktur['jam_mulai']) ? $data_instruktur['jam_mulai'] : '';

    if ($jadwal_hari !== $instruktur_hari || $jadwal_jam_mulai !== $instruktur_jam_mulai) {
        $sql = "UPDATE jadwal SET id_instruktur = '$id_instruktur' WHERE id_jadwal = '$id_jadwal'";
        $db->query($sql) or die($db->error);
        $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Instruktur berhasil ditetapkan', 'icon_color' => 'greenlight'];
    } else {
        $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Gagal menetapkan instruktur', 'icon_color' => 'red', 'text' => 'Instruktur yang bersangkutan sudah ada di jadwal lain di hari dan jam yang sama'];
    }
    redirect("../../client/admin/jadwal.php");
}

if (isset($_POST['update'])) {
    $id_jadwal = $_POST['update'];
    $id_mapel = $_POST['mapel'];
    $id_kelas = $_POST['kelas'];
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    if ($jam_mulai < $jam_selesai) {
        $sql = "UPDATE jadwal SET id_mapel = '$id_mapel', id_kelas = '$id_kelas', hari =  '$hari', jam_mulai = '$jam_mulai', jam_selesai = '$jam_selesai' WHERE id_jadwal = '$id_jadwal'";
        $db->query($sql) or die($db->error);
        $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Data jadwal berhasil diubah', 'icon_color' => 'greenlight'];
    } else {
        $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Gagal mengubah', 'icon_color' => 'red', 'text' => 'Pastikan waktu jam mulai kurang dari jam selesai'];
    }
}

if (isset($_POST['delete'])) {
    try {
        $id_jadwal = $_POST['delete'];
        $sql = "DELETE FROM jadwal WHERE id_jadwal = '$id_jadwal'";
        $db->query($sql) or die($db->error);
        $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Data jadwal berhasil dihapus', 'icon_color' => 'greenlight'];
    } catch (\Throwable $th) {
        $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Gagal menghapus', 'icon_color' => 'red', 'text' => 'Constraint integrity error'];
    }
}

?>

<script>
    history.back()
</script>