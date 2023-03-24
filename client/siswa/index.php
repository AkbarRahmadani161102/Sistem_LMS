<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('siswa');
?>

<div id="index" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Dashboard', 'filename' => 'index.php']]);
            ?>
           
            <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Jadwal Minggu Ini</h4>

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3"></th>
                            <th scope="col" class="px-6 py-3">Senin</th>
                            <th scope="col" class="px-6 py-3">Selasa</th>
                            <th scope="col" class="px-6 py-3">Rabu</th>
                            <th scope="col" class="px-6 py-3">Kamis</th>
                            <th scope="col" class="px-6 py-3">Jumat</th>
                            <th scope="col" class="px-6 py-3">Sabtu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th class="px-6 py-4 text-amber-500">13.00 - 14.00</th>
                            <td class="px-6 py-4">Matematika</td>
                            <td class="px-6 py-4"></td>
                            <td class="px-6 py-4">IPAS</td>
                            <td class="px-6 py-4"></td>
                            <td class="px-6 py-4"></td>
                        </tr>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th class="px-6 py-4 text-amber-500">14.00 - 15.00</th>
                            <td class="px-6 py-4"></td>
                            <td class="px-6 py-4">Bahasa Indonesia</td>
                            <td class="px-6 py-4"></td>
                            <td class="px-6 py-4">Biologi</td>
                            <td class="px-6 py-4"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <a class="text-gray-400 text-semibold flex items-center mt-5 group hover:text-amber-500" href="./jadwal.php">
                <span>Lihat lebih lanjut</span>
                <span class="text-xl group-hover:translate-x-2 transition"><i class="ri-arrow-right-s-line align-bottom"></i></span>
            </a>

        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>