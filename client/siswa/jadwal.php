<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('siswa');

$id_siswa = $_SESSION['user_id'];

$sql = "SELECT DISTINCT j.id_mapel, j.*, k.nama nama_kelas, i.nama nama_instruktur, m.nama nama_mapel 
FROM jadwal j 
JOIN kelas k on j.id_kelas = k.id_kelas
JOIN detail_kelas dk on dk.id_kelas = k.id_kelas
JOIN mapel m on j.id_mapel = m.id_mapel 
JOIN siswa s on s.id_siswa = dk.id_siswa
JOIN detail_jadwal dj on dj.id_jadwal = j.id_jadwal 
JOIN instruktur i on i.id_instruktur = dj.id_instruktur
WHERE s.id_siswa = '$id_siswa' AND j.id_jadwal = dj.id_jadwal
";

if(isset($_GET['kelas'])) {
    $id_kelas = $_GET['kelas'];
    $sql .= " AND k.id_kelas = '$id_kelas'";
}
$data_jadwal = $db->query($sql) or die($db->error);
$data_jadwal->fetch_assoc();

$nama_kelas = [];
$sql = "SELECT k.id_kelas, k.nama nama_kelas FROM detail_kelas dk, kelas k WHERE dk.id_kelas = k.id_kelas AND dk.id_siswa = '$id_siswa'";
$data_kelas = $db->query($sql) or die($db->error);
$data_kelas->fetch_assoc();

foreach ($data_kelas as $kelas) {
    $nama_kelas[$kelas['id_kelas']] = $kelas['nama_kelas'];
}

?>

<div class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Jadwal', 'filename' => 'jadwal.php']]);
            ?>

            <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400">
                <?php foreach ($data_kelas as $key => $kelas) : ?>
                    <li class="mr-2">
                        <a href="?kelas=<?= $kelas['id_kelas'] ?>" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800 dark:hover:text-gray-300 <?= isset($_GET['kelas']) && $_GET['kelas'] === $kelas['id_kelas'] ? 'text-blue-500' : '' ?>"><?= $kelas['nama_kelas'] ?></a>
                    </li>
                <?php endforeach ?>
            </ul>

            <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Jadwal Kelas</h4>

            <div class="table__container">
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Hari</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th>Instruktur</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_jadwal as $key => $jadwal) : ?>
                            <tr>
                                <th><?= $key + 1 ?></th>
                                <td><?= $jadwal['hari'] ?></td>
                                <td><?= $jadwal['nama_mapel'] ?></td>
                                <td><?= $jadwal['nama_kelas'] ?></td>
                                <td><?= $jadwal['nama_instruktur'] ?></td>
                                <td><?= $jadwal['jam_mulai'] ?></td>
                                <td><?= $jadwal['jam_selesai'] ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>