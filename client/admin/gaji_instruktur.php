<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('admin', 'gaji_instruktur.php');

$sql = "SELECT MONTH(tgl_pertemuan) bulan FROM detail_jadwal GROUP BY MONTH(tgl_pertemuan) ORDER BY bulan DESC";
$bulan_pertemuan = $db->query($sql) or die($db->error);

$sql = "SELECT YEAR(tgl_pertemuan) tahun FROM detail_jadwal GROUP BY YEAR(tgl_pertemuan) ORDER BY tahun DESC";
$tahun_pertemuan = $db->query($sql) or die($db->error);

if (isset($_GET['tahun']) && isset($_GET['bulan'])) {
    $tahun = $_GET['tahun'];
    $bulan = $_GET['bulan'];
    $sql = "SELECT *, g.tgl_dibuat FROM gaji g RIGHT JOIN instruktur i ON g.id_instruktur = i.id_instruktur WHERE YEAR(g.tgl_dibuat) = $tahun AND MONTH(g.tgl_dibuat) = $bulan";
    $data_gaji = $db->query($sql) or die($db->error);
    $data_gaji->fetch_assoc();
} else {
    $sql = "SELECT *, SUM(nominal) total_nominal FROM gaji g RIGHT JOIN instruktur i ON g.id_instruktur = i.id_instruktur WHERE tgl_penerimaan IS NOT NULL GROUP BY g.id_instruktur";
    $data_gaji = $db->query($sql) or die($db->error);
    $data_gaji->fetch_assoc();
}
?>

<div class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Gaji Instruktur', 'filename' => 'gaji_instruktur.php']]);
            ?>

            <div class="flex items-center gap-5">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Gaji Instruktur</h4>
                <?php if (isset($_GET['tahun']) && isset($_GET['bulan'])) : ?>
                    <form action="../../api/admin/gaji_instruktur.php" method="post">
                        <input type="hidden" name="tahun" value="<?= $_GET['tahun'] ?>">
                        <input type="hidden" name="bulan" value="<?= $_GET['bulan'] ?>">
                        <button type="submit" name="sync" class="btn">Update data gaji instruktur</button>
                    </form>
                <?php endif ?>
            </div>

            <div class="flex flex-col w-fit text-gray-800 dark:text-white space-y-4 bg-gray-200 dark:bg-gray-700 p-5 rounded-lg">
                <h6 class="font-semibold">Filter Pendapatan</h6>
                <form action="../../api/admin/gaji_instruktur.php" method="post">
                    <div class="flex gap-5">
                        <div class="flex flex-col gap-2">
                            <label for="tb">Tahun</label>
                            <select id="tb" class="selectize-redirect input" name="tahun">
                                <?php foreach ($tahun_pertemuan as $tahun) : ?>
                                    <option value="<?= $tahun['tahun'] ?>" <?= isset($_GET['tahun']) && $tahun['tahun'] === $_GET['tahun'] ? 'selected' : '' ?>><?= $tahun['tahun'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="tb">Bulan</label>
                            <select id="tb" class="selectize-redirect input" name="bulan">
                                <?php foreach ($bulan_pertemuan as $bulan) : ?>
                                    <option value="<?= $bulan['bulan'] ?>" <?= isset($_GET['bulan']) && $bulan['bulan'] === $_GET['bulan'] ? 'selected' : '' ?>><?= BULAN[$bulan['bulan'] - 1] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="flex flex-1 flex-col gap-2 justify-end">
                            <button name="redirect" type="submit" class="input btn">Filter</button>
                        </div>
                    </div>
                </form>
            </div>

            <?php if (isset($_GET['tahun']) && isset($_GET['bulan'])) : ?>
                <div class="table__container">
                    <table class="datatable table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Nama Instruktur</th>
                                <th>Tanggal Penerimaan</th>
                                <th>Nominal (Rp)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data_gaji as $key => $gaji) : ?>
                                <tr>
                                    <th><?= $key + 1 ?></th>
                                    <td><?= $gaji['nama'] ?></td>
                                    <td><?= $gaji['tgl_penerimaan'] ?></td>
                                    <td><?= $gaji['nominal'] ? $gaji['nominal'] : 0 ?></td>
                                    <td>
                                        <?php if ($gaji['nominal']) : ?>
                                            <?php if (!$gaji['tgl_penerimaan']) : ?>
                                                <button data-modal-target="edit<?= $gaji['id_gaji'] ?>" data-modal-toggle="edit<?= $gaji['id_gaji'] ?>" class="btn btn--outline-green">Tandai telah diterima</button>
                                                <div id="edit<?= $gaji['id_gaji'] ?>" tabindex="-1" aria-hidden="true" class="modal">
                                                    <div class="modal__backdrop">
                                                        <!-- Modal content -->
                                                        <div class="modal__content">
                                                            <!-- Modal header -->
                                                            <div class="modal__header">
                                                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                                    Masukkan tanggal penerimaan
                                                                </h3>
                                                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="edit<?= $gaji['id_gaji'] ?>">
                                                                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                    <span class="sr-only">Close modal</span>
                                                                </button>
                                                            </div>
                                                            <form class="form" action="../../api/admin/gaji_instruktur.php" method="post">
                                                                <!-- Modal body -->
                                                                <div class="modal__body">
                                                                    <label for="tgl_penerimaan">Tanggal penerimaan</label>
                                                                    <input class="input" type="date" name="tgl_penerimaan" id="tgl_penerimaan" value="<?= $gaji['tgl_dibuat'] ?>" required>
                                                                </div>
                                                                <!-- Modal footer -->
                                                                <div class="modal__footer">
                                                                    <button type="submit" name="update" value="<?= $gaji['id_gaji'] ?>" class="btn btn--blue">Tandai sebagai lunas</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php else : ?>
                                                <form action="../../api/admin/gaji_instruktur.php" method="post">
                                                    <input type="hidden" name="tgl_penerimaan" value="NULL">
                                                    <button type="submit" name="update" value="<?= $gaji['id_gaji'] ?>" class="btn btn--outline-amber flex items-center gap-1">
                                                        <i class="ri-refresh-line"></i>
                                                        Reset
                                                    </button>
                                                </form>
                                            <?php endif ?>

                                        <?php else : ?>
                                            <button class="btn btn--transparent">&nbsp;</button>
                                        <?php endif ?>
                                    </td>
                                <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <div class="table__container">
                    <table class="datatable table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Nama Instruktur</th>
                                <th>Total Pendapatan (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data_gaji as $key => $gaji) : ?>
                                <tr>
                                    <th><?= $key + 1 ?></th>
                                    <td><?= $gaji['nama'] ?></td>
                                    <td><?= $gaji['total_nominal'] ? $gaji['total_nominal'] : 0 ?></td>
                                <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php endif ?>

        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>