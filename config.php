<?php
ob_start();
session_start();

define('ENVIRONMENT', '');

$is_production = fn () => ENVIRONMENT === 'PRODUCTION';

define('Hari', ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"]);
define('Waktu', ['14:30:00', '15:30:00', '16:30:00']);

date_default_timezone_set('Asia/Jakarta');

if ($is_production()) {
    define('ROOT_PATH', 'si-smart.000webhostapp.com/');
} else {
    define('ROOT_PATH', dirname(__FILE__).'/');
}
