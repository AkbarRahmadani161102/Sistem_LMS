<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('admin', 'pertemuan.php');
$sql = "SELECT * FROM jenjang";
$data_jenjang = $db->query($sql) or die($db->error);
$data_jenjang->fetch_assoc();

$month = date('n');
$year = date('Y');

$sql = "SELECT d.*, j.*, k.nama nama_kelas, m.nama nama_mapel, i.nama nama_instruktur, COUNT(abs.id_absensi_siswa) count_absensi_siswa, COUNT(p.id_penilaian) count_penilaian, COUNT(pe.id_pengajuan) count_pengajuan FROM detail_jadwal d
LEFT JOIN instruktur i ON d.id_instruktur=  i.id_instruktur
JOIN jadwal j ON d.id_jadwal = j.id_jadwal
JOIN kelas k ON j.id_kelas = k.id_kelas 
JOIN mapel m ON j.id_mapel = m.id_mapel
LEFT JOIN absensi_siswa abs ON d.id_detail_jadwal = abs.id_detail_jadwal
LEFT JOIN penilaian p ON d.id_detail_jadwal = p.id_detail_jadwal
LEFT JOIN pengajuan pe ON d.id_detail_jadwal = pe.id_detail_jadwal
WHERE MONTH(d.tgl_pertemuan) = $month AND YEAR(d.tgl_pertemuan) = $year";

if (isset($_GET['jenjang'])) {
    $id_jenjang = $_GET['jenjang'];
    $sql .= " AND k.id_jenjang = '$id_jenjang'";
}

$sql .= " GROUP BY d.id_detail_jadwal";

$data_pertemuan = $db->query($sql) or die($db->error);
$data_pertemuan->fetch_assoc();

if (isset($_GET['reassign_instruktur'])) {
    $id_detail_jadwal = $_GET['reassign_instruktur'];
    $sql = "SELECT *, m.id_mapel, m.nama nama_mapel, k.nama nama_kelas, i.nama nama_instruktur FROM detail_jadwal dj
    JOIN jadwal j ON dj.id_jadwal = j.id_jadwal
    JOIN mapel m ON j.id_mapel = m.id_mapel
    JOIN kelas k ON j.id_kelas = k.id_kelas
    JOIN instruktur i ON dj.id_instruktur = i.id_instruktur
    WHERE id_detail_jadwal = '$id_detail_jadwal'";
    $data_detail_jadwal = $db->query($sql) or die($db->error);
    $data_detail_jadwal = $data_detail_jadwal->fetch_assoc();
    $id_mapel = $data_detail_jadwal['id_mapel'];
}
?>

<div class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php'; ?>
            <div class="flex flex-wrap items-center gap-5">
                <h4 class="mt-4 md:my-7 font-semibold text-gray-800 dark:text-white">Data Pertemuan</h4>

                <form action="../../api/admin/pertemuan.php" method="post">
                    <button type="submit" class="btn" name="sync"> Update Data Pertemuan </button>
                </form>

            </div>

            <?php generate_breadcrumb([['title' => 'Pertemuan', 'filename' => 'pertemuan.php']]); ?>

            <?php if (isset($_GET['reassign_instruktur'])) : ?>
                <div class="flex gap-2 mt-5 flex-col lg:flex-row">
                    <div class="flex w-full lg:w-1/4 flex-col rounded bg-gray-200 dark:bg-gray-600 p-5 space-y-3 text-gray-800 dark:text-white">
                        <h5>Detail Jadwal</h5>
                        <div class="flex justify-between hover:bg-gray-500 hover:text-white py-3 px-2 rounded-lg">
                            <p>Nama Mapel:</p>
                            <p><?= $data_detail_jadwal['nama_mapel'] ?></p>
                        </div>
                        <div class="flex justify-between hover:bg-gray-500 hover:text-white py-3 px-2 rounded-lg">
                            <p>Kelas:</p>
                            <p><?= $data_detail_jadwal['nama_kelas'] ?></p>
                        </div>
                        <div class="flex justify-between hover:bg-gray-500 hover:text-white py-3 px-2 rounded-lg">
                            <p>Tanggal Pertemuan:</p>
                            <p><?= $data_detail_jadwal['tgl_pertemuan'] ?></p>
                        </div>
                        <div class="flex justify-between hover:bg-gray-500 hover:text-white py-3 px-2 rounded-lg">
                            <p>Jam Mulai:</p>
                            <p><?= $data_detail_jadwal['jam_mulai'] ?></p>
                        </div>
                        <div class="flex justify-between hover:bg-gray-500 hover:text-white py-3 px-2 rounded-lg">
                            <p>Jam Selesai:</p>
                            <p><?= $data_detail_jadwal['jam_selesai'] ?></p>
                        </div>
                        <div class="hover:bg-gray-500 hover:text-white py-3 px-2 rounded-lg space-y-9">
                            <p>Instruktur yang akan diubah:</p>
                            <p><?= $data_detail_jadwal['nama_instruktur'] ?></p>
                        </div>
                    </div>
                    <form action="../../api/admin/pertemuan.php" method="post" class="form flex-1 rounded bg-gray-200 dark:bg-gray-600 p-5 space-y-5">
                        <label class="text-xl" for="instruktur">Instruktur yang mengampu</label>
                        <label class="block" for="instruktur">Instruktur dibawah ini telah disaring berdasarkan mata pelajaran yang diampu</label>
                        <select class="input selectize" name="instruktur" id="instruktur" required>
                            <?php
                            $sql = "SELECT i.* FROM detail_mapel dm
                            JOIN mapel m ON dm.id_mapel = m.id_mapel
                            JOIN instruktur i ON dm.id_instruktur = i.id_instruktur
                            WHERE m.id_mapel = '$id_mapel'";
                            $data_instruktur = $db->query($sql) or die($db->error);
                            while ($instruktur = $data_instruktur->fetch_assoc()) : ?>
                                <?php if (isset($_GET['pengajuan'])) : ?>
                                    <option value="<?= $instruktur['id_instruktur'] ?>" <?= $_GET['instruktur'] === $instruktur['id_instruktur'] ? 'selected' : '' ?>><?= $instruktur['nama'] ?></option>
                                <?php else : ?>
                                    <option value="<?= $instruktur['id_instruktur'] ?>" <?= $data_detail_jadwal['nama_instruktur'] === $instruktur['nama'] ? 'selected' : '' ?>><?= $instruktur['nama'] ?></option>
                                <?php endif ?>
                            <?php endwhile ?>
                        </select>

                        <?php if (isset($_GET['pengajuan'])) : ?>
                            <input type="hidden" name="pengajuan" value="<?= $_GET['pengajuan'] ?>">
                        <?php endif ?>

                        <button type="submit" class="btn dark:bg-green-500 dark:text-white" name="reassign_instruktur" value="<?= $id_detail_jadwal ?>">Tetapkan</button>
                    </form>
                </div>
            <?php else : ?>

                <?php if ($data_pertemuan->num_rows > 0) : ?>
                    <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400">
                        <?php foreach ($data_jenjang as $key => $jenjang) : ?>
                            <li class="mr-2">
                                <a href="?jenjang=<?= $jenjang['id_jenjang'] ?>" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800 dark:hover:text-gray-300 <?= isset($_GET['jenjang']) && $_GET['jenjang'] === $jenjang['id_jenjang'] ? 'text-blue-500' : '' ?>"><?= $jenjang['nama'] ?></a>
                            </li>
                        <?php endforeach ?>
                    </ul>
                    <div class="table__container">
                        <form action="../../api/admin/pertemuan.php" method="post">
                            <table class="datatable table">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="flex flex-col items-center gap-4 z-10">
                                                <button name="bulk_delete" type="submit" class="btn w-full btn--outline-red">Hapus</button>
                                                <label class="flex">Check All <input class="ml-3" type="checkbox" id="check_all"></label>
                                            </div>
                                        </th>
                                        <th>Nama Kelas</th>
                                        <th>Nama Mapel</th>
                                        <th>Nama Instruktur</th>
                                        <th>Hari, Tanggal</th>
                                        <th>Jam Mulai</th>
                                        <th>Jam Selesai</th>
                                        <th>Status Kehadiran Instruktur</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data_pertemuan as $key => $pertemuan) : ?>
                                        <?php $delete_able = $pertemuan['count_absensi_siswa'] === '0' && $pertemuan['count_penilaian'] === '0' && $pertemuan['count_pengajuan'] === '0' ?>

                                        <tr class="relative">
                                            <th class="mt-5">
                                                <?php if ($delete_able) : ?>
                                                    <label for="check<?= $key ?>" class="absolute top-0 left-0 h-full w-full"></label>
                                                    <input id="check<?= $key ?>" class="ml-8" type="checkbox" name="delete_pertemuan[]" value="<?= $pertemuan['id_detail_jadwal'] ?>">
                                                <?php endif ?>
                                            </th>
                                            <td><?= $pertemuan['nama_kelas'] ?></td>
                                            <td><?= $pertemuan['nama_mapel'] ?></td>
                                            <td><?= $pertemuan['nama_instruktur'] ?></td>
                                            <td><?= $pertemuan['hari'] ?>, <?= $pertemuan['tgl_pertemuan'] ?></td>
                                            <td><?= $pertemuan['jam_mulai'] ?></td>
                                            <td><?= $pertemuan['jam_selesai'] ?></td>
                                            <td class="<?= $pertemuan['status_kehadiran_instruktur'] === 'Hadir' ? 'text-green-500' : 'text-red-500' ?>"><?= $pertemuan['status_kehadiran_instruktur'] ?></td>
                                            <td>
                                                <?php if ($delete_able) : ?>
                                                    <div class="flex gap-3">
                                                        <a href="?reassign_instruktur=<?= $pertemuan['id_detail_jadwal'] ?>" class="btn btn--outline-green flex items-center justify-around z-20 gap-2"><i class="ri-arrow-left-right-line"></i><span>Ganti Instruktur</span></a>
                                                        <!-- <button onclick="generateConfirmationDialog('../../api/admin/pertemuan.php', {delete: '<?= $pertemuan['id_detail_jadwal'] ?>'})" class="btn btn--outline-red z-10">
                                                            <i class="ri-delete-bin-6-line"></i>
                                                        </button> -->
                                                    </div>
                                                <?php else : ?>
                                                    <button class="btn btn--transparent">&nbsp;</button>
                                                <?php endif ?>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </form>
                    </div>
                <?php else : ?>
                    <div class="pt-16 flex flex-col items-center h-full gap-5 text-gray-800 dark:text-white">
                        <h5>Data pertemuan dalam bulan ini tidak ditemukan</h5>
                    </div>
                <?php endif ?>
            <?php endif ?>

        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>