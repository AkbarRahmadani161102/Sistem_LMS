<?php

$role = $_SESSION['role'];

$menu_siswa = [
    'MENU' => [
        ['Dashboard', "../$role/index.php", '<i class="ri-pie-chart-line"></i>'],
    ],
    'KBM' => [
        ['Anggota Kelas', "../$role/anggota_kelas.php", '<i class="ri-team-line"></i>'],
        ['Jadwal', "../$role/jadwal.php", '<i class="ri-calendar-2-line"></i>'],
        ['Pertemuan', "../$role/pertemuan.php", '<i class="ri-calendar-event-line"></i>'],
        ['Nilai Siswa', "../$role/nilai_siswa.php", '<i class="ri-star-line"></i>'],
    ],
    'ADMINISTRASI' => [
        ['Tunggakan', "../$role/tunggakan.php", '<i class="ri-money-dollar-circle-line"></i>'],
        ['Umpan Balik', "../$role/umpan_balik_instruktur.php", '<i class="ri-feedback-line"></i>'],
        ['Pengajuan', "../$role/pengajuan.php", '<i class="ri-mail-send-line"></i>'],
    ],
    'PENGATURAN' => [
        ['Notifikasi', '../user/notifikasi.php', '<i class="ri-notification-4-line"></i>'],
        ['Preferensi', '../user/user_settings.php', '<i class="ri-settings-4-line"></i>'],
        ['Logout', '../../api/auth/logout.php', '<i class="ri-door-open-line"></i>'],
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
        ['Pendapatan', "../$role/pendapatan.php", '<i class="ri-wallet-2-line"></i>'],
    ],
    'PENGATURAN' => [
        ['Notifikasi', '../user/notifikasi.php', '<i class="ri-notification-4-line"></i>'],
        ['Preferensi', '../user/user_settings.php', '<i class="ri-settings-4-line"></i>'],
        ['Logout', '../../api/auth/logout.php', '<i class="ri-door-open-line"></i>'],
    ]
];

// $menu_admin_akademik = [
//     'MENU' => [
//         ['Dashboard', "../$role/index.php", '<i class="ri-pie-chart-line"></i>'],
//         ['Instruktur', "../$role/instruktur.php", '<i class="ri-user-2-line"></i>'],
//         ['Siswa', "../$role/siswa.php", '<i class="ri-user-line"></i>'],
//         ['Pengajuan', "../$role/pengajuan.php", '<i class="ri-discuss-line"></i>'],
//     ],
//     'KBM' => [
//         ['Jadwal', "../$role/jadwal.php", '<i class="ri-list-check-2"></i>'],
//         ['Pertemuan', "../$role/pertemuan.php", '<i class="ri-calendar-event-line"></i>'],
//         ['Jenjang', "../$role/jenjang.php", '<i class="ri-stack-line"></i>'],
//         ['Kelas', "../$role/kelas.php", '<i class="ri-bookmark-3-line"></i>'],
//         ['Mapel', "../$role/mapel.php", '<i class="ri-book-2-line"></i>'],
//         ['Penilaian', "../$role/penilaian.php", '<i class="ri-star-line"></i>'],
//         ['Presensi', "../$role/presensi.php", '<i class="ri-check-line"></i>'],
//     ],
//     'ADMINISTRASI' => [
//         ['Umpan Balik', "../$role/umpan_balik_instruktur.php", '<i class="ri-feedback-line"></i>'],
//     ]
// ];

// $menu_admin_keuangan = [
//     'MENU' => [
//         ['Dashboard', "../$role/index.php", '<i class="ri-pie-chart-line"></i>'],
//     ],
//     'ADMINISTRASI' => [
//         ['Tunggakan', "../$role/tunggakan.php", '<i class="ri-money-dollar-box-line"></i>'],
//         ['Gaji Instruktur', "../$role/gaji_instruktur.php", '<i class="ri-money-dollar-box-line"></i>'],
//         ['L. Keuangan', "../$role/laporan_keuangan.php", '<i class="ri-money-dollar-box-line"></i>'],
//     ]
// ];

// $menu_superadmin = [
//     'MENU' => [
//         ['Dashboard', "../$role/index.php", '<i class="ri-pie-chart-line"></i>'],
//         ['Hak Akses', "../$role/hak_akses.php", '<i class="ri-key-2-line"></i>'],
//         ['Admin', "../$role/admin.php", '<i class="ri-admin-line"></i>'],
//         ['Instruktur', "../$role/instruktur.php", '<i class="ri-user-2-line"></i>'],
//         ['Siswa', "../$role/siswa.php", '<i class="ri-user-line"></i>'],
//         ['Pengajuan', "../$role/pengajuan.php", '<i class="ri-discuss-line"></i>'],
//     ],
// ];

// $menu_siswa = array_replace_recursive($menu_siswa, $menu_user);

// $menu_instruktur = array_replace_recursive($menu_instruktur, $menu_user);

// $menu_admin_akademik = array_replace_recursive($menu_admin_akademik, $menu_user);

// $menu_admin_keuangan = array_replace_recursive($menu_admin_keuangan, $menu_user);

// $menu_superadmin = array_replace_recursive($menu_admin_akademik, $menu_admin_keuangan, $menu_superadmin, $menu_user);

// $menu_admin_akademik_keuangan = array_replace_recursive($menu_admin_akademik, $menu_admin_keuangan, $menu_user, [
//     'ADMINISTRASI' => [
//         ['Tunggakan', "../$role/tunggakan.php", '<i class="ri-money-dollar-box-line"></i>'],
//         ['Gaji Instruktur', "../$role/gaji_instruktur.php", '<i class="ri-money-dollar-box-line"></i>'],
//         ['L. Keuangan', "../$role/laporan_keuangan.php", '<i class="ri-money-dollar-box-line"></i>'],
//         ['Umpan Balik', "../$role/umpan_balik_instruktur.php", '<i class="ri-feedback-line"></i>']
//     ]
// ]);

function generate_sidebar(array $source_menu)
{
    foreach ($source_menu as $title => $menu) : ?>
        <div class="dashboard__sidebar-menu">
            <a href="" class="dashboard__sidebar-header"><?= $title ?></a>
            <?php foreach ($menu as $submenu) : ?>
                <a href="<?= $submenu[1] ?>" class="dashboard__sidebar-item">
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
            <?php if ($_SESSION['role'] !== 'siswa' && $_SESSION['role'] !== 'instruktur') : ?>
                <?php $user_id = $_SESSION['user_id'];
                $sql = "SELECT DISTINCT(kategori) FROM detail_hak_akses dha 
                JOIN hak_akses ha ON dha.id_hak_akses = ha.id_hak_akses 
                JOIN role r ON dha.id_role = r.id_role 
                JOIN detail_role dr ON r.id_role = dr.id_role 
                WHERE dr.id_admin = '$user_id' 
                ORDER BY ha.kategori DESC";
                $menu_admin = $db->query($sql) or die($db->error); ?>

                <?php foreach ($menu_admin as $menu) : ?>
                    <div class="dashboard__sidebar-menu">
                        <a href="" class="dashboard__sidebar-header"><?= $menu['kategori'] ?></a>
                        <?php
                        $kategori = $menu['kategori'];
                        $sql = "SELECT * FROM detail_hak_akses dha 
                        JOIN hak_akses ha ON dha.id_hak_akses = ha.id_hak_akses 
                        JOIN role r ON dha.id_role = r.id_role 
                        JOIN detail_role dr ON r.id_role = dr.id_role 
                        WHERE dr.id_admin = '$user_id' 
                        AND ha.kategori = '$kategori'";
                        $submenu = $db->query($sql) or die($db->error);
                        ?>

                        <?php if ($menu['kategori'] === 'MENU') : ?>
                            <a href="../admin/index.php" class="dashboard__sidebar-item">
                                <i class="ri-pie-chart-line"></i>
                                Dashboard
                            </a>
                        <?php endif ?>

                        <?php foreach ($submenu as $sb) : ?>
                            <a href="../admin/<?= $sb['nama_file'] ?>" class="dashboard__sidebar-item">
                                <?= $sb['icon'] ?>
                                <?= $sb['nama'] ?>
                            </a>
                        <?php endforeach ?>
                    </div>
                <?php endforeach ?>
                <div class="dashboard__sidebar-menu">
                    <a href="" class="dashboard__sidebar-header">PENGATURAN</a>
                    <a href="../user/user_settings.php" class="dashboard__sidebar-item">
                        <i class="ri-settings-4-line"></i>
                        Preferensi
                    </a>
                    <a href="../../api/auth/logout.php" class="dashboard__sidebar-item">
                        <i class="ri-door-open-line"></i>
                        Logout
                    </a>
                </div>
            <?php endif ?>
        </nav>
    </div>
</aside>