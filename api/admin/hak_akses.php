<?php

include_once '../util/db.php';

if (isset($_POST['create'])) {
    $title = escape($_POST['title']);
    $sql = "INSERT INTO role (title) VALUES('$title')";
    $db->query($sql);

    $id_role = $db->insert_id;
    if (isset($_POST['hak_akses'])) {
        $hak_akses = $_POST['hak_akses'];
        foreach ($hak_akses as $id_hak_akses) {
            $sql = "INSERT INTO detail_hak_akses (id_role, id_hak_akses) VALUES('$id_role', '$id_hak_akses')";
            $db->query($sql);
        }
    }

    $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Data role berhasil ditambahkan', 'icon_color' => 'greenlight'];
} else if (isset($_POST['update'])) {
    $id_role = escape($_POST['update']);
    $title = escape($_POST['title']);
    $sql = "UPDATE role SET title = '$title' WHERE id_role = '$id_role'";
    $db->query($sql);

    $sql = "DELETE FROM detail_hak_akses WHERE id_role = '$id_role'";
    $db->query($sql);
    if (isset($_POST['hak_akses'])) {
        $hak_akses = $_POST['hak_akses'];
        foreach ($hak_akses as $id_hak_akses) {
            $sql = "INSERT INTO detail_hak_akses (id_role, id_hak_akses) VALUES('$id_role', '$id_hak_akses')";
            $db->query($sql);
        }
    }
    $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Data role berhasil diubah', 'icon_color' => 'greenlight'];
} else if (isset($_POST['delete'])) {
    // ISSUE: constraint foreign key
    $id_role = escape($_POST['delete']);
    $sql = "DELETE FROM detail_hak_akses WHERE id_role = '$id_role'";
    $db->query($sql);
    $sql = "DELETE FROM role WHERE id_role = '$id_role'";
    $db->query($sql);
    $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Data role berhasil dihapus', 'icon_color' => 'greenlight'];

    if ($db->error) {
        $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Gagal menghapus', 'icon_color' => 'red', 'text' => 'Constraint integrity error'];
    }
} else if (isset($_POST['sync'])) {
    $data_hak_akses = $_POST['data_hak_akses'];
    foreach ($data_hak_akses as $data) {
        $nama = $data['nama'];
        $kategori = $data['kategori'];
        $nama_file = $data['nama_file'];
        $icon = $data['icon'];

        $sql = "INSERT INTO hak_akses (nama, kategori, nama_file, icon) VALUES('$nama', '$kategori', '$nama_file', '$icon')";
        $db->query($sql) or die($db->error);
    }
    $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Daftar hak akses berhasil diperbarui', 'icon_color' => 'greenlight'];
} else if (isset($_POST['update_hak_akses'])) {
    $id_hak_akses = escape($_POST['update_hak_akses']);
    $nama = escape($_POST['nama']);
    $kategori = escape($_POST['kategori']);
    $icon = escape($_POST['icon']);

    $sql = "UPDATE hak_akses SET nama = '$nama', kategori = '$kategori', icon='$icon' WHERE id_hak_akses = '$id_hak_akses'";
    $db->query($sql) or die($db->error);
    $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Hak akses berhasil diperbarui', 'icon_color' => 'greenlight'];

?>
    <script>
        history.back()
    </script>
<?php
}

redirect('../../client/admin/hak_akses.php');
