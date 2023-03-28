<?php
require '../../vendor/autoload.php';
include_once '../util/db.php';

use Ramsey\Uuid\Uuid;

if (isset($_POST['create'])) {
    $id_siswa = Uuid::uuid4()->toString();
    $nama = escape($_POST['nama']);
    $no_telp = escape($_POST['no_telp']);
    $alamat = escape($_POST['alamat']);
    $email = escape($_POST['email']);
    $password = md5(escape($_POST['password']));
    $id_kelas = escape($_POST['kelas']);

    $contain_letter = preg_match("/[a-z][A-Z]/", $no_telp) === 1;

    function is_email_available()
    {
        global $db, $email;
        $sql = "SELECT COUNT(*) used_email FROM siswa WHERE email = '$email'";
        $used_email = $db->query($sql) or die($db->error);
        $used_email = $used_email->fetch_assoc();
        return $used_email['used_email'] === '0';
    }

    if (!$contain_letter) {
        if (is_email_available()) {
            $sql = "INSERT INTO siswa (id_siswa, nama, no_telp, alamat, email, password) VALUES ('$id_siswa', '$nama', '$no_telp', '$alamat', '$email' , '$password')";
            $db->query($sql) or die($db->error);

            $sql = "INSERT INTO detail_kelas (id_kelas, id_siswa) VALUES ('$id_kelas', '$id_siswa')";
            $db->query($sql) or die($db->error);
        }
        // Email Sudah Ada
    }
    // Password Mengandung Karakter

}
if (isset($_POST['update'])) {
    $id_siswa = $_POST['update'];
    $nama = escape($_POST['nama']);
    $no_telp = escape($_POST['no_telp']);
    $alamat = escape($_POST['alamat']);
    $email = escape($_POST['email']);
    $password = escape($_POST['password']);
    $kelas = isset($_POST['kelas']) ? $_POST['kelas'] : [];
    $contain_letter = preg_match("/[a-z][A-Z]/", $no_telp) === 1;

    function is_email_changed()
    {
        global $db, $email;
        $sql = "SELECT COUNT(*) used_email FROM siswa WHERE email = '$email'";
        $used_email = $db->query($sql) or die($db->error);
        $used_email = $used_email->fetch_assoc();
        return $used_email['used_email'] === '0';
    }

    function is_password_changed()
    {
        global $db, $password;
        $sql = "SELECT COUNT(*) used_password FROM siswa WHERE password = '$password'";
        $used_password = $db->query($sql) or die($db->error);
        $used_password = $used_password->fetch_assoc();
        return $used_password['used_password'] === '0';
    }

    if (!$contain_letter) {
        $ext_sql = '';

        if (is_email_changed()) {
            $ext_sql .= ", email = '$email'";
        }
        if (is_password_changed()) {
            $password = md5($password);
            $ext_sql .= ", password = '$password'";
        }

        $sql = "UPDATE siswa SET nama = '$nama', no_telp = '$no_telp', alamat = '$alamat' $ext_sql WHERE id_siswa = '$id_siswa'";
        $db->query($sql) or die($db->error);

        $sql = "DELETE FROM detail_kelas WHERE id_siswa = '$id_siswa'";
        $db->query($sql) or die($db->error);
        
        if (count($kelas) > 0) {
            foreach ($kelas as $key => $id_kelas) {
                $sql = "INSERT INTO detail_kelas (id_kelas, id_siswa) VALUES('$id_kelas', '$id_siswa')";
                $db->query($sql) or die($db->error);
            }
        }
    }
}
if (isset($_POST['delete'])) {
    $id_siswa = escape($_POST['delete']);
    if ($_SESSION['user_id'] !== $id_siswa) {
        $sql = "DELETE FROM detail_penilaian WHERE id_siswa = '$id_siswa'";
        $db->query($sql) or die($db->error);
        
        $sql = "DELETE FROM detail_kelas WHERE id_siswa = '$id_siswa'";
        $db->query($sql) or die($db->error);

        $sql = "DELETE FROM siswa WHERE id_siswa = '$id_siswa'";
        $db->query($sql) or die($db->error);
    }
}

redirect("../../client/admin/siswa.php");
