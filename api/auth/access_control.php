<?php

include_once "../../api/util/util.php";

function user_access($role = 'siswa')
{
    if (!$_SESSION['role'])
        if (!$_SESSION['role'] !== $role)
            redirect("../../client/$role/login.php");
}
