<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('instruktur');

$id_instruktur = $_SESSION['user_id'];

$sql = "SELECT j.*, k.nama nama_kelas, m.nama nama_mapel FROM jadwal j, kelas k, mapel m WHERE j.id_mapel = m.id_mapel AND j.id_kelas = k.id_kelas AND j.id_instruktur = '$id_instruktur'";
$data_jadwal = $db->query($sql) or die($db->error);
$data_jadwal->fetch_assoc();
?>

<div id="index" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Jadwal', 'filename' => 'jadwal.php']]);
            ?>

            <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Jadwal</h4>

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 text-center">
                        <tr>
                            <th scope="col" class="px-6 py-3">Jam Mulai - Jam Selesai</th>
                            <th scope="col" class="px-6 py-3">Hari</th>
                            <th scope="col" class="px-6 py-3">Mapel</th>
                            <th scope="col" class="px-6 py-3">Kelas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_jadwal as $jadwal) : ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 text-center">
                                <th class="px-6 py-4 text-amber-500"><?= $jadwal['jam_mulai'] ?> - <?= $jadwal['jam_selesai'] ?></th>
                                <td class="px-6 py-4"><?= $jadwal['hari'] ?></td>
                                <td class="px-6 py-4"><?= $jadwal['nama_mapel'] ?></td>
                                <td class="px-6 py-4"><?= $jadwal['nama_kelas'] ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>