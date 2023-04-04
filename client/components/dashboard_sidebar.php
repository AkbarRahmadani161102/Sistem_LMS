<?php

$role = $_SESSION['role'];

$menu_user = [
    'MENU' => [
        ['Dashboard', "../$role/index.php", '<i class="ri-pie-chart-line"></i>'],
    ],
    'PENGATURAN' => [
        ['Notifikasi', '../user/notifikasi.php', '<i class="ri-notification-4-line"></i>'],
        ['Preferensi', '../user/user_settings.php', '<i class="ri-settings-4-line"></i>'],
        ['Logout', '../../api/auth/logout.php', '<i class="ri-door-open-line"></i>'],
    ]
];

$menu_siswa = [
    'MENU' => [
        ['Dashboard', "../$role/index.php", '<i class="ri-pie-chart-line"></i>'],
    ],
    'KBM' => [
        ['Anggota Kelas', "../$role/anggota_kelas.php", '<i class="ri-team-line"></i>'],
        ['Jadwal', "../$role/jadwal.php", '<i class="ri-calendar-2-line"></i>'],
        ['Pertemuan', "../$role/pertemuan.php", '<i class="ri-calendar-event-line"></i>'],
    ],
    'ADMINISTRASI' => [
        ['Tunggakan', "../$role/tunggakan.php", '<i class="ri-money-dollar-circle-line"></i>'],
        ['Umpan Balik', "../$role/umpan_balik_instruktur.php", '<i class="ri-feedback-line"></i>'],
        ['Pengajuan', "../$role/pengajuan.php", '<i class="ri-mail-send-line"></i>'],
    ]
];

$menu_instruktur = [
    'MENU' => [
        ['Dashboard', "../$role/index.php", '<i class="ri-pie-chart-line"></i>'],
    ],
    'KBM' => [
        ['Jadwal', "../$role/jadwal.php", '<i class="ri-calendar-2-line"></i>'],
        ['Pertemuan', "../$role/pertemuan.php", '<i class="ri-calendar-event-line"></i>'],
        ['Pertemuan hari ini', "../$role/pertemuan_hari_ini.php", '<i class="ri-calendar-check-fill"></i>'],
        ['Penilaian', "../$role/penilaian.php", '<i class="ri-star-line"></i>'],
    ],
    'ADMINISTRASI' => [
        ['Umpan Balik', "../$role/umpan_balik_instruktur.php", '<i class="ri-feedback-line"></i>'],
    ]
];

$menu_admin_akademik = [
    'MENU' => [
        ['Dashboard', "../$role/index.php", '<i class="ri-pie-chart-line"></i>'],
        ['Instruktur', "../$role/instruktur.php", '<i class="ri-user-settings-line"></i>'],
        ['Siswa', "../$role/siswa.php", '<i class="ri-user-2-line"></i>'],
    ],
    'KBM' => [
        ['Jadwal', "../$role/jadwal.php", '<i class="ri-list-check-2"></i>'],
        ['Pertemuan', "../$role/pertemuan.php", '<i class="ri-calendar-event-line"></i>'],
        ['Jenjang', "../$role/jenjang.php", '<i class="ri-stack-line"></i>'],
        ['Kelas', "../$role/kelas.php", '<i class="ri-bookmark-3-line"></i>'],
        ['Mapel', "../$role/mapel.php", '<i class="ri-book-2-line"></i>'],
        ['Penilaian', "../$role/penilaian.php", '<i class="ri-star-line"></i>'],
        ['Presensi', "../$role/presensi.php", '<i class="ri-check-line"></i>'],
    ],
    'ADMINISTRASI' => [
        ['Umpan Balik', "../$role/umpan_balik_instruktur.php", '<i class="ri-feedback-line"></i>'],
    ]
];

$menu_admin_keuangan = [
    'MENU' => [
        ['Dashboard', "../$role/index.php", '<i class="ri-pie-chart-line"></i>'],
    ],
    'ADMINISTRASI' => []
];

$menu_superadmin = [
    'MENU' => [
        ['Dashboard', "../$role/index.php", '<i class="ri-pie-chart-line"></i>'],
        ['Role Admin', "../$role/admin_role.php", '<i class="ri-key-2-line"></i>'],
        ['Admin', "../$role/admin.php", '<i class="ri-admin-line"></i>'],
        ['Instruktur', "../$role/instruktur.php", '<i class="ri-user-settings-line"></i>'],
        ['Siswa', "../$role/siswa.php", '<i class="ri-user-2-line"></i>'],
    ]
];

$menu_siswa = array_replace_recursive($menu_siswa, $menu_user);

$menu_instruktur = array_replace_recursive($menu_instruktur, $menu_user);

$menu_admin_akademik = array_replace_recursive($menu_admin_akademik, $menu_user);

$menu_admin_keuangan = array_replace_recursive($menu_admin_keuangan, $menu_user);

$menu_superadmin = array_replace_recursive($menu_admin_akademik, $menu_admin_keuangan, $menu_superadmin, $menu_user);

$menu_admin_akademik_keuangan = array_replace_recursive($menu_admin_akademik, $menu_admin_keuangan, $menu_user);

function generate_sidebar(array $source_menu)
{
    foreach ($source_menu as $title => $menu) : ?>
        <div class="dashboard-sidebar-menu">
            <a href="" class="dashboard-sidebar-header"><?= $title ?></a>
            <?php foreach ($menu as $submenu) : ?>
                <a href="<?= $submenu[1] ?>" class="dashboard-sidebar-item">
                    <?= $submenu[2] ?>
                    <?= $submenu[0] ?>
                </a>
            <?php endforeach ?>
        </div>
    <?php endforeach ?>
<?php } ?>

<aside id="sidebar-multi-level-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 py-6 overflow-y-auto bg-gray-50 dark:bg-gray-800">
        <a href="../<?= $role ?>/index.php" class="flex items-center mb-5">
            <img src="../assets/image/icon.png" class="h-6 mr-3 sm:h-7" alt="SMART Logo" />
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