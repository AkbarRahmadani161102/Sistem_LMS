<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access(['Super Admin', 'Admin Akademik']);
$sql = "SELECT * FROM jenjang";
$data_jenjang = $db->query($sql) or die($db->error);
$data_jenjang->fetch_assoc();

$month = date('n');
$year = date('Y');

$sql = "SELECT d.*, j.*, k.nama nama_kelas, m.nama nama_mapel, i.nama nama_instruktur FROM detail_jadwal d
LEFT JOIN instruktur i ON d.id_instruktur=  i.id_instruktur
JOIN jadwal j ON d.id_jadwal = j.id_jadwal
JOIN kelas k ON j.id_kelas = k.id_kelas 
JOIN mapel m ON j.id_mapel = m.id_mapel
WHERE MONTH(d.tgl_pertemuan) = $month AND YEAR(d.tgl_pertemuan) = $year ";

if(isset($_GET['jenjang'])) {
    $id_jenjang = $_GET['jenjang'];
    $sql .= "AND k.id_jenjang = '$id_jenjang'";
}
$data_pertemuan = $db->query($sql) or die($db->error);
$data_pertemuan->fetch_assoc();
?>

<div id="jadwal" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php'; ?>
            <div class="flex items-center gap-5">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Data Pertemuan</h4>

                <form action="../../api/admin/pertemuan.php" method="post">
                    <button type="submit" class="btn" name="sync"> Update Data Pertemuan </button>
                </form>

            </div>

            <?php generate_breadcrumb([['title' => 'Pertemuan', 'filename' => 'pertemuan.php']]); ?>

            <?php if ($data_pertemuan->num_rows > 0) : ?>
                <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400">
                    <?php foreach ($data_jenjang as $key => $jenjang) : ?>
                        <li class="mr-2">
                            <a href="?jenjang=<?= $jenjang['id_jenjang'] ?>" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800 dark:hover:text-gray-300 <?= isset($_GET['jenjang']) && $_GET['jenjang'] === $jenjang['id_jenjang'] ? 'text-blue-500' : '' ?>"><?= $jenjang['nama'] ?></a>
                        </li>
                    <?php endforeach ?>
                </ul>
                <div class="relative overflow-x-auto mt-5">
                    <table class="datatable w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">#</th>
                                <th scope="col" class="px-6 py-3">Nama Kelas</th>
                                <th scope="col" class="px-6 py-3">Nama Mapel</th>
                                <th scope="col" class="px-6 py-3">Nama Instruktur</th>
                                <th scope="col" class="px-6 py-3">Hari, Tanggal</th>
                                <th scope="col" class="px-6 py-3">Jam Mulai</th>
                                <th scope="col" class="px-6 py-3">Jam Selesai</th>
                                <th scope="col" class="px-6 py-3">Status Kehadiran Instruktur</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data_pertemuan as $key => $pertemuan) : ?>
                                <tr class="border-b dark:bg-gray-800 dark:border-gray-700 bg-transparent">
                                    <th class="px-6 py-4 text-amber-500"><?= $key + 1 ?></th>
                                    <td class="px-6 py-4"><?= $pertemuan['nama_kelas'] ?></td>
                                    <td class="px-6 py-4"><?= $pertemuan['nama_mapel'] ?></td>
                                    <td class="px-6 py-4"><?= $pertemuan['nama_instruktur'] ?></td>
                                    <td class="px-6 py-4"><?= $pertemuan['hari'] ?>, <?= $pertemuan['tgl_pertemuan'] ?></td>
                                    <td class="px-6 py-4"><?= $pertemuan['jam_mulai'] ?></td>
                                    <td class="px-6 py-4"><?= $pertemuan['jam_selesai'] ?></td>
                                    <td class="px-6 py-4 <?= $pertemuan['status_kehadiran_instruktur'] === 'Hadir' ? 'text-green-500' : 'text-red-500' ?>"><?= $pertemuan['status_kehadiran_instruktur'] ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <div class="pt-16 flex flex-col items-center h-full gap-5 text-gray-800 dark:text-white">
                    <h5>Data pertemuan dalam bulan ini tidak ditemukan</h5>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>