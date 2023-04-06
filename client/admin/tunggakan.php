<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access(['Super Admin', 'Admin Akademik']);
$sql = "SELECT t.*, s.nama nama_siswa, k.nama nama_kelas, j.nama nama_jenjang FROM tunggakan t
JOIN siswa s ON t.id_siswa = s.id_siswa
JOIN detail_kelas dk ON s.id_siswa = dk.id_siswa
JOIN kelas k ON dk.id_kelas = k.id_kelas
JOIN jenjang j ON k.id_jenjang = j.id_jenjang";
$data_tunggakan = $db->query($sql) or die($db->error);
$data_tunggakan->fetch_assoc();

$sql = "SELECT *, k.nama nama_kelas, j.nama nama_jenjang FROM siswa s
JOIN detail_kelas dk ON dk.id_siswa = s.id_siswa 
JOIN kelas k ON k.id_kelas = dk.id_kelas 
JOIN jenjang j ON k.id_jenjang = j.id_jenjang
JOIN tunggakan t ON t.id_siswa = s.id_siswa";
$data_siswa = $db->query($sql) or die($db->error);
$data_siswa->fetch_assoc();
?>

<div id="tunggakan" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'tunggakan', 'filename' => 'tunggakan.php']]);
            ?>

            <div class="flex items-center gap-5">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Tunggakan</h4>
                <form action="../../api/admin/tunggakan.php" method="post">
                    <button type="submit" name="sync" class="btn">Update data tunggakan</button>
                </form>
            </div>


            <div class="relative overflow-x-auto">
                <table class="datatable w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3"></th>
                            <th scope="col" class="px-6 py-3">Tanggal Pembayaran</th>
                            <th scope="col" class="px-6 py-3">Tenggat Pembayaran</th>
                            <th scope="col" class="px-6 py-3">Nominal (Rp)</th>
                            <th scope="col" class="px-6 py-3">Jenjang</th>
                            <th scope="col" class="px-6 py-3">Kelas</th>
                            <th scope="col" class="px-6 py-3">Siswa</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_tunggakan as $key => $tunggakan) : ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th class="px-6 py-4 text-amber-500"></th>
                                <td class="px-6 py-4"><?= $tunggakan['tgl_pembayaran'] ?></td>
                                <td class="px-6 py-4"><?= $tunggakan['tenggat_pembayaran'] ?></td>
                                <td class="px-6 py-4"><?= $tunggakan['nominal'] ?></td>
                                <td class="px-6 py-4"><?= $tunggakan['nama_jenjang'] ?></td>
                                <td class="px-6 py-4"><?= $tunggakan['nama_kelas'] ?></td>
                                <td class="px-6 py-4"><?= $tunggakan['nama_siswa'] ?></td>
                                <td class="px-6 py-4 <?= $tunggakan['status'] === null ? 'text-red-500' : 'text-green-500' ?>">
                                    <?= $tunggakan['status'] === null ? 'Belum Terbayar' : $tunggakan['status'] ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($tunggakan['status'] !== 'Lunas') : ?>
                                        <button data-modal-target="edit<?= $tunggakan['id_tunggakan'] ?>" data-modal-toggle="edit<?= $tunggakan['id_tunggakan'] ?>" type="submit" class="btn btn--outline-green">Tandai sebagai lunas</button>
                                        <div id="edit<?= $tunggakan['id_tunggakan'] ?>" tabindex="-1" aria-hidden="true" class="modal">
                                            <div class="modal__backdrop">
                                                <!-- Modal content -->
                                                <div class="modal__content">
                                                    <!-- Modal header -->
                                                    <div class="modal__header">
                                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                            Masukkan tanggal pembayaran
                                                        </h3>
                                                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="edit<?= $tunggakan['id_tunggakan'] ?>">
                                                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            <span class="sr-only">Close modal</span>
                                                        </button>
                                                    </div>
                                                    <form class="form" action="../../api/admin/tunggakan.php" method="post">
                                                        <!-- Modal body -->
                                                        <div class="modal__body">
                                                            <label for="tgl_pembayaran">Tanggal pembayaran</label>
                                                            <?php
                                                            $bulan = date('m');
                                                            $hari = date('d');
                                                            $tahun = date('Y');
                                                            $hari_ini = "$tahun-$bulan-$hari";
                                                            ?>
                                                            <input type="hidden" name="status" value="Lunas">
                                                            <input class="input" type="date" name="tgl_pembayaran" id="tgl_pembayaran" value="<?= $hari_ini ?>">
                                                        </div>
                                                        <!-- Modal footer -->
                                                        <div class="modal__footer">
                                                            <button type="submit" name="update" value="<?= $tunggakan['id_tunggakan'] ?>" class="btn btn-blue">Tandai sebagai lunas</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                </td>
                            <?php endforeach ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>