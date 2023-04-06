<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access(['Super Admin', 'Admin Akademik']);
$sql = "SELECT * FROM tunggakan";
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
                                <td class="px-6 py-4"><?= $tunggakan['nama'] ?></td>
                                <td class="px-6 py-4 "><?= $tunggakan['status'] ?></td>
                            <?php endforeach ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>