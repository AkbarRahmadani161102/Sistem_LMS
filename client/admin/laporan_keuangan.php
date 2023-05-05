<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('admin', 'laporan_keuangan.php');

$sql = "SELECT  YEAR(tgl_pertemuan) tahun, MONTH(tgl_pertemuan) bulan FROM detail_jadwal GROUP BY tahun";
$result = $db->query($sql) or die($sql);
$result->fetch_assoc();
?>

<div class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php'; ?>

            <div class="flex items-center gap-5">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Laporan Keuangan</h4>
            </div>

            <?php generate_breadcrumb([['title' => 'Laporan Keuangan', 'filename' => 'laporan_keuangan.php']]); ?>
            <div class="relative overflow-x-auto mt-5">
                <table class="datatable w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3"></th>
                            <th scope="col" class="px-6 py-3">Tahun</th>
                            <th scope="col" class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $key => $value) : ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th class="px-6 py-4 text-amber-500"><?= $key + 1 ?></th>
                                <td class="px-6 py-4"><?= $value['tahun'] ?></td>
                                <td class="px-6 py-4 flex gap-4">
                                    <a href="../../api/admin/laporan_keuangan.php?tahun=<?= $value['tahun'] ?>&bulan=<?= $value['bulan'] ?>" class="btn btn--outline-cyan flex items-center gap-2"><i class="ri-file-chart-line"></i> Lihat Laporan</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>