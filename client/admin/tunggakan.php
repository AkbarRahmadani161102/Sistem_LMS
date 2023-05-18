<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('admin','tunggakan.php');

$sql = "SELECT YEAR(dj.tgl_pertemuan) tahun FROM absensi_siswa abs
JOIN detail_jadwal dj ON abs.id_detail_jadwal = dj.id_detail_jadwal
GROUP BY YEAR(dj.tgl_pertemuan)";
$tahun_tunggakan = $db->query($sql) or die($db->error);
$tahun_tunggakan->fetch_assoc();

$sql = "SELECT MONTH(dj.tgl_pertemuan) bulan FROM absensi_siswa abs
JOIN detail_jadwal dj ON abs.id_detail_jadwal = dj.id_detail_jadwal
GROUP BY MONTH(dj.tgl_pertemuan)";
$bulan_tunggakan = $db->query($sql) or die($db->error);
$bulan_tunggakan->fetch_assoc();

if (isset($_GET['tahun']) && isset($_GET['bulan'])) {
    $tahun = $_GET['tahun'];
    $bulan = $_GET['bulan'];
    $sql = "SELECT t.*, s.nama nama_siswa, k.nama nama_kelas, j.nama nama_jenjang, SUM(nominal) total_nominal FROM tunggakan t
    JOIN siswa s ON t.id_siswa = s.id_siswa
    JOIN detail_kelas dk ON s.id_siswa = dk.id_siswa
    JOIN kelas k ON dk.id_kelas = k.id_kelas
    JOIN jenjang j ON k.id_jenjang = j.id_jenjang
    WHERE MONTH(t.tgl_dibuat) = $bulan AND YEAR(t.tgl_dibuat) = $tahun
    GROUP BY t.id_siswa";
    $data_tunggakan = $db->query($sql) or die($db->error);
    $data_tunggakan->fetch_assoc();
} else {
    $sql = "SELECT t.*, s.nama nama_siswa, k.nama nama_kelas, j.nama nama_jenjang, (SUM(t.nominal) - COUNT(t.status) * nominal) total_nominal, COUNT(t.id_tunggakan) jumlah_tunggakan, COUNT(t.status) jumlah_lunas, IF(COUNT(t.id_tunggakan) = COUNT(t.status), 'Lunas', NULL) status_tunggakan FROM tunggakan t
    JOIN siswa s ON t.id_siswa = s.id_siswa
    JOIN detail_kelas dk ON s.id_siswa = dk.id_siswa
    JOIN kelas k ON dk.id_kelas = k.id_kelas
    JOIN jenjang j ON k.id_jenjang = j.id_jenjang
    GROUP BY t.id_siswa";
    $data_tunggakan = $db->query($sql) or die($db->error);
    $data_tunggakan->fetch_assoc();
}
?>

<div class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Tunggakan', 'filename' => 'tunggakan.php']]);
            ?>

            <div class="flex items-center gap-5">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Tunggakan</h4>
                <?php if (isset($_GET['tahun']) && isset($_GET['bulan'])) : ?>
                    <form action="../../api/admin/tunggakan.php" method="post">
                        <input type="hidden" name="tahun" value="<?= $_GET['tahun'] ?>">
                        <input type="hidden" name="bulan" value="<?= $_GET['bulan'] ?>">
                        <button type="submit" name="sync" class="btn">Update data tunggakan</button>
                    </form>
                <?php endif ?>
            </div>

            <div class="flex flex-col w-fit text-gray-800 dark:text-white space-y-4 bg-gray-200 dark:bg-gray-700 p-5 rounded-lg">
                <h6 class="font-semibold">Filter Tunggakan</h6>
                <form action="../../api/admin/tunggakan.php" method="post">
                    <div class="flex gap-5">
                        <div class="flex flex-col gap-2">
                            <label for="tb">Tahun</label>
                            <select id="tb" class="selectize-redirect input" name="tahun">
                                <?php foreach ($tahun_tunggakan as $tahun) : ?>
                                    <option value="<?= $tahun['tahun'] ?>" <?= isset($_GET['tahun']) && $tahun['tahun'] === $_GET['tahun'] ? 'selected' : '' ?>><?= $tahun['tahun'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="tb">Bulan</label>
                            <select id="tb" class="selectize-redirect input" name="bulan">
                                <?php foreach ($bulan_tunggakan as $bulan) : ?>
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
                    <table class="datatable-disable-paging table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Tanggal Pembayaran</th>
                                <th>Tenggat Pembayaran</th>
                                <th>Nominal (Rp)</th>
                                <th>Jenjang</th>
                                <th>Kelas</th>
                                <th>Siswa</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data_tunggakan as $key => $tunggakan) : ?>
                                <tr>
                                    <th></th>
                                    <td><?= $tunggakan['tgl_pembayaran'] ?></td>
                                    <td><?= $tunggakan['tenggat_pembayaran'] ?></td>
                                    <td><?= $tunggakan['nominal'] ?></td>
                                    <td><?= $tunggakan['nama_jenjang'] ?></td>
                                    <td><?= $tunggakan['nama_kelas'] ?></td>
                                    <td><?= $tunggakan['nama_siswa'] ?></td>
                                    <td class="<?= $tunggakan['status'] === null ? 'text-red-500' : 'text-green-500' ?>">
                                        <?= $tunggakan['status'] === null ? 'Belum Terbayar' : $tunggakan['status'] ?>
                                    </td>
                                    <td>
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
                                                                <div class="flex flex-col text-gray-800 dark:text-white rounded-lg bg-gray-200 dark:bg-gray-800 p-5 mb-5">
                                                                    <p>Nama: <?= $tunggakan['nama_siswa'] ?></p>
                                                                    <p>Kelas: <?= $tunggakan['nama_kelas'] ?></p>
                                                                    <p>Nominal: <?= $tunggakan['nominal'] ?></p>
                                                                </div>
                                                                <label for="tgl_pembayaran">Tanggal pembayaran</label>
                                                                <input class="input" type="date" name="tgl_pembayaran" id="tgl_pembayaran" value="<?= $tunggakan['tenggat_pembayaran'] ?>">
                                                            </div>
                                                            <!-- Modal footer -->
                                                            <div class="modal__footer">
                                                                <?php if (isset($_GET['tahun']) && isset($_GET['bulan'])) : ?>
                                                                    <input type="hidden" name="status" value="Lunas">
                                                                    <button type="submit" name="update" value="<?= $tunggakan['id_tunggakan'] ?>" class="btn btn--blue">Tandai sebagai lunas</button>
                                                                <?php else : ?>
                                                                    <input type="hidden" name="status" value="LunasKeseluruhan">
                                                                    <button type="submit" name="update" value="<?= $tunggakan['id_siswa'] ?>" class="btn btn--blue">Tandai sebagai lunas</button>
                                                                <?php endif ?>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php else : ?>
                                            <form action="../../api/admin/tunggakan.php" method="post">
                                                <input type="hidden" name="tgl_pembayaran" value="0000-00-00">
                                                <input type="hidden" name="status" value="Reset">
                                                <button type="submit" name="update" value="<?= $tunggakan['id_tunggakan'] ?>" class="btn btn--outline-amber flex items-center gap-1">
                                                    <i class="ri-refresh-line"></i>
                                                    Reset
                                                </button>
                                            </form>
                                        <?php endif ?>
                                    </td>
                                <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <div class="table__container">
                    <table class="datatable-disable-paging table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Tanggal Pembayaran</th>
                                <th>Tenggat Pembayaran</th>
                                <th>Total (Rp)</th>
                                <th>Jenjang</th>
                                <th>Kelas</th>
                                <th>Siswa</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data_tunggakan as $key => $tunggakan) : ?>
                                <tr>
                                    <th></th>
                                    <td><?= $tunggakan['tgl_pembayaran'] ?></td>
                                    <td><?= $tunggakan['tenggat_pembayaran'] ?></td>
                                    <td><?= $tunggakan['total_nominal'] ?></td>
                                    <td><?= $tunggakan['nama_jenjang'] ?></td>
                                    <td><?= $tunggakan['nama_kelas'] ?></td>
                                    <td><?= $tunggakan['nama_siswa'] ?></td>
                                    <td class="<?= $tunggakan['status_tunggakan'] === null ? 'text-red-500' : 'text-green-500' ?>">
                                        <?= $tunggakan['status_tunggakan'] === null ? 'Belum Terbayar' : $tunggakan['status_tunggakan'] ?>
                                    </td>
                                    <td>
                                        <?php if ($tunggakan['status_tunggakan'] !== 'Lunas') : ?>
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
                                                                <div class="flex flex-col text-gray-800 dark:text-white rounded-lg bg-gray-200 dark:bg-gray-800 p-5 mb-5">
                                                                    <p>Nama: <?= $tunggakan['nama_siswa'] ?></p>
                                                                    <p>Kelas: <?= $tunggakan['nama_kelas'] ?></p>
                                                                    <hr class="bg-white my-4">
                                                                    <p class="mb-4">Daftar Tunggakan:</p>
                                                                    <?php
                                                                    $id_siswa = $tunggakan['id_siswa'];
                                                                    $sql = "SELECT *, YEAR(tgl_dibuat) tahun, MONTH(tgl_dibuat) bulan FROM tunggakan WHERE id_siswa = '$id_siswa' AND status IS NULL";
                                                                    $data_tunggakan_siswa = $db->query($sql) or die($db->error); ?>
                                                                    <div class="h-40 overflow-auto">
                                                                        <table class="table">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Tahun, Bulan</th>
                                                                                    <th>Nominal</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php foreach ($data_tunggakan_siswa as $ts) : ?>
                                                                                    <tr>
                                                                                        <td><?= $ts['tahun']?>, <?= BULAN[$ts['bulan'] - 1] ?></td>
                                                                                        <td>Rp. <?= $ts['nominal'] ?></td>
                                                                                    </tr>
                                                                                <?php endforeach ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <p>Total: Rp. <?= $tunggakan['total_nominal'] ?></p>
                                                                </div>

                                                                <label for="tgl_pembayaran">Tanggal pembayaran</label>
                                                                <?php
                                                                $bulan = date('m');
                                                                $hari = date('d');
                                                                $tahun = date('Y');
                                                                $hari_ini = "$tahun-$bulan-$hari";
                                                                ?>

                                                                <input class="input" type="date" name="tgl_pembayaran" id="tgl_pembayaran" value="<?= $hari_ini ?>">
                                                            </div>
                                                            <!-- Modal footer -->
                                                            <div class="modal__footer">
                                                                <input type="hidden" name="status" value="LunasKeseluruhan">
                                                                <button type="submit" name="update" value="<?= $tunggakan['id_siswa'] ?>" class="btn btn--blue">Tandai sebagai lunas</button>
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
            <?php endif ?>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>