<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('siswa');
?>

<div class="w-full min-h-screen flex">
    <?php include_once './components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <?php include_once './components/dashboard_navbar.php' ?>
        <div class="w-full px-10">

            <div class="flex items-center gap-2">
                <a class="text-xl text-gray-400 hover:text-amber-500 transition" href="#">Home</a>
                <i class="ri-arrow-right-s-line text-gray-400 text-xl"></i>
                <a class="text-xl text-slate-800 hover:text-amber-500 transition" href="#">Dashboard</a>
            </div>
            <h4 class="mt-7 font-semibold">Jadwal Minggu Ini</h4>
            <table class="mt-5 w-full text-sm text-left shadow-xl rounded">
                <thead class="text-xs text-gray-700 uppercase bg-white">
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
                    <tr class="bg-white">
                        <th class="px-6 py-4 text-amber-500">13.00 - 14.00</th>
                        <td class="px-6 py-4">Matematika</td>
                        <td class="px-6 py-4"></td>
                        <td class="px-6 py-4">IPAS</td>
                        <td class="px-6 py-4"></td>
                        <td class="px-6 py-4"></td>
                    </tr>
                    <tr class="bg-white">
                        <th class="px-6 py-4 text-amber-500">14.00 - 15.00</th>
                        <td class="px-6 py-4"></td>
                        <td class="px-6 py-4">Bahasa Indonesia</td>
                        <td class="px-6 py-4"></td>
                        <td class="px-6 py-4">Biologi</td>
                        <td class="px-6 py-4"></td>
                    </tr>
                </tbody>
            </table>
            <a class="text-gray-400 text-semibold flex items-center mt-5 group hover:text-amber-500" href="#">
                <span>Lihat lebih lanjut</span>
                <span class="text-xl group-hover:translate-x-2 transition"><i class="ri-arrow-right-s-line align-bottom"></i></span>
            </a>

        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>