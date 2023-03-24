<?php
include_once '../../api/util/db.php';
include_once '../components/dashboard_breadcrumb.php';
$sql = "SELECT * FROM instruktur";
$data_instruktur = $db->query($sql);
?>

<div class="max-w-full flex text-gray-800 dark:text-white">
    <div class="w-full flex items-center justify-end h-fit gap-3">
        <button data-drawer-target="sidebar-multi-level-sidebar" data-drawer-toggle="sidebar-multi-level-sidebar" aria-controls="sidebar-multi-level-sidebar" type="button" class="inline-flex items-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600 mr-auto">
            <span class="sr-only">Open sidebar</span>
            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
            </svg>
        </button>

        <label class="pl-4 bg-white dark:bg-slate-700 lg:mr-auto rounded-full w-3/6 flex items-center">
            <i class="ri-search-line"></i>
            <input type="text" class="bg-transparent border-0 w-full rounded-full" placeholder="Search">
        </label>
        <p id="live-clock" class="m-0 me-3 font-semibold"></p>

        <a href="./notifikasi.php" class="rounded-full px-3 py-1.5 border hover:bg-amber-500 hover:border-amber-500 hover:text-white transition active:translate-y-1"><i class="ri-notification-line"></i></a>

        <hr class="vr hidden md:block">
        <img class="w-10 h-10 rounded-full border border-amber-500 border" src="https://images.pexels.com/photos/302769/pexels-photo-302769.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="User image">
        <div class="flex-col ml-3 hidden md:flex w-fit">
            <p class="m-0 text-secondary font-semibold"><?= $_SESSION['nama'] ?></p>
            <p class="m-0 text-secondary"><?= ucfirst($_SESSION['role']) ?></p>
        </div>

        <button data-dropdown-toggle="dropdown" class="ml-5" type="button"><i class="ri-arrow-down-s-line text-2xl hover:text-amber-500"></i></button>
        <div id="dropdown" class="z-10 hidden bg-white rounded-lg shadow-lg">
            <ul class="text-sm text-gray-700">
                <a href="./pengaturan.php" class="block px-4 py-2 hover:bg-amber-500 rounded hover:text-white gap-2 flex align-center"><i class="ri-settings-4-line"></i> Pengaturan</a>
                <button id="theme-toggle" type="button" class="block px-4 py-2 hover:bg-amber-500 rounded hover:text-white gap-2 flex align-center w-full">
                    <i id="theme-toggle-dark-icon" class="hidden ri-moon-line"></i>
                    <span id="theme-toggle-dark-icon-text" class="hidden font-medium">Light Mode</span>
                    <i id="theme-toggle-light-icon" class="hidden ri-sun-line"></i>
                    <span id="theme-toggle-light-icon-text" class="hidden font-medium">Dark Mode</span>
                </button>
                <hr>
                <a href="../../api/auth/logout.php" class="block px-4 py-2 hover:bg-amber-500 rounded hover:text-white gap-2 flex align-center"><i class="ri-door-open-line"></i> Keluar</a>
            </ul>
        </div>
    </div>
</div>