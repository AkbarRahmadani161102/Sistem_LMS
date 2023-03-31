<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('siswa');
?>

<div id="pertemuan" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Pertemuan', 'filename' => 'pertemuan.php']]);
            ?>
            
            <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Pertemuan Kelas 2B</h4>

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3"></th>
                            <th scope="col" class="px-6 py-3">Mata Pelajaran</th>
                            <th scope="col" class="px-6 py-3">Instruktur</th>
                            <th scope="col" class="px-6 py-3">Tanggal</th>
                            <th scope="col" class="px-6 py-3">Jam Mulai</th>
                            <th scope="col" class="px-6 py-3">Jam Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th class="px-6 py-4 text-amber-500">1</th>
                            <td class="px-6 py-4">Bahasa Indonesia</td>
                            <td class="px-6 py-4">Mega</td>
                            <td class="px-6 py-4">12 Maret 2022</td>
                            <td class="px-6 py-4">13.00</td>
                            <td class="px-6 py-4">14.00</td>
                        </tr>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th class="px-6 py-4 text-amber-500">2</th>
                            <td class="px-6 py-4">Seni Budaya</td>
                            <td class="px-6 py-4">Maharani</td>
                            <td class="px-6 py-4">12 Maret 2022</td>
                            <td class="px-6 py-4">14.00</td>
                            <td class="px-6 py-4">15.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>