<?php
require '../../vendor/autoload.php';
include_once '../util/db.php';

use Ramsey\Uuid\Uuid;

if (isset($_POST['create'])) {

    $id_instruktur = Uuid::uuid4()->toString();
    $nama = escape($_POST['nama']);
    $no_telp = escape($_POST['no_telp']);
    $alamat = escape($_POST['alamat']);
    $email = escape($_POST['email']);
    $password = md5(escape($_POST['password']));

    $contain_letter = preg_match("/[a-z][A-Z]/", $no_telp) === 1;

    function is_email_available()
    {
        global $db, $email;
        $sql = "SELECT COUNT(*) used_email FROM instruktur WHERE email = '$email'";
        $used_email = $db->query($sql) or die($db->error);
        $used_email = $used_email->fetch_assoc();
        return $used_email['used_email'] === '0';
    }

    if (!$contain_letter) {
        if (is_email_available()) {
            $sql = "INSERT INTO instruktur (id_instruktur, nama, no_telp, alamat, email, password, status) VALUES ('$id_instruktur', '$nama', '$no_telp', '$alamat', '$email' , '$password', 'Aktif')";
            $db->query($sql) or die($db->error);
            if (isset($_POST['mapel'])) {
                $mapel = $_POST['mapel'];
                foreach ($mapel as $key => $value) {
                    $sql = "INSERT INTO detail_mapel (id_mapel, id_instruktur) VALUES('$value', '$id_instruktur')";
                    $db->query($sql) or die($db->error);
                }
            }
        }
        // Email Sudah Ada
    }
    // Password Mengandung Karakter

    redirect("../../client/admin/instruktur.php");
}
if (isset($_POST['update_profil'])) {
    $id_instruktur = escape($_POST['update_profil']);
    $nama = escape($_POST['nama']);
    $nomor_telepon = escape($_POST['no_telp']);
    $alamat = escape($_POST['alamat']);

    $contain_letter = preg_match("/[a-z][A-Z]/", $nomor_telepon) === 1;
    if (!$contain_letter) {
        $_SESSION['nama'] = $nama;
        $sql = "UPDATE instruktur SET nama = '$nama', no_telp = '$nomor_telepon', alamat = '$alamat'  WHERE id_instruktur = '$id_instruktur'";
        $db->query($sql) or die($db->error);
    }
    redirect("../../client/admin/instruktur.php?edit=$id_instruktur");
}
if (isset($_POST['update_kredensial'])) {
    $id_instruktur = escape($_POST['update_kredensial']);
    $email = escape($_POST['email']);
    $password = escape($_POST['password']);
    $confirm_password = escape($_POST['confirm_password']);

    if ($password === $confirm_password) {
        $sql = "SELECT password FROM instruktur WHERE id_instruktur = '$id_instruktur'";
        echo $sql;
        $db_password = $db->query($sql)->fetch_column() or die($db);
        print_r($password . '<br>' . $db_password);
        if ($password === $db_password) {
            $sql = "UPDATE instruktur SET email = '$email' WHERE id_instruktur = '$id_instruktur'";
        } else {
            $encrypted_password = md5($password);
            $sql = "UPDATE instruktur SET email = '$email', password = '$encrypted_password' WHERE id_instruktur = '$id_instruktur'";
        }

        $db->query($sql) or die($db->error);
    }
    redirect("../../client/admin/instruktur.php?edit=$id_instruktur");
}
if (isset($_POST['update_mapel'])) {
    $id_instruktur = escape($_POST['update_mapel']);

    $sql = "DELETE FROM detail_mapel WHERE id_instruktur = '$id_instruktur'";
    $db->query($sql) or die($db->error);

    if (isset($_POST['mapel'])) {
        $mapel = $_POST['mapel'];
        foreach ($mapel as $key => $value) {
            $sql = "INSERT INTO detail_mapel (id_mapel, id_instruktur) VALUES('$value', '$id_instruktur')";
            $db->query($sql) or die($db->error);
        }
    }
}
if (isset($_POST['delete'])) {
    $id_instruktur = escape($_POST['delete']);
    $sql = "DELETE FROM gaji WHERE id_instruktur = '$id_instruktur'";
    $db->query($sql) or die($db->error);

    $sql = "DELETE FROM detail_mapel WHERE id_instruktur = '$id_instruktur'";
    $db->query($sql) or die($db->error);

    $sql = "DELETE FROM instruktur WHERE id_instruktur = '$id_instruktur'";
    $db->query($sql) or die($db->error);

    $sql = "DELETE FROM kuesioner_instruktur WHERE id_instruktur = '$id_instruktur'";
    $db->query($sql) or die($db->error);

    $sql = "DELETE FROM notifikasi_instruktur WHERE id_instruktur = '$id_instruktur'";
    $db->query($sql) or die($db->error);

    $sql = "DELETE FROM penilaian WHERE id_instruktur = '$id_instruktur'";
    $db->query($sql) or die($db->error);
}
redirect("../../client/admin/instruktur.php");
