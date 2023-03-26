<?php

include_once '../util/db.php';

function execute_and_redirect(string $sql)
{
    global $db;
    $db->query($sql) or die($db->error);
    redirect('../../client/admin/admin_role.php');
}

if (isset($_POST['create'])) {
    $title = $_POST['title'];
    $sql = "INSERT INTO role (title) VALUES('$title')";
    execute_and_redirect($sql);
} else if (isset($_POST['update'])) {
    $id_role = $_POST['update'];
    $title = $_POST['title'];
    $sql = "UPDATE role SET title = '$title' WHERE id_role = '$id_role'";
    execute_and_redirect($sql);
} else if (isset($_POST['delete'])) {
    // ISSUE: constraint foreign key
    $id_role = $_POST['delete'];
    $sql = "DELETE FROM role WHERE id_role = '$id_role'";
    execute_and_redirect($sql);
}
