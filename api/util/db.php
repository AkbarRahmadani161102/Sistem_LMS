<?php
require_once('../../vendor/autoload.php');
require_once('../../config.php');
require_once('util.php');

use Dotenv\Dotenv;

// Load ENV
$dotenv = Dotenv::createImmutable(__DIR__, '../../.env')->load();

// Connect Database
if (ENVIRONMENT === 'PRODUCTION')
    $db = mysqli_connect($_ENV['PROD_DB_HOST'], $_ENV['PROD_DB_USER'], $_ENV['PROD_DB_PASS'], $_ENV['PROD_DB_NAME']) or die("ERROR: Could not connect. " . mysqli_connect_error());
else
    $db = mysqli_connect($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']) or die("ERROR: Could not connect. " . mysqli_connect_error());