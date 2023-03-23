<?php

include_once '../util/db.php';

if (!isset($_SESSION)) {
    session_start();
}
$id_instruktur = $_SESSION['user_id'];

if (isset($_POST['update_profil'])) {
    $nama = escape($_POST['nama']);
    $nomor_telepon = escape($_POST['no_telp']);
    $alamat = escape($_POST['alamat']);

    $contain_letter = preg_match("/[a-z][A-Z]/", $nomor_telepon) === 1;
    if (!$contain_letter) {
        $_SESSION['nama'] = $nama;
        $sql = "UPDATE instruktur SET nama = '$nama', no_telp = '$nomor_telepon', alamat = '$alamat'  WHERE id_instruktur = '$id_instruktur'";
        $db->query($sql);
    }
    redirect('../../client/instruktur/pengaturan.php');
}
if (isset($_POST['update_kredensial'])) {
    $email = escape($_POST['email']);
    $password = escape($_POST['password']);
    $confirm_password = escape($_POST['confirm_password']);

    if ($password === $confirm_password) {
        $password = md5($password);
        $sql = "UPDATE instruktur SET email = '$email', password = '$password' WHERE id_instruktur = '$id_instruktur'";
        $db->query($sql);
    }
    redirect('../../client/instruktur/pengaturan.php');
}
