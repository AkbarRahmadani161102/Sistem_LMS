<?php
include_once '../../config.php';
include_once '../util/util.php';
$role = $_SESSION['role'];
echo $role;
session_destroy();

redirect("../../client/$role/index.php");
