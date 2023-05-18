<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('instruktur');

$id_instruktur = $_SESSION['user_id'];

$sql = "SELECT j.*, k.nama nama_kelas, m.nama nama_mapel FROM jadwal j, kelas k, mapel m WHERE j.id_mapel = m.id_mapel AND j.id_kelas = k.id_kelas AND j.id_instruktur = '$id_instruktur'";
$data_jadwal = $db->query($sql) or die($db->error);
$data_jadwal->fetch_assoc();
?>

<div class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Jadwal', 'filename' => 'jadwal.php']]);
            ?>

            <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Jadwal</h4>

            <div class="table__container">
                <table class="table">
                    <thead class="text-center">
                        <tr>
                            <th>Jam Mulai - Jam Selesai</th>
                            <th>Hari</th>
                            <th>Mapel</th>
                            <th>Kelas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_jadwal as $jadwal) : ?>
                            <tr class="text-center">
                                <th><?= $jadwal['jam_mulai'] ?> - <?= $jadwal['jam_selesai'] ?></th>
                                <td><?= $jadwal['hari'] ?></td>
                                <td><?= $jadwal['nama_mapel'] ?></td>
                                <td><?= $jadwal['nama_kelas'] ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>