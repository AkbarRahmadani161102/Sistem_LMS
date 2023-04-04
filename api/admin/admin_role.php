<?php

include_once '../util/db.php';

try {
    if (isset($_POST['create'])) {
        $title = $_POST['title'];
        $sql = "INSERT INTO role (title) VALUES('$title')";
        $db->query($sql) or die($db->error);
        $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Data role berhasil ditambahkan', 'icon_color' => 'greenlight'];
    } else if (isset($_POST['update'])) {
        $id_role = $_POST['update'];
        $title = $_POST['title'];
        $sql = "UPDATE role SET title = '$title' WHERE id_role = '$id_role'";
        $db->query($sql) or die($db->error);
        $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Data role berhasil diubah', 'icon_color' => 'greenlight'];
    } else if (isset($_POST['delete'])) {
        // ISSUE: constraint foreign key
        $id_role = $_POST['delete'];
        $sql = "DELETE FROM role WHERE id_role = '$id_role'";
        $db->query($sql) or die($db->error);
        $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Data role berhasil dihapus', 'icon_color' => 'greenlight'];
    }
} catch (\Throwable $th) {
    $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Gagal menghapus', 'icon_color' => 'red', 'text' => 'Constraint integrity error'];
}

redirect('../../client/admin/admin_role.php');
