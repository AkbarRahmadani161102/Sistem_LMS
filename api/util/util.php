<?php

/** 
 * Page Redirector
 * 
 * @param string $location lokasi tujuan, contoh "../../client/siswa/index.php"
 */
function redirect($location)
{
    header("Location: $location");
    die();
}

/**
 * Prevent SQL Injection
 * 
 * @param string $str Data yang akan disanitasi
 * 
 * @return string Data tersanitasi
 */
function escape($str)
{
    global $db;
    return mysqli_real_escape_string($db, trim($str));
}

$status_colors = [
    ['status' => 'success', 'icon_color' => 'greenlight'],
    ['status' => 'error', 'icon_color' => 'red'],
    ['status' => 'warning', 'icon_color' => 'yellow'],
    ['status' => 'info', 'icon_color' => 'cyan'],
    ['status' => 'question', 'icon_color' => 'gray'],
];

/**
 * Aka. Flash Message
 * 
 * Add toast details to session
 * 
 * @param string $title Judul toast
 * @param string $status Tema toast (Opsi: 'success', 'error', 'warning', 'info', 'question'; Default: 'success')
 * @param string|null $text (Opsional) Deskripsi dari detail kejadian yang sedang terjadi
 * 
 * @return array Array Session yang telah diisi oleh data dari detail toast
 */
function push_toast($title = '', $status = 'success', $text = null)
{
    global $status_colors;
    foreach ($status_colors as $toast) {
        if ($toast['status'] === $status) {
            return $_SESSION['toast'] = ['icon' => $toast['status'], 'title' => $title, 'text' => $text, 'icon_color' => $toast['icon_color']];
        }
    }
}

/**
 * Aka. Flash Alert
 * 
 * Add message alert to session
 * 
 * @param string $title Judul alert
 * @param string $status Tema alert (Opsi: 'success', 'error', 'warning', 'info', 'question'; Default: 'success')
 * 
 * @return array Array Session yang telah diisi oleh data dari detail alert
 */
function push_alert($title = '', $status = 'success')
{
    global $status_colors;
    foreach ($status_colors as $alert) {
        if ($alert['status'] === $status) {
            return $_SESSION['alert'] = ['icon' => $alert['status'], 'title' => $title, 'icon_color' => $alert['icon_color']];
        }
    }
}