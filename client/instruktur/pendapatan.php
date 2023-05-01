<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('instruktur');

$id_instruktur = $_SESSION['user_id'];
$sql = "SELECT * FROM gaji WHERE id_instruktur = '$id_instruktur'";
$data_pendapatan = $db->query($sql) or die($sql);
$data_pendapatan->fetch_assoc();
?>

<div id="pendapatan" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php'; ?>

            <div class="flex items-center gap-5">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Pendapatan</h4>
            </div>

            <?php generate_breadcrumb([['title' => 'Pendapatan', 'filename' => 'pendapatan.php']]); ?>
            <div class="relative overflow-x-auto mt-5">
                <table class="datatable w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3"></th>
                            <th scope="col" class="px-6 py-3">Nominal</th>
                            <th scope="col" class="px-6 py-3">Tanggal Penerimaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_pendapatan as $key => $value) : ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th class="px-6 py-4 text-amber-500"><?= $key + 1 ?></th>
                                <td class="px-6 py-4"><?= $value['nominal'] ?></td>
                                <td class="px-6 py-4">
                                    <?php if ($value['tgl_penerimaan']) : ?>
                                        <?= $value['tgl_penerimaan'] ?>
                                    <?php else : ?>
                                        <span class="text-amber-500">Belum Diterima</span>
                                    <?php endif ?>
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