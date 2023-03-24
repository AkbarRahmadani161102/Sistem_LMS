<?php
include_once('../template/header.php');
user_access('instruktur');
?>

<div id="pertemuan" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Pertemuan', 'filename' => 'pertemuan.php']]);
            ?>

            <?php if (!isset($_GET['id'])) : ?>
                <div class="flex justify-between items-center">
                    <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Data Pertemuan Hari ini</h4>
                </div>
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center"></th>
                                <th scope="col" class="px-6 py-3 text-center">14.30</th>
                                <th scope="col" class="px-6 py-3 text-center">15.30</th>
                                <th scope="col" class="px-6 py-3 text-center">16.30</th>
                                <th scope="col" class="px-6 py-3 text-center">17.30</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th class="px-6 py-4 text-amber-500 text-center">
                                    <p>Senin</p>
                                    <p>7 Agustus 2023</p>
                                </th>
                                <td class="px-6 py-4 text-center border group hover:px-0 hover:border-transparent relative" colspan="2">
                                    <h6>1A</h6>
                                    <p>Analisa Kegagalan Material</p>
                                    <a href="?id=123ok" class="w-full h-0 bg-green-500 group-hover:z-[100] absolute bottom-0 left-0 justify-center items-center hidden group-hover:flex group-hover:h-full active:bg-gradient-to-r active:from-cyan-500 active:to-blue-500 transition-all text-xl text-white">Pilih</a>
                                </td>
                                <td class="px-6 py-4 text-center border group hover:px-0 hover:border-transparent relative">
                                    <h6>2B</h6>
                                    <p>Kinematika & Dinamika Lanjut</p>
                                    <a href="?id=123ok" class="w-full h-0 bg-green-500 group-hover:z-[100] absolute bottom-0 left-0 justify-center items-center hidden group-hover:flex group-hover:h-full active:bg-gradient-to-r active:from-cyan-500 active:to-blue-500 transition-all text-xl text-white">Pilih</a>
                                </td>
                                <td class="px-6 py-4 text-center"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php endif ?>

            <?php if (isset($_GET['id'])) : ?>
                <div class="flex justify-between items-center">
                    <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Pertemuan Kelas 2A</h4>
                </div>

                <div id="accordion-open" data-accordion="open">
                    <h2 id="accordion-open-heading-1">
                        <button type="button" class="flex items-center justify-between w-full p-5 font-medium text-left text-xl text-gray-500 border border-b-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800" data-accordion-target="#accordion-open-body-1" aria-expanded="true" aria-controls="accordion-open-body-1">
                            Absensi Siswa
                        </button>
                    </h2>
                    <div id="accordion-open-body-1" class="hidden" aria-labelledby="accordion-open-heading-1">
                        <form action="">
                            <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900">
                                <div class="relative overflow-x-auto">
                                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-center">#</th>
                                                <th scope="col" class="px-6 py-3 text-center">Nama Siswa</th>
                                                <th scope="col" class="px-6 py-3 text-center" colspan="3">Status Kehadiran</th>
                                            </tr>
                                            <div class="tr">
                                                <th class="px-6 py-3 text-center" colspan="2"></th>
                                                <th scope="col" class="px-6 py-3 text-center">Hadir</th>
                                                <th scope="col" class="px-6 py-3 text-center">Izin</th>
                                                <th scope="col" class="px-6 py-3 text-center">Tanpa Keterangan</th>
                                            </div>
                                        </thead>
                                        <tbody>
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                <th class="px-6 py-4 text-amber-500 text-center">1</th>
                                                <td class="px-6 py-4 text-center">Putin</td>
                                                <td class="px-6 py-4 text-center">
                                                    <input id="kehadiran" type="radio" value="" name="kehadiran" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" checked>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <input id="kehadiran" type="radio" value="" name="kehadiran" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <input id="kehadiran" type="radio" value="" name="kehadiran" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                </td>
                                            </tr>
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                <th class="px-6 py-4 text-amber-500 text-center">2</th>
                                                <td class="px-6 py-4 text-center">Mega</td>
                                                <td class="px-6 py-4 text-center">
                                                    <input id="kehadiran" type="radio" value="" name="kehadiran2" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" checked>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <input id="kehadiran" type="radio" value="" name="kehadiran2" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <input id="kehadiran" type="radio" value="" name="kehadiran2" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                </td>
                                            </tr>
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                <th class="px-6 py-4 text-amber-500 text-center">3</th>
                                                <td class="px-6 py-4 text-center">Wati</td>
                                                <td class="px-6 py-4 text-center">
                                                    <input id="kehadiran" type="radio" value="" name="kehadiran3" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" checked>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <input id="kehadiran" type="radio" value="" name="kehadiran3" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <input id="kehadiran" type="radio" value="" name="kehadiran3" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                </td>
                                            </tr>
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                <th class="px-6 py-4 text-amber-500 text-center">4</th>
                                                <td class="px-6 py-4 text-center">Ginsar</td>
                                                <td class="px-6 py-4 text-center">
                                                    <input id="kehadiran" type="radio" value="" name="kehadiran4" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" checked>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <input id="kehadiran" type="radio" value="" name="kehadiran4" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <input id="kehadiran" type="radio" value="" name="kehadiran4" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <button type="submit" href="./penilaian.php?id=blablabla" class="block ml-auto mt-5 px-4 py-2 bg-green-300 rounded">Selesai Absensi</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <h2 id="accordion-open-heading-2">
                        <button type="button" class="flex items-center justify-between w-full p-5 font-medium text-left text-xl text-gray-500 border border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800" data-accordion-target="#accordion-open-body-2" aria-expanded="false" aria-controls="accordion-open-body-2">
                            Data Penilaian
                        </button>
                    </h2>
                    <div id="accordion-open-body-2" class="hidden" aria-labelledby="accordion-open-heading-2">
                        <form action="">
                            <input type="text" class="w-50 m-5" name="nama_penilaian" placeholder="Nama Penilaian" required>
                            <div class="relative overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-center">#</th>
                                            <th scope="col" class="px-6 py-3 text-center">Nama Siswa</th>
                                            <th scope="col" class="px-6 py-3 text-center" colspan="3">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <th class="px-6 py-4 text-amber-500 text-center">1</th>
                                            <td class="px-6 py-4 text-center">Putin</td>
                                            <td class="px-6 py-4 text-center">
                                                <input type="number" name="nilai">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="submit" href="./penilaian.php?id=blablabla" class="block ml-auto mt-5 px-4 py-2 bg-green-300 rounded">Selesai Penilaian</button>
                            </div>
                        </form>
                    </div>
                </div>

            <?php endif ?>

        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>