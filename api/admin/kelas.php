<?php
include_once '../util/db.php';

if (isset($_POST['create'])) {
    $jenjang = escape($_POST['jenjang']);
    $nama_kelas = escape($_POST['nama_kelas']);
    $status = escape($_POST['status_kelas']);

    $sql = "SELECT * FROM kelas WHERE nama = '$nama_kelas'";
    $data_kelas = $db->query($sql);

    if ($data_kelas->num_rows > 0) {
        push_toast('Gagal menambahkan kelas', 'error', 'Kelas dengan nama yang sama telah ada');
        redirect('../../client/admin/kelas.php');
    }

    if (isset($_POST['ketua_kelas']) && isset($_POST['anggota_kelas'])) {
        if (in_array($_POST['ketua_kelas'], $_POST['anggota_kelas'])) {
            $id_ketua_kelas = $_POST['ketua_kelas'];
            $sql = "INSERT INTO kelas (id_jenjang, nama, status, id_ketua_kelas) VALUES('$jenjang', '$nama_kelas', '$status', '$id_ketua_kelas')";
        } else {
            push_toast('Gagal menambahkan kelas', 'error', 'Pastikan ketua kelas dipilih menjadi anggota kelas');
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

    push_toast('Kelas berhasil ditambahkan');
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
            push_toast('Gagal menambahkan kelas', 'error', 'Pastikan ketua kelas dipilih menjadi anggota kelas');
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

    push_toast('Kelas berhasil diubah');
}

if (isset($_POST['delete'])) {
    $id_kelas = escape($_POST['delete']);
    function delete_jadwal()
    {
        global $db, $id_kelas;
        $sql = "SELECT id_jadwal FROM jadwal WHERE id_kelas = $id_kelas";
        $data_jadwal = $db->query($sql);

        // Jadwal
        foreach ($data_jadwal as $jadwal) {

            $sql = "SELECT id_detail_jadwal FROM detail_jadwal WHERE id_jadwal = {$jadwal['id_jadwal']}";

            $data_detail_jadwal = $db->query($sql);

            // Detail Jadwal
            foreach ($data_detail_jadwal as $detail_jadwal) {
                // Absensi Siswa
                $sql = "DELETE FROM absensi_siswa WHERE id_detail_jadwal = {$detail_jadwal['id_detail_jadwal']}";
                $db->query($sql);

                // Penilaian
                $sql = "SELECT id_penilaian FROM penilaian WHERE id_detail_jadwal = {$detail_jadwal['id_detail_jadwal']}";
                $data_penilaian = $db->query($sql);

                foreach ($data_penilaian as $penilaian) {
                    $sql = "DELETE FROM detail_penilaian WHERE id_penilaian = {$penilaian['id_penilaian']}";
                    $db->query($sql);
                }

                $sql = "DELETE FROM penilaian WHERE id_detail_jadwal = {$detail_jadwal['id_detail_jadwal']}";
                $db->query($sql);
            }

            $sql = "DELETE FROM detail_jadwal WHERE id_jadwal = {$jadwal['id_jadwal']}";
            $db->query($sql);
        }
        $sql = "DELETE FROM jadwal WHERE id_kelas = '$id_kelas'";
        $db->query($sql);
    }
    try {
        delete_jadwal();
        $sql = "DELETE FROM detail_kelas WHERE id_kelas = '$id_kelas'";
        $db->query($sql);
        $sql = "DELETE FROM kelas WHERE id_kelas = '$id_kelas'";
        $db->query($sql);
        push_toast('Kelas berhasil dihapus');
    } catch (\Throwable $th) {
        push_toast('Gagal menghapus', 'error', 'Constraint integrity error');
    }
}

redirect('../../client/admin/kelas.php');
