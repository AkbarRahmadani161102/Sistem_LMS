<?php

session_start();

// Redirect Function
function redirect($location){
    header("Location: $location");
    die();
}

// Prevent SQL Injection Function
function escape($str)
{
    global $db;
    return mysqli_real_escape_string($db, trim($str));
}
