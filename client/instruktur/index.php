<?php
include_once('../template/header.php');
user_access('instruktur');
$id_instruktur = $_SESSION['user_id'];
?>

<div id="index" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Dashboard', 'filename' => 'index.php']]);
            ?>

            <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Jadwal</h4>

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center">Jam Mulai</th>
                            <th scope="col" class="px-6 py-3 text-center">Senin</th>
                            <th scope="col" class="px-6 py-3 text-center">Selasa</th>
                            <th scope="col" class="px-6 py-3 text-center">Rabu</th>
                            <th scope="col" class="px-6 py-3 text-center">Kamis</th>
                            <th scope="col" class="px-6 py-3 text-center">Jumat</th>
                            <th scope="col" class="px-6 py-3 text-center">Sabtu</th>
                            <th scope="col" class="px-6 py-3 text-center">Minggu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (WAKTU as $key => $waktu) : ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th class="px-6 py-4 text-amber-500 text-center"><?= $waktu ?></th>
                                <?php foreach (HARI as $hari) : ?>
                                    <?php
                                    $sql = "SELECT j.*, k.nama nama_kelas, m.nama nama_mapel FROM jadwal j
                                    JOIN kelas k ON j.id_kelas = k.id_kelas
                                    JOIN mapel m ON j.id_mapel = m.id_mapel
                                    WHERE id_instruktur = '$id_instruktur'
                                    AND jam_mulai = '$waktu' AND hari = '$hari'";

                                    $data_per_hari = $db->query($sql) or die($db->error);
                                    $data_per_hari = $data_per_hari->fetch_assoc(); ?>

                                    <?php if ($data_per_hari) : ?>
                                        <td class="px-6 py-4 text-center">
                                            <h6><?= $data_per_hari['nama_kelas'] ?></h6>
                                            <p><?= $data_per_hari['nama_mapel'] ?></p>
                                        </td>
                                    <?php else : ?>
                                        <td class="px-6 py-4 text-center">
                                            <h6>&nbsp;</h6>
                                            <p>&nbsp;</p>
                                        </td>
                                    <?php endif ?>

                                <?php endforeach ?>
                            </tr>
                        <?php endforeach ?>
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