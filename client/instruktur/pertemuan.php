<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
include_once('../../api/util/db.php');
user_access('instruktur');

// $id_instruktur = $_SESSION['user_id'];
// $sql = "SELECT * FROM detail_jadwal dj, jadwal j, mapel m, detail_kelas dk, kelas k, instruktur i, instruktur s WHERE dj.id_jadwal = j.id_jadwal AND dj.id_instruktur = i.id_instruktur AND j.id_mapel = m.id_mapel AND j.id_kelas = k.id_kelas AND dk.id_kelas = k.id_kelas AND dk.id_instruktur = s.id_instruktur AND s.id_instruktur = '$id_instruktur'";
// $data_pertemuan = $db->query($sql);
?>

<div id="pertemuan" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Pertemuan', 'filename' => 'pertemuan.php']]);
            ?>
            <div class="flex justify-between items-center">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Data Pertemuan Bulan Agustus</h4>
                <a href="./penilaian.php?id=blablabla" class="px-4 py-2 bg-green-300 rounded">Input penilaian hari ini</a>
            </div>
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center"></th>
                            <th scope="col" class="px-6 py-3 text-center">13.00</th>
                            <th scope="col" class="px-6 py-3 text-center">14.00</th>
                            <th scope="col" class="px-6 py-3 text-center">14.00</th>
                            <th scope="col" class="px-6 py-3 text-center">15.00</th>
                            <th scope="col" class="px-6 py-3 text-center">15.00</th>
                            <th scope="col" class="px-6 py-3 text-center">16.00</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th class="px-6 py-4 text-amber-500 text-center">
                                <p>Selasa</p>
                                <p>7 Agustus 2023</p>
                            </th>
                            <td class="px-6 py-4 text-center">
                                <h6>1A</h6>
                                <p>Fiber Optik</p>
                            </td>
                            <td class="px-6 py-4 text-center"></td>
                            <td class="px-6 py-4 text-center">
                                <h6>4B</h6>IPAS
                            </td>
                            <td class="px-6 py-4 text-center"></td>
                            <td class="px-6 py-4 text-center"></td>
                        </tr>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th class="px-6 py-4 text-amber-500 text-center">
                                <p>Senin</p>
                                <p>8 Agustus 2023</p>
                            </th>
                            <td class="px-6 py-4 text-center"></td>
                            <td class="px-6 py-4 text-center">
                                <h6>3A</h6>
                                <p>Matematika</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <h6>3C</h6>
                                <p>Matematika</p>
                            </td>
                            <td class="px-6 py-4 text-center"></td>
                            <td class="px-6 py-4 text-center"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>