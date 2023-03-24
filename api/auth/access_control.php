<?php

include_once "../../api/util/util.php";

function user_access(string $role)
{
    $current_session = $_SESSION['role'];
    if (!$current_session)
        redirect("../../client/$role/login.php");

    if($current_session !== $role) 
        redirect("../../client/$current_session/index.php");
}
