<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('siswa');

$id_siswa = $_SESSION['user_id'];

$sql = "SELECT t.* FROM tunggakan t
JOIN siswa s on s.id_siswa = t.id_siswa 
WHERE s.id_siswa = '$id_siswa'";
$data_tunggakan = $db->query($sql) or die($db->error);
$data_tunggakan->fetch_assoc();
?>

<div id="tunggakan" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Pengaturan', 'filename' => 'pengaturan.php']]);
            ?>

            <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Tunggakan</h4>

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3"></th>
                            <th scope="col" class="px-6 py-3">Tanggal Pembayaran</th>
                            <th scope="col" class="px-6 py-3">Tenggat Pembayaran</th>
                            <th scope="col" class="px-6 py-3">Nominal (Rp)</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_tunggakan as $key => $tunggakan) : ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th class="px-6 py-4 text-amber-500"><?= $key + 1 ?></th>
                            <td class="px-6 py-4"><?= $tunggakan['tgl_pembayaran'] ?></td>
                            <td class="px-6 py-4"><?= $tunggakan['tenggat_pembayaran'] ?></td>
                            <td class="px-6 py-4"><?= $tunggakan['nominal'] ?></td>
                            <td class="px-6 py-4 <?= $tunggakan['status'] === 'Lunas' ? 'text-green-500' : 'text-red-500'?> "><?= $tunggakan['status'] === 'Lunas' ? $tunggakan['status'] : 'Belum Terbayar' ?></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>