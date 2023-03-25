<?php
// require './vendor/autoload.php';

// use Ramsey\Uuid\Uuid;

// $uuid = Uuid::uuid4();

// printf(
//     $uuid->toString()
// );

session_start();
print_r($_SESSION);
echo count($_SESSION['detail_role']);


// session_destroy();
