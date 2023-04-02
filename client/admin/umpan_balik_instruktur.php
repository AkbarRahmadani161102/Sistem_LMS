<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access(['Super Admin', 'Admin Akademik']);

$sql = "SELECT k.*, s.nama nama_siswa, i.nama nama_instruktur FROM kuesioner_instruktur k, siswa s, instruktur i WHERE k.id_siswa = s.id_siswa AND k.id_instruktur = i.id_instruktur";
$result = $db->query($sql) or die($sql);
$result->fetch_assoc();
?>

<div id="jenjang" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php'; ?>

            <div class="flex items-center gap-5">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Jenjang</h4>
            </div>

            <?php generate_breadcrumb([['title' => 'Umpan Balik Instruktur', 'filename' => 'umpan_balik_instruktur.php']]); ?>
            <div class="relative overflow-x-auto mt-5">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3"></th>
                            <th scope="col" class="px-6 py-3">Siswa</th>
                            <th scope="col" class="px-6 py-3">Instruktur</th>
                            <th scope="col" class="px-6 py-3">Deskripsi</th>
                            <th scope="col" class="px-6 py-3">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $key => $value) : ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th class="px-6 py-4 text-amber-500"><?= $key + 1 ?></th>
                                <td class="px-6 py-4"><?= $value['nama_siswa'] ?></td>
                                <td class="px-6 py-4"><?= $value['nama_instruktur'] ?></td>
                                <td class="px-6 py-4"><?= $value['deskripsi'] ?></td>
                                <td class="px-6 py-4"><?= $value['tgl_dibuat'] ?></td>
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