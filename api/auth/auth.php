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

            if ($role === 'admin') {
                /**
                 * ID Role:
                 * 1. Super Admin
                 * 2. Admin Keuangan
                 * 3. Admin Akademik
                 */

                $id_admin = $user_data["id_$role"];

                $sql = "SELECT a.nama, r.id_role , r.title FROM admin a, role r, detail_role dr WHERE a.id_admin = dr.id_admin AND dr.id_role = r.id_role AND a.id_admin = '$id_admin'";
                $result = $db->query($sql) or die(mysqli_error($db));
                $result->fetch_assoc();

                $detail_role = [];
                foreach ($result as $key => $value) {
                    array_push($detail_role, ['id_role' => $value['id_role'], 'title' => $value['title']]);
                }
                $_SESSION['detail_role'] = $detail_role;
            }

            $_SESSION['alert'] = ['icon' => 'success', 'title' => 'Berhasil Login'];

            redirect("../../client/$role/index.php");
        } else {
            // Jika gagal login
            $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Email atau password anda salah'];
            redirect("../../client/$role/login.php");
        }
    } else {
        // Jika user tidak ditemukan
        $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Email atau Password anda belum terdaftar'];
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
            $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Register gagal'];
            redirect("../../client/siswa/register.php");
        }
    } else {
        // Jika email sudah digunakan
        $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Email sudah terdaftar'];
        redirect("../../client/siswa/register.php");
    }
}
