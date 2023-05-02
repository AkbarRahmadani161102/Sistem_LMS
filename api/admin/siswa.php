<?php
require '../../vendor/autoload.php';
include_once '../util/db.php';

use Ramsey\Uuid\Uuid;
$current_date = date('Y-m-d');

if (isset($_POST['create'])) {
    $id_siswa = Uuid::uuid4()->toString();
    $nama = escape($_POST['nama']);
    $no_telp = escape($_POST['no_telp']);
    $alamat = escape($_POST['alamat']);
    $email = escape($_POST['email']);
    $password = md5(escape($_POST['password']));

    $is_number = preg_match("/^[0-9]*$/", $no_telp) === 1;

    function is_email_available()
    {
        global $db, $email;
        $sql = "SELECT COUNT(*) used_email FROM siswa WHERE email = '$email'";
        $used_email = $db->query($sql);
        $used_email = $used_email->fetch_assoc();
        return $used_email['used_email'] === '0';
    }

    if ($is_number) {
        if (is_email_available()) {
            $sql = "INSERT INTO siswa (id_siswa, nama, no_telp, alamat, email, password) VALUES ('$id_siswa', '$nama', '$no_telp', '$alamat', '$email' , '$password')";
            $db->query($sql);
            $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Data siswa berhasil ditambahkan', 'icon_color' => 'greenlight'];
        } else {
            $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Gagal menambah', 'icon_color' => 'red', 'text' => 'Email sudah ada'];
        }
    } else {
        $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Gagal menambah', 'icon_color' => 'red', 'text' => 'Password mengandung karakter'];
    }
}
if (isset($_POST['update'])) {
    $id_siswa = $_POST['update'];
    $nama = escape($_POST['nama']);
    $no_telp = escape($_POST['no_telp']);
    $alamat = escape($_POST['alamat']);
    $email = escape($_POST['email']);
    $password = escape($_POST['password']);
    $status = escape($_POST['status']);
    $is_number = preg_match("/^[0-9]*$/", $no_telp) === 1;

    function is_email_changed()
    {
        global $db, $email;
        $sql = "SELECT COUNT(*) used_email FROM siswa WHERE email = '$email'";
        $used_email = $db->query($sql);
        $used_email = $used_email->fetch_assoc();
        return $used_email['used_email'] === '0';
    }

    function is_password_changed()
    {
        global $db, $password;
        $sql = "SELECT COUNT(*) used_password FROM siswa WHERE password = '$password'";
        $used_password = $db->query($sql);
        $used_password = $used_password->fetch_assoc();
        return $used_password['used_password'] === '0';
    }

    if ($is_number) {
        $ext_sql = '';

        if (is_email_changed()) {
            $ext_sql .= ", email = '$email'";
        }
        if (is_password_changed()) {
            $password = md5($password);
            $ext_sql .= ", password = '$password'";
        }

        $sql = "UPDATE siswa SET nama = '$nama', no_telp = '$no_telp', alamat = '$alamat', status = '$status', tgl_diubah = '$current_date' $ext_sql WHERE id_siswa = '$id_siswa'";
        $db->query($sql);

        $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Data siswa berhasil diubah', 'icon_color' => 'greenlight'];
    } else {
        $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Gagal mengubah', 'icon_color' => 'red', 'text' => 'Field nomor telp mengandung karakter'];
    }
}
if (isset($_POST['delete'])) {
    try {
        $id_siswa = escape($_POST['delete']);

        $sql = "DELETE FROM tunggakan WHERE id_siswa = '$id_siswa'";
        $db->query($sql);

        $sql = "DELETE FROM detail_penilaian WHERE id_siswa = '$id_siswa'";
        $db->query($sql);

        $sql = "DELETE FROM pengajuan WHERE id_siswa = '$id_siswa'";
        $db->query($sql);

        $sql = "DELETE FROM kuesioner_instruktur WHERE id_siswa = '$id_siswa'";
        $db->query($sql);

        $sql = "DELETE FROM notifikasi_siswa WHERE id_siswa = '$id_siswa'";
        $db->query($sql);

        $sql = "DELETE FROM detail_penilaian WHERE id_siswa = '$id_siswa'";
        $db->query($sql);

        $sql = "DELETE FROM absensi_siswa WHERE id_siswa = '$id_siswa'";
        $db->query($sql);

        $sql = "UPDATE kelas SET id_ketua_kelas = NULL WHERE id_ketua_kelas = '$id_siswa'";
        $db->query($sql);

        $sql = "DELETE FROM detail_kelas WHERE id_siswa = '$id_siswa'";
        $db->query($sql);

        $sql = "DELETE FROM siswa WHERE id_siswa = '$id_siswa'";
        $db->query($sql);

        $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Data siswa berhasil dihapus', 'icon_color' => 'greenlight'];

        if($db->error) {
            $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Gagal menghapus', 'icon_color' => 'red', 'text' => 'Constraint integrity error'];
        }
    } catch (\Throwable $th) {
        $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Gagal menghapus', 'icon_color' => 'red', 'text' => 'Constraint integrity error'];
    }
}

redirect("../../client/admin/siswa.php");
