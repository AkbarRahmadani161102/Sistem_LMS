<?php
include_once '../util/util.php';

$role = $_SESSION['role'];
session_destroy();

redirect("../../client/$role/index.php");
