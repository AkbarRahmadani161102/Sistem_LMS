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

        <img class="w-10 h-10 rounded-full border border-amber-500 border" src="../assets/image/example.png" alt="User image">
        <div class="flex-col ml-3 hidden md:flex w-fit">
            <p class="m-0 text-secondary font-semibold"><?= $_SESSION['nama'] ?></p>
            <p class="m-0 text-secondary">
                <?php
                if (isset($_SESSION['detail_role']))
                    foreach ($_SESSION['detail_role'] as $key => $value)
                        echo $value['title'] . '<br/>';
                else
                    echo ucfirst($_SESSION['role']);
                ?>
            </p>
        </div>

        <button data-dropdown-toggle="dropdown" class="ml-5" type="button"><i class="ri-arrow-down-s-line text-2xl hover:text-amber-500"></i></button>
        <div id="dropdown" class="z-10 hidden bg-white rounded-lg shadow-lg">
            <ul class="text-sm text-gray-700">
                <a href="../user/user_settings.php" class="block px-4 py-2 hover:bg-amber-500 rounded hover:text-white gap-2 flex align-center"><i class="ri-settings-4-line"></i> Pengaturan</a>
                <button id="theme-toggle" type="button" class="block px-4 py-2 hover:bg-amber-500 rounded hover:text-white gap-2 flex align-center w-full">
                    <i id="theme-toggle-dark-icon" class="hidden ri-moon-line"></i>
                    <span id="theme-toggle-dark-icon-text" class="hidden font-medium">Light Mode</span>
                    <i id="theme-toggle-light-icon" class="hidden ri-sun-line"></i>
                    <span id="theme-toggle-light-icon-text" class="hidden font-medium">Dark Mode</span>
                </button>
                <button data-modal-target="add_umpan_balik_sistem_modal" data-modal-toggle="add_umpan_balik_sistem_modal" class="block px-4 py-2 hover:bg-amber-500 rounded hover:text-white gap-2 flex align-center w-full">
                    <i class="ri-chat-settings-line"></i> <span class="font-medium">Feedback Sistem</span>
                </button>
                <hr>
                <a href="../../api/auth/logout.php" class="block px-4 py-2 hover:bg-amber-500 rounded hover:text-white gap-2 flex align-center"><i class="ri-door-open-line"></i>Keluar</a>
            </ul>
        </div>
    </div>
</div>

<div id="add_umpan_balik_sistem_modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
    <div class="relative w-full h-full max-w-2xl md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <form action="../../api/user/umpan_balik_sistem.php" class="flex flex-col gap-3" method="post">
                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <div class="flex flex-col text-gray-900 dark:text-white gap-3">
                        <h3 class="text-xl font-semibold">
                            Feedback Sistem
                        </h3>
                        <p class="text-sm">Silahkan isi kolom deskripsi untuk membantu kami dalam pengembangan sistem selanjutnya</p>
                    </div>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="add_umpan_balik_sistem_modal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 space-y-6">
                    <label for="deskripsi" class="text-gray-900 dark:text-white">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" cols="30" rows="10" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button type="submit" name="create" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>