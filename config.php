<?php
ob_start();
session_start();

define('ENVIRONMENT', '');

$is_production = fn () => ENVIRONMENT === 'PRODUCTION';

date_default_timezone_set('Asia/Jakarta');

if ($is_production()) {
    define('ROOT_PATH', 'si-smart.000webhostapp.com/');
} else {
    define('ROOT_PATH', dirname(__FILE__).'/');
}
