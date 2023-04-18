<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('instruktur');

$id_instruktur = $_SESSION['user_id'];

$sql = "SELECT *, dj.id_detail_jadwal, k.nama nama_kelas, m.nama nama_mapel FROM detail_jadwal dj
JOIN jadwal j ON dj.id_jadwal = j.id_jadwal
JOIN kelas k ON j.id_kelas = k.id_kelas
JOIN mapel m ON j.id_mapel = m.id_mapel
LEFT JOIN penilaian p ON dj.id_detail_jadwal = p.id_detail_jadwal
WHERE status_kehadiran_instruktur = 'Hadir'
AND dj.id_instruktur = '$id_instruktur'";
$data_detail_jadwal = $db->query($sql) or die($db->error);
$data_detail_jadwal->fetch_assoc();

if (isset($_GET['create'])) {
    $id_detail_jadwal = $_GET['create'];
    $sql = "SELECT s.* FROM detail_jadwal dj
    JOIN jadwal j ON dj.id_jadwal = j.id_jadwal
    JOIN kelas k ON j.id_kelas = k.id_kelas
    JOIN detail_kelas dk ON k.id_kelas = dk.id_kelas
    JOIN siswa s ON dk.id_siswa = s.id_siswa
    JOIN mapel m ON j.id_mapel = m.id_mapel
    LEFT JOIN penilaian p ON dj.id_detail_jadwal = p.id_detail_jadwal
    WHERE status_kehadiran_instruktur = 'Hadir'
    AND dj.id_instruktur = '$id_instruktur'
    AND dj.id_detail_jadwal = '$id_detail_jadwal'";

    $data_siswa = $db->query($sql) or die($db->error);
    $data_siswa->fetch_assoc();
}

if (isset($_GET['detail'])) {
    $id_detail_jadwal = $_GET['detail'];
    $sql = "SELECT p.judul_penilaian, p.keterangan_penilaian, p.tgl_penilaian, dp.nilai, s.nama nama_siswa, k.nama nama_kelas, m.nama nama_mapel, dj.tgl_pertemuan, j.hari, j.jam_mulai, j.jam_selesai FROM penilaian p
    JOIN detail_penilaian dp ON p.id_penilaian = dp.id_penilaian
    JOIN siswa s ON dp.id_siswa = s.id_siswa
    JOIN detail_jadwal dj ON p.id_detail_jadwal = dj.id_detail_jadwal
    JOIN jadwal j ON dj.id_jadwal = j.id_jadwal
    JOIN kelas k ON j.id_kelas = k.id_kelas
    JOIN mapel m ON j.id_mapel = m.id_mapel
    WHERE dj.id_detail_jadwal = '$id_detail_jadwal'";

    $data_penilaian = $db->query($sql) or die($db->error);
    $data_penilaian->fetch_assoc();

    $data_penilaian_single = $data_penilaian->fetch_assoc();
    $judul_penilaian = $data_penilaian_single['judul_penilaian'];
    $keterangan_penilaian = $data_penilaian_single['keterangan_penilaian'];
    $nama_kelas = $data_penilaian_single['nama_kelas'];
    $nama_mapel = $data_penilaian_single['nama_mapel'];
}
?>

<div id="jenjang" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php'; ?>

            <?php if (isset($_GET['create'])) { ?>
                <?php generate_breadcrumb([['title' => 'Penilaian', 'filename' => 'penilaian.php'], ['title' => 'Tambah Penilaian', 'filename' => '#']]); ?>
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Tambah Penilaian</h4>
                <form action="../../api/instruktur/penilaian.php" class="form space-y-12" method="post">
                    <div class="space-y-2 w-full md:w-1/2 lg:w-1/4">
                        <div class="flex flex-col gap-3 flex-wrap">
                            <label for="judul_penilaian">Judul Penilaian</label>
                            <div class="flex flex-1 flex-col md:flex-row gap-5 justify-between">
                                <input type="text" name="judul_penilaian" id="judul_penilaian" class="input flex flex-1" required>
                                <button type="submit" class="btn" name="create" value="<?= $_GET['create'] ?>">Tambah</button>
                            </div>
                        </div>
                        <div class="flex gap-3 flex-col">
                            <label for="keterangan_penilaian">Keterangan Penilaian (Opsional)</label>
                            <textarea name="keterangan_penilaian" id="keterangan_penilaian" cols="30" rows="2" class="input"></textarea>
                        </div>
                    </div>

                    <div class="table__container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3"></th>
                                    <th scope="col" class="px-6 py-3">Nama Siswa</th>
                                    <th scope="col" class="px-6 py-3">Input Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data_siswa as $key => $siswa) : ?>
                                    <tr>
                                        <th class="px-6 py-4 text-amber-500"><?= $key + 1 ?></th>
                                        <td class="px-6 py-4"><label for="siswa-<?= $siswa['id_siswa'] ?>"><?= $siswa['nama'] ?></label></td>
                                        <td class="px-6 py-4"><input id="siswa-<?= $siswa['id_siswa'] ?>" value="0" min="0" max="100" type="number" name="nilai_siswa[<?= $siswa['id_siswa'] ?>]" required></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            <?php } else if (isset($_GET['detail'])) { ?>
                <?php generate_breadcrumb([['title' => 'Penilaian', 'filename' => 'penilaian.php'], ['title' => 'Detail Penilaian', 'filename' => '#']]); ?>
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Detail Penilaian Siswa <?= $nama_kelas ?></h4>

                <div class="flex w-full md:w-1/2 flex-col text-gray-800 dark:text-white space-y-4">
                    <div class="flex justify-between">
                        <h6>Judul Penilaian :</h6>
                        <p><?= $judul_penilaian ?></p>
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
                                <th scope="col" class="px-6 py-3"></th>
                                <th scope="col" class="px-6 py-3">Nama</th>
                                <th scope="col" class="px-6 py-3">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data_penilaian as $key => $penilaian) : ?>
                                <tr class="relative group">
                                    <th class="px-6 py-4 text-amber-500"><?= $key + 1 ?></th>
                                    <td class="px-6 py-4"><?= $penilaian['nama_siswa'] ?></td>
                                    <td class="px-6 py-4"><?= $penilaian['nilai'] ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <?php generate_breadcrumb([['title' => 'Penilaian', 'filename' => 'penilaian.php']]); ?>
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Penilaian</h4>

                <div class="table__container">
                    <table class="datatable table">
                        <thead>
                            <tr>
                                <th scope="col" class="px-6 py-3"></th>
                                <th scope="col" class="px-6 py-3">Kelas</th>
                                <th scope="col" class="px-6 py-3">Mapel</th>
                                <th scope="col" class="px-6 py-3">Tanggal Pertemuan</th>
                                <th scope="col" class="px-6 py-3">Jam mulai</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data_detail_jadwal as $key => $value) : ?>
                                <tr class="relative group">
                                    <th class="px-6 py-4 text-amber-500"><?= $key + 1 ?></th>
                                    <td class="px-6 py-4"><?= $value['nama_kelas'] ?></td>
                                    <td class="px-6 py-4"><?= $value['nama_mapel'] ?></td>
                                    <td class="px-6 py-4"><?= $value['tgl_pertemuan'] ?></td>
                                    <td class="px-6 py-4"><?= $value['jam_mulai'] ?></td>
                                    <td class="px-6 py-4 text-green-500"><?= $value['id_penilaian'] ? 'Ternilai' : '' ?></td>
                                    <td class="px-6 py-4">
                                        <?php if ($value['id_penilaian']) : ?>
                                            <div class="flex gap-5">
                                                <form action="../../api/instruktur/penilaian.php" method="post">
                                                    <button type="submit" name="reset" value="<?= $value['id_penilaian'] ?>" class="btn btn--outline-amber flex items-center gap-1"><i class="ri-refresh-line"></i> Reset</button>
                                                </form>
                                                <a href="?detail=<?= $value['id_detail_jadwal'] ?>" class="btn btn--outline-cyan flex items-center gap-1"><i class="ri-search-line"></i> Detail</a>
                                            </div>
                                        <?php else : ?>
                                            <button class="btn invisible">&nbsp;</button>
                                            <a class="invisible group-hover:visible absolute w-full h-full flex top-0 left-0 bg-green-500 text-white items-center justify-center" href="?create=<?= $value['id_detail_jadwal'] ?>">Tambah Penilaian</a>
                                        <?php endif ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>