<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('admin', 'penilaian.php');

$id_instruktur = $_SESSION['user_id'];

$sql = "SELECT *, dj.id_detail_jadwal, k.nama nama_kelas, m.nama nama_mapel, i.nama nama_instruktur FROM detail_jadwal dj
JOIN jadwal j ON dj.id_jadwal = j.id_jadwal
JOIN kelas k ON j.id_kelas = k.id_kelas
JOIN mapel m ON j.id_mapel = m.id_mapel
JOIN instruktur i ON dj.id_instruktur = i.id_instruktur
LEFT JOIN penilaian p ON dj.id_detail_jadwal = p.id_detail_jadwal
WHERE status_kehadiran_instruktur = 'Hadir'
AND p.id_penilaian IS NOT NULL";
$data_detail_jadwal = $db->query($sql) or die($db->error);
$data_detail_jadwal->fetch_assoc();

if (isset($_GET['detail'])) {
    $id_detail_jadwal = $_GET['detail'];
    $sql = "SELECT p.judul_penilaian, p.keterangan_penilaian, p.tgl_penilaian, dp.nilai, s.nama nama_siswa, k.nama nama_kelas, m.nama nama_mapel, i.nama nama_instruktur, dj.tgl_pertemuan, j.hari, j.jam_mulai, j.jam_selesai FROM penilaian p
    JOIN detail_penilaian dp ON p.id_penilaian = dp.id_penilaian
    JOIN siswa s ON dp.id_siswa = s.id_siswa
    JOIN detail_jadwal dj ON p.id_detail_jadwal = dj.id_detail_jadwal
    JOIN jadwal j ON dj.id_jadwal = j.id_jadwal
    JOIN kelas k ON j.id_kelas = k.id_kelas
    JOIN mapel m ON j.id_mapel = m.id_mapel
    JOIN instruktur i ON dj.id_instruktur = i.id_instruktur
    WHERE dj.id_detail_jadwal = '$id_detail_jadwal'";

    $data_penilaian = $db->query($sql) or die($db->error);
    // print_r($data_penilaian->fetch_assoc());

    $data_penilaian_single = $data_penilaian->fetch_assoc();
    $judul_penilaian =  $data_penilaian_single['judul_penilaian'];
    $keterangan_penilaian =  $data_penilaian_single['keterangan_penilaian'];
    $nama_instruktur =  $data_penilaian_single['nama_instruktur'];
    $nama_kelas =  $data_penilaian_single['nama_kelas'];
    $nama_mapel =  $data_penilaian_single['nama_mapel'];
    $data_penilaian->fetch_assoc();
}
?>

<div class="dashboard__main">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="dashboard__content">
        <div class="dashboard__subcontent">
            <?php include_once '../components/dashboard_navbar.php'; ?>

            <?php if (isset($_GET['detail'])) : ?>
                <?php generate_breadcrumb([['title' => 'Penilaian', 'filename' => 'penilaian.php'], ['title' => 'Detail Penilaian', 'filename' => '#']]); ?>
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Detail Penilaian Siswa <?= $nama_kelas ?></h4>

                <div class="flex w-full md:w-1/2 flex-col text-gray-800 dark:text-white space-y-4">
                    <div class="flex justify-between">
                        <h6>Judul Penilaian :</h6>
                        <p><?= $judul_penilaian ?></p>
                    </div>
                    <div class="flex justify-between">
                        <h6>Instruktur :</h6>
                        <p><?= $nama_instruktur ?></p>
                    </div>

                    <?php if ($keterangan_penilaian) : ?>
                        <div class="flex flex-col gap-3">
                            <h6>Keterangan Penilaian :</h6>
                            <p><?= $keterangan_penilaian ?></p>
                        </div>
                    <?php endif ?>

                    <div class="flex justify-between">
                        <h6>Kelas :</h6>
                        <p><?= $nama_kelas ?></p>
                    </div>
                    <div class="flex justify-between">
                        <h6>Mapel :</h6>
                        <p><?= $nama_mapel ?></p>
                    </div>
                    <div class="flex justify-between">
                        <h6>Tanggal Penilaian :</h6>
                        <?php foreach ($data_penilaian as $value) : ?>
                            <p><?= $value['tgl_penilaian'];
                                break ?></p>
                        <?php endforeach ?>
                    </div>
                    <div class="flex justify-between">
                        <h6>Jam Mulai :</h6>
                        <?php foreach ($data_penilaian as $value) : ?>
                            <p><?= $value['jam_mulai'];
                                break ?></p>
                        <?php endforeach ?>
                    </div>
                    <div class="flex justify-between">
                        <h6>Jam Selesai :</h6>
                        <?php foreach ($data_penilaian as $value) : ?>
                            <p><?= $value['jam_selesai'];
                                break ?></p>
                        <?php endforeach ?>
                    </div>
                </div>

                <hr class="mt-6">

                <div class="table__container">
                    <h4 class="text-gray-800 dark:text-white mb-5">Data Penilaian</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Nama</th>
                                <th>Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data_penilaian as $key => $penilaian) : ?>
                                <tr class="relative group">
                                    <th><?= $key + 1 ?></th>
                                    <td><?= $penilaian['nama_siswa'] ?></td>
                                    <td><?= $penilaian['nilai'] ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <?php generate_breadcrumb([['title' => 'Penilaian', 'filename' => 'penilaian.php']]); ?>
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Penilaian</h4>

                <div class="table__container">
                    <table class="datatable table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Kelas</th>
                                <th>Mapel</th>
                                <th>Judul</th>
                                <th>Instruktur</th>
                                <th>Tanggal Pertemuan</th>
                                <th>Jam mulai</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data_detail_jadwal as $key => $value) : ?>
                                <tr class="relative group">
                                    <th><?= $key + 1 ?></th>
                                    <td><?= $value['nama_kelas'] ?></td>
                                    <td><?= $value['nama_mapel'] ?></td>
                                    <td><?= $value['judul_penilaian'] ?></td>
                                    <td><?= $value['nama_instruktur'] ?></td>
                                    <td><?= $value['tgl_pertemuan'] ?></td>
                                    <td><?= $value['jam_mulai'] ?></td>
                                    <td>
                                        <a href="?detail=<?= $value['id_detail_jadwal'] ?>" class="btn btn--outline-cyan flex items-center gap-1 w-fit"><i class="ri-search-line"></i> Detail</a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>