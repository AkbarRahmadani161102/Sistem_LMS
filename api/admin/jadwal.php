<?php
include_once '../util/db.php';

if (isset($_POST['create'])) {
    $id_mapel = escape($_POST['mapel']);
    $id_kelas = escape($_POST['kelas']);
    $hari = escape($_POST['hari']);
    $jam_mulai = escape($_POST['jam_mulai']);
    $jam_selesai = escape($_POST['jam_selesai']);

    $sql = "SELECT * FROM jadwal WHERE id_kelas = '$id_kelas' AND hari = '$hari' AND jam_mulai = '$jam_mulai'";
    $data_jadwal = $db->query($sql);
    if ($data_jadwal->num_rows === 0) {
        if ($jam_mulai < $jam_selesai) {
            $sql = "INSERT INTO jadwal (id_mapel, id_kelas, hari, jam_mulai, jam_selesai) VALUES ('$id_mapel', '$id_kelas', '$hari', '$jam_mulai', '$jam_selesai')";
            $db->query($sql);
            push_toast('Data jadwal berhasil ditambah', 'success', 'Pastikan untuk melakukan update pertemuan untuk menetapkan pertemuan');
            $id_jadwal = $db->insert_id;
            redirect("../../client/admin/jadwal.php?assign_instruktur=$id_jadwal");
        } else
            push_toast('Gagal menambah', 'error', 'Pastikan waktu jam mulai kurang dari jam selesai');
    } else
        push_toast('Gagal menambah', 'error', 'Jadwal pada hari dan waktu yang sama telah ada');
}

if (isset($_POST['assign_instruktur'])) {
    $id_jadwal = escape($_POST['assign_instruktur']);
    $id_instruktur = escape($_POST['instruktur']);
    $sql = "SELECT * FROM jadwal WHERE id_jadwal = '$id_jadwal'";
    $data_jadwal = $db->query($sql);
    $data_jadwal = $data_jadwal->fetch_assoc();

    $sql = "SELECT * FROM jadwal WHERE id_instruktur = '$id_instruktur'";
    $data_instruktur = $db->query($sql);
    $data_instruktur = $data_instruktur->fetch_assoc();

    // Mengecek apabila instruktur memiliki jadwal di hari dan jam yang sama
    $jadwal_hari = $data_jadwal['hari'];
    $jadwal_jam_mulai = $data_jadwal['jam_mulai'];
    $instruktur_hari = isset($data_instruktur['hari']) ? $data_instruktur['hari'] : '';
    $instruktur_jam_mulai = isset($data_instruktur['jam_mulai']) ? $data_instruktur['jam_mulai'] : '';

    if ($jadwal_hari !== $instruktur_hari || $jadwal_jam_mulai !== $instruktur_jam_mulai) {
        $sql = "UPDATE jadwal SET id_instruktur = '$id_instruktur' WHERE id_jadwal = '$id_jadwal'";
        $db->query($sql);
        push_toast('Instruktur berhasil ditetapkan', 'success', 'Pastikan untuk melakukan update pertemuan untuk menetapkan pertemuan');
    } else {
        push_toast('Gagal menetapkan instruktur', 'error', 'Instruktur yang bersangkutan sudah ada di jadwal lain, di hari dan jam yang sama');
    }
    redirect("../../client/admin/jadwal.php");
}

if (isset($_POST['update'])) {
    $id_jadwal = escape($_POST['update']);
    $id_mapel = escape($_POST['mapel']);
    $id_kelas = escape($_POST['kelas']);
    $hari = escape($_POST['hari']);
    $jam_mulai = escape($_POST['jam_mulai']);
    $jam_selesai = escape($_POST['jam_selesai']);

    if ($jam_mulai < $jam_selesai) {
        $sql = "UPDATE jadwal SET id_mapel = '$id_mapel', id_kelas = '$id_kelas', hari =  '$hari', jam_mulai = '$jam_mulai', jam_selesai = '$jam_selesai' WHERE id_jadwal = '$id_jadwal'";
        $db->query($sql);
        push_toast('Data jadwal berhasil diubah', 'success', 'Pastikan untuk melakukan update pertemuan untuk menetapkan pertemuan');
        redirect("../../client/admin/jadwal.php");
    } else {
        push_toast('Gagal mengubah', 'error', 'Pastikan waktu jam mulai kurang dari jam selesai');
    }
}

if (isset($_POST['delete'])) {
    try {
        $id_jadwal = escape($_POST['delete']);
        $sql = "DELETE FROM jadwal WHERE id_jadwal = '$id_jadwal'";
        $db->query($sql);
        push_toast('Data jadwal berhasil dihapus');

        if ($db->error) {
            push_toast('Gagal menghapus', 'error', 'Constraint integrity error');
        }
    } catch (\Throwable $th) {
        push_toast('Gagal menghapus', 'error', 'Constraint integrity error');
    }
}

?>

<script>
    history.back()
</script>