<aside id="sidebar-multi-level-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-gray-50 dark:bg-gray-800 mt-2">
        <a href="#" class="flex items-center mb-5">
            <img src="https://flowbite.com/docs/images/logo.svg" class="h-6 mr-3 sm:h-7" alt="SMART Logo" />
            <span class="self-center text-xl font-medium whitespace-nowrap dark:text-white"><span class="text-amber-500">SMART</span> Solution</span>
        </a>
        <nav id="dashboard-sidebar">
            <?php if ($_SESSION['role'] === 'siswa') : ?>
                <div class="dashboard-sidebar-menu">
                    <a class="dashboard-sidebar-header">MENU</a>
                    <a href="./index.php" class="dashboard-sidebar-item">
                        <i class="ri-bar-chart-2-line"></i>
                        Dashboard
                    </a>
                </div>
                <div class="dashboard-sidebar-menu">
                    <a class="dashboard-sidebar-header">KBM</a>
                    <a href="./anggota_kelas.php" class="dashboard-sidebar-item">
                        <i class="ri-team-line"></i>
                        Anggota Kelas
                    </a>
                    <a href="./jadwal.php" class="dashboard-sidebar-item">
                        <i class="ri-calendar-2-line"></i>
                        Jadwal
                    </a>
                    <a href="./pertemuan.php" class="dashboard-sidebar-item">
                        <i class="ri-calendar-event-line"></i>
                        Pertemuan
                    </a>
                </div>
                <div class="dashboard-sidebar-menu">
                    <a class="dashboard-sidebar-header">ADMINISTRASI</a>
                    <a href="./tunggakan.php" class="dashboard-sidebar-item">
                        <i class="ri-money-dollar-circle-line"></i>
                        Tunggakan
                    </a>
                    <a href="./umpan_balik_instruktur.php" class="dashboard-sidebar-item">
                        <i class="ri-feedback-line"></i>
                        Umpan Balik
                    </a>
                    <a href="./pengajuan.php" class="dashboard-sidebar-item">
                        <i class="ri-mail-send-line"></i>
                        Pengajuan
                    </a>
                </div>
                <div class="dashboard-sidebar-menu">
                    <a class="dashboard-sidebar-header">PENGATURAN</a>
                    <a href="./pengaturan.php" class="dashboard-sidebar-item">
                        <i class="ri-settings-4-line"></i>
                        Preferensi
                    </a>
                    <a href="../../api/auth/logout.php" class="dashboard-sidebar-item">
                        <i class="ri-door-open-line"></i>
                        Logout
                    </a>
                </div>
            <?php endif ?>

            <?php if ($_SESSION['role'] === 'instruktur') : ?>
                <div class="dashboard-sidebar-menu">
                    <a class="dashboard-sidebar-header">MENU</a>
                    <a href="./index.php" class="dashboard-sidebar-item">
                        <i class="ri-bar-chart-2-line"></i>
                        Dashboard
                    </a>
                </div>
                <div class="dashboard-sidebar-menu">
                    <a class="dashboard-sidebar-header">KBM</a>
                    <a href="./jadwal.php" class="dashboard-sidebar-item">
                        <i class="ri-calendar-2-line"></i>
                        Jadwal
                    </a>
                    <a href="./pertemuan.php" class="dashboard-sidebar-item">
                        <i class="ri-calendar-event-line"></i>
                        Pertemuan
                    </a>
                    <a href="./pertemuan_hari_ini.php" class="dashboard-sidebar-item">
                        <i class="ri-calendar-event-line"></i>
                        Pertemuan hari ini
                    </a>
                </div>
                <div class="dashboard-sidebar-menu">
                    <a class="dashboard-sidebar-header">ADMINISTRASI</a>
                    <a href="./umpan_balik_instruktur.php" class="dashboard-sidebar-item">
                        <i class="ri-feedback-line"></i>
                        Umpan Balik
                    </a>
                    <a href="./analisis_pendapatan.php" class="dashboard-sidebar-item">
                        <i class="ri-line-chart-line"></i>
                        Analisis Pendapatan
                    </a>
                </div>
                <div class="dashboard-sidebar-menu">
                    <a class="dashboard-sidebar-header">PENGATURAN</a>
                    <a href="./pengaturan.php" class="dashboard-sidebar-item">
                        <i class="ri-settings-4-line"></i>
                        Preferensi
                    </a>
                    <a href="../../api/auth/logout.php" class="dashboard-sidebar-item">
                        <i class="ri-door-open-line"></i>
                        Logout
                    </a>
                </div>
            <?php endif ?>

            <?php if ($_SESSION['role'] === 'admin') : ?>
                <div class="dashboard-sidebar-menu">
                    <a class="dashboard-sidebar-header">MENU</a>
                    <a href="./index.php" class="dashboard-sidebar-item">
                        <i class="ri-pie-chart-line"></i>
                        Dashboard
                    </a>
                    <a href="./admin.php" class="dashboard-sidebar-item">
                        <i class="ri-user-settings-line"></i>
                        Admin
                    </a>
                    <a href="./instruktur.php" class="dashboard-sidebar-item">
                        <i class="ri-user-2-line"></i>
                        Instruktur
                    </a>
                    <a href="./siswa.php" class="dashboard-sidebar-item">
                        <i class="ri-user-line"></i>
                        Siswa
                    </a>
                </div>
                <div class="dashboard-sidebar-menu">
                    <a class="dashboard-sidebar-header">KBM</a>
                    <a href="./jadwal.php" class="dashboard-sidebar-item">
                        <i class="ri-calendar-2-line"></i>
                        Jadwal
                    </a>
                    <a href="./pertemuan.php" class="dashboard-sidebar-item">
                        <i class="ri-star-line"></i>
                        Penilaian
                    </a>
                    <a href="./pertemuan_hari_ini.php" class="dashboard-sidebar-item">
                        <i class="ri-check-line"></i>
                        Presensi
                    </a>
                    
                </div>
                <div class="dashboard-sidebar-menu">
                    <a class="dashboard-sidebar-header">ADMINISTRASI</a>
                    <a href="./umpan_balik_instruktur.php" class="dashboard-sidebar-item">
                        <i class="ri-feedback-line"></i>
                        Umpan Balik
                    </a>
                    <a href="./analisis_pendapatan.php" class="dashboard-sidebar-item">
                        <i class="ri-line-chart-line"></i>
                        Analisis Pendapatan
                    </a>
                </div>
                <div class="dashboard-sidebar-menu">
                    <a class="dashboard-sidebar-header">PENGATURAN</a>
                    <a href="./pengaturan.php" class="dashboard-sidebar-item">
                        <i class="ri-settings-4-line"></i>
                        Preferensi
                    </a>
                    <a href="../../api/auth/logout.php" class="dashboard-sidebar-item">
                        <i class="ri-door-open-line"></i>
                        Logout
                    </a>
                </div>
            <?php endif ?>
        </nav>
    </div>
</aside>