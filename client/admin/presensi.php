<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('admin', 'presensi.php');

if (isset($_GET['role']) && $_GET['role'] === 'siswa') {
    $sql = "SELECT dj.tgl_pertemuan, s.nama nama_siswa, k.nama nama_kelas, a.status FROM absensi_siswa a JOIN siswa s ON a.id_siswa = s.id_siswa JOIN detail_jadwal dj ON a.id_detail_jadwal = dj.id_detail_jadwal JOIN detail_kelas dk ON s.id_siswa = dk.id_siswa JOIN kelas k ON dk.id_kelas = k.id_kelas GROUP BY tgl_pertemuan, s.id_siswa";
    $data_presensi_siswa = $db->query($sql) or die($sql);
    $data_presensi_siswa->fetch_assoc();
} else {
    $sql = "SELECT * FROM detail_jadwal dj JOIN instruktur i ON dj.id_instruktur = i.id_instruktur WHERE status_kehadiran_instruktur IS NOT NULL";
    $data_presensi_instruktur = $db->query($sql) or die($sql);
    $data_presensi_instruktur->fetch_assoc();
}
?>

<div class="dashboard__main">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="dashboard__content">
        <div class="dashboard__subcontent">
            <?php include_once '../components/dashboard_navbar.php'; ?>

            <div class="flex items-center gap-5">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Presensi <?= isset($_GET['role']) && $_GET['role'] ? $_GET['role'] : '' ?></h4>
            </div>

            <?php generate_breadcrumb([['title' => 'Presensi', 'filename' => 'presensi.php']]); ?>

            <div class="flex flex-col">
                <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400">
                    <li class="mr-2">
                        <a href="?role=instruktur" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800 dark:hover:text-gray-300 <?= isset($_GET['role']) && $_GET['role'] === 'instruktur' ? 'text-blue-500' : '' ?>">Instruktur</a>
                        <a href="?role=siswa" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800 dark:hover:text-gray-300 <?= isset($_GET['role']) && $_GET['role'] === 'siswa' ? 'text-blue-500' : '' ?>">Siswa</a>
                    </li>
                </ul>
            </div>
            <div class="table__container">
                <table class="datatable table">
                    <thead>
                        <?php if (isset($_GET['role']) && $_GET['role'] === 'siswa') : ?>
                            <tr>
                                <th></th>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Status Kehadiran</th>
                            </tr>
                        <?php else : ?>
                            <tr>
                                <th></th>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Status Kehadiran</th>
                            </tr>
                        <?php endif ?>
                    </thead>
                    <tbody>
                        <?php if (isset($_GET['role']) && $_GET['role'] === 'siswa') : ?>
                            <?php foreach ($data_presensi_siswa as $key => $value) : ?>
                                <tr>
                                    <th><?= $key + 1 ?></th>
                                    <td><?= $value['tgl_pertemuan'] ?></td>
                                    <td><?= $value['nama_siswa'] ?></td>
                                    <td><?= $value['nama_kelas'] ?></td>
                                    <td>
                                        <?php if ($value['status'] === 'H') : ?>
                                            <p class="text-green-500">Hadir</p>
                                        <?php endif ?>
                                        <?php if ($value['status'] === 'I') : ?>
                                            <p class="text-amber-500">Izin</p>
                                        <?php endif ?>
                                        <?php if ($value['status'] === 'T') : ?>
                                            <p class="text-red-500">Tidak Ada Keterangan</p>
                                        <?php endif ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>

                        <?php else : ?>
                            <?php foreach ($data_presensi_instruktur as $key => $value) : ?>
                                <tr>
                                    <th><?= $key + 1 ?></th>
                                    <td><?= $value['tgl_pertemuan'] ?></td>
                                    <td><?= $value['nama'] ?></td>
                                    <td><?= $value['status_kehadiran_instruktur'] ?></td>
                                </tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>