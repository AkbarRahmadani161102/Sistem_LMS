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
                            <th scope="col" class="px-6 py-3">Tahun</th>
                            <th scope="col" class="px-6 py-3">Laporan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $key => $value) : ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="px-6 py-4"><?= $value['tahun'] ?></td>
                                <td class="px-6 py-4 flex gap-4 flex-wrap">
                                    <button data-popover-target="popover-laporan-<?= $key ?>" data-popover-placement="right" type="button" class="btn btn--outline-blue">Lihat Laporan</button>
                                    <div data-popover id="popover-laporan-<?= $key ?>" role="tooltip" class="absolute z-10 invisible inline-block w-64 text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800">
                                        <div class="px-3 py-2 bg-gray-100 border-b border-gray-200 rounded-t-lg dark:border-gray-600 dark:bg-gray-700">
                                            <h5 class="font-semibold text-gray-900 dark:text-white">Laporan</h5>
                                        </div>
                                        <div class="flex flex-col gap-2 p-5">
                                            <a href="../../api/admin/laporan_keuangan.php?jurnal_umum&tahun=<?= $value['tahun'] ?>&bulan=<?= $value['bulan'] ?>" class="btn btn--outline-cyan flex items-center gap-2"><i class="ri-file-chart-line"></i> Laporan Jurnal Umum</a>
                                            <a href="../../api/admin/laporan_keuangan.php?laporan_transport&tahun=<?= $value['tahun'] ?>&bulan=<?= $value['bulan'] ?>" class="btn btn--outline-cyan flex items-center gap-2"><i class="ri-file-transfer-line"></i> Laporan Transport Instruktur</a>
                                        </div>
                                        <div data-popper-arrow></div>
                                    </div>
                                    <button data-modal-target="slip_gaji_instruktur_modal<?= $key ?>" data-modal-toggle="slip_gaji_instruktur_modal<?= $key ?>" class="btn btn--outline-cyan flex items-center gap-2">
                                        <i class="ri-file-list-3-line"></i> Slip Gaji Instruktur
                                    </button>
                                    <div id="slip_gaji_instruktur_modal<?= $key ?>" tabindex="-1" aria-hidden="true" class="modal">
                                        <div class="modal__backdrop">
                                            <!-- Modal content -->
                                            <form action="../../api/admin/laporan_keuangan.php?slip_gaji_instruktur" method="get" class="form">
                                                <div class="modal__content">
                                                    <!-- Modal header -->
                                                    <div class="modal__header">
                                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                            Pilih bulan
                                                        </h3>
                                                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="slip_gaji_instruktur_modal<?= $key ?>">
                                                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            <span class="sr-only">Close modal</span>
                                                        </button>
                                                    </div>
                                                    <!-- Modal body -->
                                                    <div class="modal__body">
                                                        <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                                            Pilih bulan untuk melakukan proses generate slip gaji
                                                        </p>
                                                        <input type="hidden" name="slip_gaji_instruktur" value="NULL">
                                                        <input type="hidden" name="tahun" value="<?= $value['tahun'] ?>">
                                                        <select name="bulan" class="input">
                                                            <?php
                                                            $sql = "SELECT DISTINCT MONTH(tgl_pertemuan) bulan FROM detail_jadwal WHERE YEAR(tgl_pertemuan) = {$value['tahun']}";
                                                            $data_bulan_per_tahun = $db->query($sql) or die($db->error); ?>
                                                            <?php foreach ($data_bulan_per_tahun as $data_bulan) : ?>
                                                                <option value="<?= $data_bulan['bulan'] ?>"><?= BULAN[$data_bulan['bulan'] - 1] ?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>
                                                    <!-- Modal footer -->
                                                    <div class="modal__footer">
                                                        <button type="submit" class="btn btn--blue">Generate Slip Gaji</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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