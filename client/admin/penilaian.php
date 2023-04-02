<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access(['Super Admin', 'Admin Akademik']);

$sql = "SELECT * FROM penilaian";
$result = $db->query($sql) or die($sql);
$result->fetch_assoc();
?>

<div id="jenjang" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php'; ?>

            <div class="flex items-center gap-5">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Penilaian</h4>
            </div>

            <?php generate_breadcrumb([['title' => 'Penilaian', 'filename' => 'penilaian.php']]); ?>
            <div class="relative overflow-x-auto mt-5">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3"></th>
                            <th scope="col" class="px-6 py-3">Judul Penilaian</th>
                            <th scope="col" class="px-6 py-3">Keterangan Penilaian</th>
                            <th scope="col" class="px-6 py-3">Instruktur</th>
                            <th scope="col" class="px-6 py-3">Kelas</th>
                            <th scope="col" class="px-6 py-3">Mapel</th>
                            <th scope="col" class="px-6 py-3">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $key => $value) : ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th class="px-6 py-4 text-amber-500"><?= $key + 1 ?></th>
                                <td class="px-6 py-4"><?= $value['judul_penilaian'] ?></td>
                                <td class="px-6 py-4"><?= $value['keterangan_penilaian'] ?></td>
                                <td class="px-6 py-4"></td>
                                <td class="px-6 py-4"></td>
                                <td class="px-6 py-4"></td>
                                <td class="px-6 py-4"><?= $value['tgl_penilaian'] ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php
$result->free_result();
include_once('../template/footer.php') ?>