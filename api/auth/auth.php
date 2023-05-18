<?php

use Ramsey\Uuid\Uuid;

require '../../vendor/autoload.php';
require '../util/db.php';

$email = escape($_POST['email']);
$password = md5(escape($_POST['password']));

function login()
{
    global $email;
    global $password;
    global $db;

    $role = isset($_POST['role']) ? $_POST['role'] : $_SESSION['role'];

    $sql = "SELECT * FROM $role WHERE email = '$email' AND password = '$password'";

    if ($result = $db->query($sql)) {
        if ($result->num_rows > 0) {
            $user_data = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $user_data["id_$role"];
            $_SESSION['nama'] = $user_data['nama'];
            $_SESSION['email'] = $user_data['email'];
            $_SESSION['role'] = $role;

            $access_array = [];
            $role_array = [];

            if ($role === 'admin') {
                $user_id = $user_data["id_$role"];

                $sql = "SELECT * FROM hak_akses ha
                JOIN detail_hak_akses dha ON ha.id_hak_akses = dha.id_hak_akses
                JOIN role r ON dha.id_role = r.id_role
                JOIN detail_role dr ON r.id_role = dr.id_role
                WHERE dr.id_admin = '$user_id'";
                $has_access = $db->query($sql) or die($db->error);
                foreach ($has_access as $value) {
                    $access_array[] = $value['nama_file'];
                }

                $sql = "SELECT title FROM detail_role dr
                JOIN role r ON dr.id_role =r.id_role
                WHERE id_admin = '$user_id'
                GROUP BY dr.id_role";
                $has_role = $db->query($sql) or die($db->error);
                foreach ($has_role as $value) {
                    $role_array[] = $value['title'];
                }

                $_SESSION['sidebar_menu'] = $has_access;
                $_SESSION['roles'] = $role_array;
                $_SESSION['has_access'] = $access_array;
            }


            push_alert('Berhasil Login');

            redirect("../../client/$role/index.php");
        } else {
            // Jika gagal login
            push_alert('Email atau password anda salah', 'error');
            redirect("../../client/$role/login.php");
        }
    } else {
        // Jika user tidak ditemukan
        push_alert('Email atau Password anda belum terdaftar', 'error');
        redirect("../../client/$role/login.php");
    }
}

if (isset($_POST['login'])) {
    login();
} else if (isset($_POST['register'])) {

    function is_email_available()
    {
        global $email;
        global $db;
        $sql = "SELECT COUNT(*) jumlah_user FROM siswa WHERE email = '$email'";
        $result = $db->query($sql)->fetch_assoc();
        $jumlah_user = $result['jumlah_user'];
        return $jumlah_user < 1;
    }

    if (is_email_available()) {
        $id_siswa = Uuid::uuid4()->toString();
        $nama = escape($_POST['nama']);
        $sql = "INSERT INTO siswa (id_siswa, nama, email, password) VALUES('$id_siswa', '$nama', '$email', '$password')";
        if ($result = $db->query($sql)) {
            $_SESSION['role'] = 'siswa';
            login();
        } else {
            // Jika register gagal
            push_alert('Register gagal', 'error');
            redirect("../../client/siswa/register.php");
        }
    } else {
        // Jika email sudah digunakan
        push_alert('Email sudah terdaftar', 'error');
        redirect("../../client/siswa/register.php");
    }
}
