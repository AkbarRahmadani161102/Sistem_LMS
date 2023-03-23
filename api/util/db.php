<?php
require_once('../../vendor/autoload.php');
require_once('util.php');

use Dotenv\Dotenv;

// Load ENV
$dotenv = Dotenv::createImmutable(__DIR__, '../../.env')->load();

// Connect Database
$db = mysqli_connect($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

// Check connection
if ($db === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

