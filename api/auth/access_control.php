<?php

include_once "../../api/util/util.php";

function user_access($role)
{
    $param_type = gettype($role);

    if ($param_type === 'array') {
        $detail_role = $_SESSION['detail_role'];
        // print_r($detail_role);
        // echo "<br>";
        // print_r($role);

        $has_access = false;
        foreach ($detail_role as $current_role) {
            if (in_array($current_role['title'], $role)) {
                $has_access = true;
                break;
            }
        }

        if (!$has_access)
            redirect('../../client/admin/login.php');

        // $detail_role_titles = [];
        // foreach ($detail_role as $key => $value)
        //     $detail_role_titles[] = $value['title'];


        // if (!in_array($role, $detail_role_titles) && $value['title'] !== 'Super Admin')
        //     redirect('../../client/admin/index.php');
        //     return;
    } else {
        $current_session = $_SESSION['role'];
        if (!$current_session)
            redirect("../../client/$role/login.php");

        if ($current_session !== $role)
            redirect("../../client/$current_session/index.php");
    }
}
