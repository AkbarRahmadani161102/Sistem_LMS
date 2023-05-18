<?php
require '../../vendor/autoload.php';
include_once '../util/db.php';

use Ramsey\Uuid\Uuid;

$current_date = date('Y-m-d');

if (isset($_POST['create'])) {

    $id_admin = Uuid::uuid4()->toString();
    $nama = escape($_POST['nama']);
    $no_telp = escape($_POST['no_telp']);
    $alamat = escape($_POST['alamat']);
    $email = escape($_POST['email']);
    $password = md5(escape($_POST['password']));

    $is_number = preg_match("/^[0-9]*$/", $no_telp) === 1;

    function is_email_available()
    {
        global $db, $email;
        $sql = "SELECT COUNT(*) used_email FROM admin WHERE email = '$email'";
        $used_email = $db->query($sql);
        $used_email = $used_email->fetch_assoc();
        return $used_email['used_email'] === '0';
    }

    if ($is_number) {
        try {
            if (is_email_available()) {
                $sql = "INSERT INTO admin (id_admin, nama, no_telp, alamat, email, password) VALUES ('$id_admin', '$nama', '$no_telp', '$alamat', '$email' , '$password')";
                $db->query($sql);
                if (isset($_POST['hak_akses'])) {
                    $hak_akses = $_POST['hak_akses'];
                    foreach ($hak_akses as $key => $value) {
                        $sql = "INSERT INTO detail_role (id_role, id_admin) VALUES('$value', '$id_admin')";
                        if ($value === '1') {
                            $db->query($sql);
                            break;
                        }
                        $db->query($sql);
                        push_toast('Data admin berhasil ditambahkan');
                    }
                } else {
                    $sql = "SELECT id_role FROM role LIMIT 1";
                    $id_role = $db->query($sql);
                    $id_role = $id_role->fetch_assoc()['id_role'];
                    $sql = "INSERT INTO detail_role (id_role, id_admin) VALUES('$id_role', '$id_admin')";
                    $db->query($sql);
                }
                push_toast('Admin berhasil ditambah');
            }
        } catch (\Throwable $th) {
            push_toast('Gagal menambah', 'Email sudah ada', 'error', 'red');
        }
    } else {
        push_toast('Gagal menambah', 'error', 'Password mengandung karakter');
    }
}
if (isset($_POST['update_profil'])) {
    $id_admin = escape($_POST['update_profil']);
    $nama = escape($_POST['nama']);
    $nomor_telepon = escape($_POST['no_telp']);
    $alamat = escape($_POST['alamat']);

    $is_number = preg_match("/^[0-9]*$/", $nomor_telepon) === 1;
    if ($is_number) {
        $_SESSION['nama'] = $nama;
        $sql = "UPDATE admin SET nama = '$nama', no_telp = '$nomor_telepon', alamat = '$alamat', tgl_diubah = '$current_date' WHERE id_admin = '$id_admin'";
        $db->query($sql);
        push_toast('Data profil berhasil diubah');
    } else {
        push_toast('Gagal mengubah', 'error', 'Field nomor telp mengandung karakter');
    }
    redirect("../../client/admin/admin.php?edit=$id_admin");
}
if (isset($_POST['update_kredensial'])) {
    $id_admin = escape($_POST['update_kredensial']);
    $email = escape($_POST['email']);
    $password = escape($_POST['password']);
    $confirm_password = escape($_POST['confirm_password']);

    if ($password === $confirm_password) {
        try {
            $sql = "SELECT password FROM admin WHERE id_admin = '$id_admin'";
            echo $sql;
            $db_password = $db->query($sql)->fetch_column() or die($db);
            print_r($password . '<br>' . $db_password);
            if ($password === $db_password) {
                $sql = "UPDATE admin SET email = '$email', tgl_diubah = '$current_date' WHERE id_admin = '$id_admin'";
            } else {
                $encrypted_password = md5($password);
                $sql = "UPDATE admin SET email = '$email', password = '$encrypted_password', tgl_diubah = '$current_date' WHERE id_admin = '$id_admin'";
            }
            $db->query($sql);
            push_toast('Data kredensial berhasil diubah');
        } catch (\Throwable $th) {
            push_toast('Gagal mengubah', 'error', 'Email sudah ada');
        }
    } else {
        push_toast('Gagal mengubah', 'error', 'Pastikan konfirmasi password sama');
    }
    redirect("../../client/admin/admin.php?edit=$id_admin");
}
if (isset($_POST['update_hak_akses'])) {
    $id_admin = escape($_POST['update_hak_akses']);
    $hak_akses = $_POST['hak_akses'];

    $sql = "DELETE FROM detail_role WHERE id_admin = '$id_admin'";
    $db->query($sql);

    foreach ($hak_akses as $key => $value) {
        $sql = "INSERT INTO detail_role (id_role, id_admin) VALUES('$value', '$id_admin')";
        if ($value === '1') {
            $db->query($sql);
            break;
        }
        $db->query($sql);
    }
    push_toast('Data hak akses berhasil diubah');
    redirect("../../client/admin/admin.php?edit=$id_admin");
}
if (isset($_POST['delete'])) {
    $id_admin = escape($_POST['delete']);
    if ($_SESSION['user_id'] !== $id_admin) {
        $sql = "DELETE FROM absensi_admin WHERE id_admin = '$id_admin'";
        $db->query($sql);

        $sql = "DELETE FROM detail_role WHERE id_admin = '$id_admin'";
        $db->query($sql);

        $sql = "DELETE FROM admin WHERE id_admin = '$id_admin'";
        $db->query($sql);
        push_toast('Data admin berhasil dihapus');
        redirect("../../client/admin/admin.php");

        if ($db->error) {
            push_toast('Gagal menghapus', 'error', 'Constraint integrity error');
        }
    }
    push_toast('Gagal menghapus', 'error', 'Constraint integrity error');
}

redirect("../../client/admin/admin.php");
