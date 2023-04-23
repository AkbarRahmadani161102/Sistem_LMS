<?php
include_once('../template/header.php');
user_access('instruktur');

$id_instruktur = $_SESSION['user_id'];
$sql = "SELECT * FROM jadwal j, detail_jadwal dk WHERE j.id_jadwal = dk.id_jadwal AND WEEK(tgl_pertemuan) = WEEK(NOW()) AND j.id_instruktur = '$id_instruktur' GROUP BY tgl_pertemuan";
$data_pertemuan = $db->query($sql) or die($db->error);

if (isset($_GET['presence'])) {
    $id_detail_jadwal = $_GET['presence'];
    $sql = "SELECT s.*, dj.tgl_pertemuan, dj.id_instruktur FROM detail_jadwal dj
    JOIN jadwal j ON dj.id_jadwal = j.id_jadwal
    JOIN kelas k ON j.id_kelas = k.id_kelas
    JOIN detail_kelas dk ON dk.id_kelas = k.id_kelas
    JOIN siswa s ON dk.id_siswa = s.id_siswa
    WHERE id_detail_jadwal = '$id_detail_jadwal'";
    $data_siswa = $db->query($sql) or die($db->error);
    $data_siswa->fetch_assoc();
    $tgl_pertemuan = $data_siswa->fetch_assoc()['tgl_pertemuan'];
    $id_instruktur_pertemuan = $data_siswa->fetch_assoc()['id_instruktur'];
    if ($tgl_pertemuan < date('Y-m-d')) {
        // Jika instruktur mengakses peretemuan yang telah usai
        $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Pertemuan telah usai', 'icon_color' => 'red'];
        redirect('./pertemuan_hari_ini.php');
    }
    if ($id_instruktur !== $id_instruktur_pertemuan) {
        // Jika instruktur mengakses pertemuan dari instruktur lain
        $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Akses ditolak', 'icon_color' => 'red', 'text' => 'Anda tidak memiliki hak untuk mengakses sumber daya ini'];
        redirect('./pertemuan_hari_ini.php');
    }
}
?>
<div id="pertemuan" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php'; ?>

            <?php if (!isset($_GET['presence'])) : ?>
                <?php include_once '../components/dashboard_navbar.php';
                generate_breadcrumb([['title' => 'Pertemuan Hari Ini', 'filename' => 'pertemuan_hari_ini.php']]);
                ?>
                <div class="flex justify-between items-center">
                    <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Data Pertemuan Hari Ini</h4>
                </div>
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center">Hari/Tanggal</th>
                                <th scope="col" class="px-6 py-3 text-center">14.30</th>
                                <th scope="col" class="px-6 py-3 text-center">15.30</th>
                                <th scope="col" class="px-6 py-3 text-center">16.30</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data_pertemuan as $pertemuan) :
                                $tgl_pertemuan = $pertemuan['tgl_pertemuan'];

                                $hari = $pertemuan['hari'];
                                $sql = "SELECT dj.*, j.*, k.nama nama_kelas, m.nama nama_mapel FROM jadwal j
                                        JOIN detail_jadwal dj ON j.id_jadwal = dj.id_jadwal
                                        JOIN kelas k ON j.id_kelas = k.id_kelas
                                        JOIN mapel m ON j.id_mapel = m.id_mapel
                                        WHERE dj.id_instruktur = '$id_instruktur'
                                        AND j.hari = '$hari'
                                        AND tgl_pertemuan = '$tgl_pertemuan'
                                        AND jam_mulai = "; ?>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th class="px-6 py-4 text-amber-500 text-center">
                                        <p><?= $pertemuan['hari'] ?></p>
                                        <p><?= $pertemuan['tgl_pertemuan'] ?></p>
                                    </th>
                                    <?php foreach (WAKTU as $w) : ?>
                                        <?php
                                        $pertemuan = $db->query("$sql'$w'") or die($db->error);
                                        $pertemuan = $pertemuan->fetch_assoc();
                                        if ($pertemuan) : ?>
                                            <td class="px-6 py-4 text-center group relative text-gray-800 dark:text-white <?= $pertemuan['status_kehadiran_instruktur'] === 'Hadir' ? 'bg-rose-500' : '' ?>">
                                                <h6><?= isset($pertemuan['nama_kelas']) ? $pertemuan['nama_kelas'] : "" ?></h6>
                                                <p><?= isset($pertemuan['nama_mapel']) ? $pertemuan['nama_mapel'] : "" ?></p>
                                                <?php if (date('d', strtotime($pertemuan['tgl_pertemuan'])) < date('d')) : ?>
                                                    <span class="bg-red-100 text-red-800 text-xs font-medium mx-auto px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Ditutup</span>
                                                <?php endif ?>
                                                <?php if ($pertemuan['status_kehadiran_instruktur'] !== 'Hadir' && date('d', strtotime($pertemuan['tgl_pertemuan'])) >= date('d')) : ?>
                                                    <a href="?presence=<?= $pertemuan['id_detail_jadwal'] ?>" class="w-full h-0 bg-green-500 group-hover:z-[100] absolute bottom-0 left-0 justify-center items-center hidden group-hover:flex group-hover:h-full active:bg-gradient-to-r active:from-cyan-500 active:to-blue-500 transition-all text-xl text-white">Pilih</a>
                                                <?php endif ?>
                                            </td>
                                        <?php else : ?>
                                            <td></td>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php endif ?>

            <?php if (isset($_GET['presence'])) : ?>
                <?php generate_breadcrumb([['title' => 'Pertemuan Hari Ini', 'filename' => 'pertemuan_hari_ini.php'], ['title' => 'Pertemuan Kelas 2A', 'filename' => '#']]); ?>
                <div class="flex justify-between items-center">
                    <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Pertemuan Kelas 2A</h4>
                </div>

                <form action="../../api/instruktur/pertemuan_hari_ini.php" method="post">
                    <div>
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-center">#</th>
                                        <th scope="col" class="px-6 py-3 text-center">Nama Siswa</th>
                                        <th scope="col" class="px-6 py-3 text-center" colspan="3">Status Kehadiran</th>
                                    </tr>
                                    <div class="tr">
                                        <th class="px-6 py-3 text-center" colspan="2"></th>
                                        <th scope="col" class="px-6 py-3 text-center">Hadir</th>
                                        <th scope="col" class="px-6 py-3 text-center">Izin</th>
                                        <th scope="col" class="px-6 py-3 text-center">Tanpa Keterangan</th>
                                    </div>
                                </thead>
                                <tbody>
                                    <?php foreach ($data_siswa as $key => $siswa) : ?>
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <th class="px-6 py-4 text-amber-500 text-center"><?= $key + 1 ?></th>
                                            <td class="px-6 py-4 text-center"><?= $siswa['nama'] ?></td>
                                            <td class="px-6 py-4 text-center">
                                                <input id="kehadiran<?= $key ?>" type="radio" value="H" name="kehadiran['<?= $siswa['id_siswa'] ?>']" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" checked>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <input id="kehadiran<?= $key ?>" type="radio" value="I" name="kehadiran['<?= $siswa['id_siswa'] ?>']" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <input id="kehadiran<?= $key ?>" type="radio" value="T" name="kehadiran['<?= $siswa['id_siswa'] ?>']" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                            <button type="submit" name="presence" value="<?= $_GET['presence'] ?>" class="block ml-auto mt-5 px-4 py-2 bg-green-300 rounded">Selesai Absensi</button>
                        </div>
                    </div>
                </form>
            <?php endif ?>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>