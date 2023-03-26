<?php
$menu_siswa = [
    'MENU' => [
        ['Dashboard', './index.php', '<i class="ri-pie-chart-line"></i>'],
    ],
    'KBM' => [
        ['Anggota Kelas', './anggota_kelas.php', '<i class="ri-team-line"></i>'],
        ['Jadwal', './jadwal.php', '<i class="ri-calendar-2-line"></i>'],
        ['Pertemuan', './pertemuan.php', '<i class="ri-calendar-event-line"></i>'],
    ],
    'ADMINISTRASI' => [
        ['Tunggakan', './tunggakan.php', '<i class="ri-money-dollar-circle-line"></i>'],
        ['Umpan Balik', './umpan_balik_instruktur.php', '<i class="ri-feedback-line"></i>'],
        ['Pengajuan', './pengajuan.php', '<i class="ri-mail-send-line"></i>'],
    ],
    'PENGATURAN' => [
        ['Preferensi', './pengaturan.php', '<i class="ri-settings-4-line"></i>'],
        ['Logout', '../../api/auth/logout.php', '<i class="ri-door-open-line"></i>'],
    ],
];

$menu_instruktur = [
    'MENU' => [
        ['Dashboard', './index.php', '<i class="ri-pie-chart-line"></i>'],
    ],
    'KBM' => [
        ['Jadwal', './jadwal.php', '<i class="ri-calendar-2-line"></i>'],
        ['Pertemuan', './pertemuan.php', '<i class="ri-calendar-event-line"></i>'],
        ['Pertemuan hari ini', './pertemuan_hari_ini.php', '<i class="ri-calendar-check-fill"></i>'],
    ],
    'ADMINISTRASI' => [
        ['Umpan Balik', './umpan_balik_instruktur.php', '<i class="ri-feedback-line"></i>'],
        ['Analisis Pendapatan', './analisis_pendapatan.php', '<i class="ri-line-chart-line"></i>'],
    ],
    'PENGATURAN' => [
        ['Preferensi', './pengaturan.php', '<i class="ri-settings-4-line"></i>'],
        ['Logout', '../../api/auth/logout.php', '<i class="ri-door-open-line"></i>'],
    ],
];

$menu_superadmin = [
    'MENU' => [
        ['Dashboard', './index.php', '<i class="ri-pie-chart-line"></i>'],
        ['Role Admin', './admin_role.php', '<i class="ri-key-2-line"></i>'],
        ['Admin', './admin.php', '<i class="ri-admin-line"></i>'],
        ['Instruktur', './instruktur.php', '<i class="ri-user-settings-line"></i>'],
        ['Siswa', './siswa.php', '<i class="ri-user-2-line"></i>'],
    ],
    'KBM' => [
        ['Jadwal', './jadwal.php', '<i class="ri-calendar-2-line"></i>'],
        ['Penilaian', './pertemuan.php', '<i class="ri-star-line"></i>'],
        ['Presensi', './pertemuan_hari_ini.php', '<i class="ri-check-line"></i>'],
    ],
    'ADMINISTRASI' => [
        ['Umpan Balik', './umpan_balik_instruktur.php', '<i class="ri-feedback-line"></i>'],
        ['Analisis Pendapatan', './analisis_pendapatan.php', '<i class="ri-line-chart-line"></i>'],
    ],
    'PENGATURAN' => [
        ['Preferensi', './pengaturan.php', '<i class="ri-settings-4-line"></i>'],
        ['Logout', '../../api/auth/logout.php', '<i class="ri-door-open-line"></i>'],
    ],
];

$menu_admin_akademik = [
    'MENU' => [
        ['Dashboard', './index.php', '<i class="ri-pie-chart-line"></i>'],
        ['Instruktur', './instruktur.php', '<i class="ri-user-settings-line"></i>'],
        ['Siswa', './siswa.php', '<i class="ri-user-2-line"></i>'],
    ],
    'KBM' => [
        ['Jadwal', './jadwal.php', '<i class="ri-calendar-2-line"></i>'],
        ['Penilaian', './pertemuan.php', '<i class="ri-star-line"></i>'],
        ['Presensi', './pertemuan_hari_ini.php', '<i class="ri-check-line"></i>'],
    ],
    'ADMINISTRASI' => [
        ['Umpan Balik', './umpan_balik_instruktur.php', '<i class="ri-feedback-line"></i>'],
    ],
    'PENGATURAN' => [
        ['Preferensi', './pengaturan.php', '<i class="ri-settings-4-line"></i>'],
        ['Logout', '../../api/auth/logout.php', '<i class="ri-door-open-line"></i>'],
    ],
];

$menu_admin_keuangan = [
    'MENU' => [
        ['Dashboard', './index.php', '<i class="ri-pie-chart-line"></i>'],
    ],
    'ADMINISTRASI' => [
        ['Analisis Pendapatan', './analisis_pendapatan.php', '<i class="ri-line-chart-line"></i>'],
    ],
    'PENGATURAN' => [
        ['Preferensi', './pengaturan.php', '<i class="ri-settings-4-line"></i>'],
        ['Logout', '../../api/auth/logout.php', '<i class="ri-door-open-line"></i>'],
    ],
];

$menu_admin_akademik_keuangan = [
    'MENU' => [
        ['Dashboard', './index.php', '<i class="ri-pie-chart-line"></i>'],
        ['Instruktur', './instruktur.php', '<i class="ri-user-settings-line"></i>'],
        ['Siswa', './siswa.php', '<i class="ri-user-2-line"></i>'],
    ],
    'KBM' => [
        ['Jadwal', './jadwal.php', '<i class="ri-calendar-2-line"></i>'],
        ['Penilaian', './pertemuan.php', '<i class="ri-star-line"></i>'],
        ['Presensi', './pertemuan_hari_ini.php', '<i class="ri-check-line"></i>'],
    ],
    'ADMINISTRASI' => [
        ['Umpan Balik', './umpan_balik_instruktur.php', '<i class="ri-feedback-line"></i>'],
        ['Analisis Pendapatan', './analisis_pendapatan.php', '<i class="ri-line-chart-line"></i>'],
    ],
    'PENGATURAN' => [
        ['Preferensi', './pengaturan.php', '<i class="ri-settings-4-line"></i>'],
        ['Logout', '../../api/auth/logout.php', '<i class="ri-door-open-line"></i>'],
    ],
];

function generate_sidebar(array $menu)
{
    foreach ($menu as $key => $value) {
        echo "<div class='dashboard-sidebar-menu'>";
        echo "<a class='dashboard-sidebar-header'>$key</a>";
        foreach ($value as $key => $menu) {
            $link = $menu[1];
            echo "<a href='$link' class='dashboard-sidebar-item'>";
            echo $menu[2];
            echo $menu[0];
            echo "</a>";
        }
        echo "</div>";
    }
}

?>

<aside id="sidebar-multi-level-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 py-6 overflow-y-auto bg-gray-50 dark:bg-gray-800">
        <a href="#" class="flex items-center mb-5">
            <img src="https://flowbite.com/docs/images/logo.svg" class="h-6 mr-3 sm:h-7" alt="SMART Logo" />
            <span class="self-center text-xl font-medium whitespace-nowrap dark:text-white"><span class="text-amber-500">SMART</span> Solution</span>
        </a>
        <nav id="dashboard-sidebar">
            <?php if ($_SESSION['role'] === 'siswa') generate_sidebar($menu_siswa) ?>
            <?php if ($_SESSION['role'] === 'instruktur') generate_sidebar($menu_instruktur) ?>
            <?php if (isset($_SESSION['detail_role']) && count($_SESSION['detail_role']) === 1 && $_SESSION['role'] === 'admin' && $_SESSION['detail_role'][0]['title'] === 'Super Admin') generate_sidebar($menu_superadmin); ?>
            <?php if (isset($_SESSION['detail_role']) && count($_SESSION['detail_role']) === 1 && $_SESSION['role'] === 'admin' && $_SESSION['detail_role'][0]['title'] === 'Admin Akademik') generate_sidebar($menu_admin_akademik) ?>
            <?php if (isset($_SESSION['detail_role']) && count($_SESSION['detail_role']) === 1 && $_SESSION['role'] === 'admin' && $_SESSION['detail_role'][0]['title'] === 'Admin Keuangan') generate_sidebar($menu_admin_keuangan) ?>
            <?php if (isset($_SESSION['detail_role']) && count($_SESSION['detail_role']) > 1 && $_SESSION['role'] === 'admin') generate_sidebar($menu_admin_akademik_keuangan) ?>
        </nav>
    </div>
</aside>