<?php
include_once '../util/db.php';

if (isset($_POST['create'])) {
    $jenjang = escape($_POST['jenjang']);
    $nama_kelas = escape($_POST['nama_kelas']);
    $status = escape($_POST['status_kelas']);

    $sql = "SELECT * FROM kelas WHERE nama = '$nama_kelas'";
    $data_kelas = $db->query($sql);

    if ($data_kelas->num_rows > 0) {
        $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Gagal menambahkan kelas', 'icon_color' => 'greenlight', 'text' => 'Kelas dengan nama yang sama telah ada'];
        redirect('../../client/admin/kelas.php');
    }

    if (isset($_POST['ketua_kelas']) && isset($_POST['anggota_kelas'])) {
        if (in_array($_POST['ketua_kelas'], $_POST['anggota_kelas'])) {
            $id_ketua_kelas = $_POST['ketua_kelas'];
            $sql = "INSERT INTO kelas (id_jenjang, nama, status, id_ketua_kelas) VALUES('$jenjang', '$nama_kelas', '$status', '$id_ketua_kelas')";
        } else {
            $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Gagal menambahkan kelas', 'icon_color' => 'greenlight', 'text' => 'Pastikan ketua kelas dipilih menjadi anggota kelas'];
            redirect('../../client/admin/kelas.php');
        }
    } else {
        $sql = "INSERT INTO kelas (id_jenjang, nama, status) VALUES('$jenjang', '$nama_kelas', '$status')";
    }

    $db->query($sql);
    $id_kelas = $db->insert_id;

    if (isset($_POST['anggota_kelas'])) {
        $anggota_kelas = $_POST['anggota_kelas'];
        foreach ($anggota_kelas as $id_siswa) {
            $sql = "INSERT INTO detail_kelas (id_kelas, id_siswa) VALUES('$id_kelas', '$id_siswa')";
            $db->query($sql);
        }
    }

    $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Kelas berhasil ditambahkan', 'icon_color' => 'greenlight'];
}

if (isset($_POST['update'])) {
    $id_kelas = escape($_POST['update']);
    $jenjang = escape($_POST['jenjang']);
    $nama_kelas = escape($_POST['nama_kelas']);
    $status = escape($_POST['status_kelas']);

    if (isset($_POST['ketua_kelas']) && isset($_POST['anggota_kelas'])) {
        if (in_array($_POST['ketua_kelas'], $_POST['anggota_kelas'])) {
            $id_ketua_kelas = $_POST['ketua_kelas'];
            $sql = "UPDATE kelas SET id_jenjang = '$jenjang', nama = '$nama_kelas', status = '$status', id_ketua_kelas = '$id_ketua_kelas' WHERE id_kelas = '$id_kelas'";
        } else {
            $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Gagal menambahkan kelas', 'icon_color' => 'greenlight', 'text' => 'Pastikan ketua kelas dipilih menjadi anggota kelas'];
            redirect('../../client/admin/kelas.php');
        }
    } else {
        $sql = "UPDATE kelas SET id_jenjang = '$jenjang', nama = '$nama_kelas', status = '$status' WHERE id_kelas = '$id_kelas'";
    }

    $db->query($sql);

    if (isset($_POST['anggota_kelas'])) {
        $anggota_kelas = $_POST['anggota_kelas'];

        $sql = "DELETE FROM detail_kelas WHERE id_kelas = '$id_kelas'";
        $db->query($sql);

        foreach ($anggota_kelas as $id_siswa) {
            $sql = "INSERT INTO detail_kelas (id_kelas, id_siswa) VALUES('$id_kelas', '$id_siswa')";
            $db->query($sql);
        }
    }

    $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Kelas berhasil diubah', 'icon_color' => 'greenlight'];
}

if (isset($_POST['delete'])) {
    try {
        $id_kelas = escape($_POST['delete']);
        $sql = "DELETE FROM detail_kelas WHERE id_kelas = '$id_kelas'";
        $db->query($sql);
        $sql = "DELETE FROM kelas WHERE id_kelas = '$id_kelas'";
        $db->query($sql);
        $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Kelas berhasil dihapus', 'icon_color' => 'greenlight'];
    } catch (\Throwable $th) {
        $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Gagal menghapus', 'icon_color' => 'red', 'text' => 'Constraint integrity error'];
    }
}

redirect('../../client/admin/kelas.php');
