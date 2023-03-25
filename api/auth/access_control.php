<?php

include_once "../../api/util/util.php";

function user_access(string $role)
{

    if (str_contains($role, "Admin") && strlen($role) > 5) {
        $detail_role = $_SESSION['detail_role'];
        $detail_role_titles = [];
        foreach ($detail_role as $key => $value)
            $detail_role_titles[] = $value['title'];

        if (!in_array($role, $detail_role_titles))
            redirect('../../client/admin/index.php');

        return;
    }

    $current_session = $_SESSION['role'];
    if (!$current_session)
        redirect("../../client/$role/login.php");

    if ($current_session !== $role)
        redirect("../../client/$current_session/index.php");
}
