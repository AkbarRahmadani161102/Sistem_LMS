<?php

include_once '../util/db.php';

$id_user = $_SESSION['user_id'];
$role = $_SESSION['role'];

if (isset($_POST['update_profil'])) {
    $nama = escape($_POST['nama']);
    $nomor_telepon = escape($_POST['no_telp']);
    $alamat = escape($_POST['alamat']);

    $is_number = preg_match("/^[0-9]*$/", $nomor_telepon) === 1;
    if ($is_number) {
        $_SESSION['nama'] = $nama;
        $sql = "UPDATE $role SET nama = '$nama', no_telp = '$nomor_telepon', alamat = '$alamat'  WHERE id_$role = '$id_user'";
        $db->query($sql);
    }
    redirect("../../client/user/user_settings.php");
}
if (isset($_POST['update_kredensial'])) {
    $email = escape($_POST['email']);
    $password = escape($_POST['password']);
    $confirm_password = escape($_POST['confirm_password']);

    if ($password === $confirm_password) {
        if ($result->num_rows < 1) {
            $password = md5($password);
            $sql = "UPDATE $role SET email = '$email', password = '$password' WHERE id_$role = '$id_user'";
            $db->query($sql);
        }
    }
    redirect("../../client/user/user_settings.php");
}
