<?php

include_once "../../api/util/util.php";

function user_access(string $role, string $filename = NULL)
{
    $param_type = gettype($role);

    $current_session = $_SESSION['role'];
    if (!$current_session)
        redirect("../../client/$role/login.php");

    if ($current_session !== $role)
        redirect("../../client/$current_session/index.php");

    if ($role === 'admin') {
        if ($filename !== NULL) {
            if (!in_array($filename, $_SESSION['has_access']))
                redirect("../../client/admin/index.php");
        }
    }
}
