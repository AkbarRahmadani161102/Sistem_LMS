<?php
require '../../vendor/autoload.php';
include_once '../util/db.php';

use Ramsey\Uuid\Uuid;

if (isset($_POST['create'])) {

    $id_admin = Uuid::uuid4()->toString();
    $nama = escape($_POST['nama']);
    $no_telp = escape($_POST['no_telp']);
    $alamat = escape($_POST['alamat']);
    $email = escape($_POST['email']);
    $password = md5(escape($_POST['password']));
    
    $contain_letter = preg_match("/[a-z][A-Z]/", $no_telp) === 1;

    function is_email_available()
    {
        global $db, $email;
        $sql = "SELECT COUNT(*) used_email FROM admin WHERE email = '$email'";
        $used_email = $db->query($sql) or die($db->error);
        $used_email = $used_email->fetch_assoc();
        return $used_email['used_email'] === '0';
    }

    if (!$contain_letter) {
        if (is_email_available()) {
            $sql = "INSERT INTO admin (id_admin, nama, no_telp, alamat, email, password) VALUES ('$id_admin', '$nama', '$no_telp', '$alamat', '$email' , '$password')";
            $db->query($sql) or die($db->error);
            if(isset($_POST['hak_akses'])){
                $hak_akses = $_POST['hak_akses'];
                foreach ($hak_akses as $key => $value) {
                    $sql = "INSERT INTO detail_role (id_role, id_admin) VALUES('$value', '$id_admin')";
                    if ($value === '1') {
                        $db->query($sql) or die($db->error);
                        break;
                    }
                    $db->query($sql) or die($db->error);
                }
            } 
        }
        // Email Sudah Ada
    }
    // Password Mengandung Karakter

    redirect("../../client/admin/admin.php");
}
if (isset($_POST['update_profil'])) {
    $id_admin = escape($_POST['update_profil']);
    $nama = escape($_POST['nama']);
    $nomor_telepon = escape($_POST['no_telp']);
    $alamat = escape($_POST['alamat']);

    $contain_letter = preg_match("/[a-z][A-Z]/", $nomor_telepon) === 1;
    if (!$contain_letter) {
        $_SESSION['nama'] = $nama;
        $sql = "UPDATE admin SET nama = '$nama', no_telp = '$nomor_telepon', alamat = '$alamat'  WHERE id_admin = '$id_admin'";
        $db->query($sql) or die($db->error);
    }
    redirect("../../client/admin/admin.php?edit=$id_admin");
}
if (isset($_POST['update_kredensial'])) {
    $id_admin = escape($_POST['update_kredensial']);
    $email = escape($_POST['email']);
    $password = escape($_POST['password']);
    $confirm_password = escape($_POST['confirm_password']);

    if ($password === $confirm_password) {
        $sql = "SELECT password FROM admin WHERE id_admin = '$id_admin'";
        echo $sql;
        $db_password = $db->query($sql)->fetch_column() or die($db);
        print_r($password . '<br>' . $db_password);
        if ($password === $db_password) {
            $sql = "UPDATE admin SET email = '$email' WHERE id_admin = '$id_admin'";
        } else {
            $encrypted_password = md5($password);
            $sql = "UPDATE admin SET email = '$email', password = '$encrypted_password' WHERE id_admin = '$id_admin'";
        }

        $db->query($sql) or die($db->error);
    }
    redirect("../../client/admin/admin.php?edit=$id_admin");
}
if (isset($_POST['update_hak_akses'])) {
    $id_admin = escape($_POST['update_hak_akses']);
    $hak_akses = $_POST['hak_akses'];

    $sql = "DELETE FROM detail_role WHERE id_admin = '$id_admin'";
    $db->query($sql) or die($db->error);

    foreach ($hak_akses as $key => $value) {
        $sql = "INSERT INTO detail_role (id_role, id_admin) VALUES('$value', '$id_admin')";
        if ($value === '1') {
            $db->query($sql) or die($db->error);
            break;
        }
        $db->query($sql) or die($db->error);
    }
    redirect("../../client/admin/admin.php?edit=$id_admin");
}
if (isset($_POST['delete'])) {
    $id_admin = escape($_POST['delete']);
    if ($_SESSION['user_id'] !== $id_admin) {
        $sql = "DELETE FROM absensi_admin WHERE id_admin = '$id_admin'";
        $db->query($sql) or die($db->error);

        $sql = "DELETE FROM detail_role WHERE id_admin = '$id_admin'";
        $db->query($sql) or die($db->error);

        $sql = "DELETE FROM admin WHERE id_admin = '$id_admin'";
        $db->query($sql) or die($db->error);
    }
    redirect("../../client/admin/admin.php");
}
