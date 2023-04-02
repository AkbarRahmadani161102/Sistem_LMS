<?php
include_once('../template/header.php');
user_access('instruktur');



$id_instruktur = $_SESSION['user_id'];
$sql = "SELECT * FROM jadwal j, detail_jadwal dk WHERE j.id_jadwal = dk.id_jadwal AND j.id_instruktur = '$id_instruktur' GROUP BY tgl_pertemuan";
$data_pertemuan = $db->query($sql) or die($db->error);

if (isset($_GET['presence'])) {
    $id_detail_jadwal = $_GET['presence'];
    $sql = "SELECT s.* FROM detail_jadwal dj
    JOIN jadwal j ON dj.id_jadwal = j.id_jadwal
    JOIN kelas k ON j.id_kelas = k.id_kelas
    JOIN detail_kelas dk ON dk.id_kelas = k.id_kelas
    JOIN siswa s ON dk.id_siswa = s.id_siswa
    WHERE id_detail_jadwal = '$id_detail_jadwal'";
    $data_siswa = $db->query($sql) or die($db->error);
    $data_siswa->fetch_assoc();
}
?>
<div id="pertemuan" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php'; ?>

            <?php if (!isset($_GET['presence'])) : ?>
                <?php include_once '../components/dashboard_navbar.php';
                generate_breadcrumb([['title' => 'Pertemuan', 'filename' => 'pertemuan.php']]);
                ?>
                <div class="flex justify-between items-center">
                    <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Data Pertemuan Hari Ini</h4>
                </div>
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center"></th>
                                <th scope="col" class="px-6 py-3 text-center">14.30</th>
                                <th scope="col" class="px-6 py-3 text-center">15.30</th>
                                <th scope="col" class="px-6 py-3 text-center">16.30</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data_pertemuan as $pertemuan) :
                                $tgl_pertemuan = $pertemuan['tgl_pertemuan'];

                                $hari = $pertemuan['hari'];
                                $sql = "SELECT dk.*, j.*, k.nama nama_kelas, m.nama nama_mapel FROM jadwal j
                                    JOIN detail_jadwal dk ON j.id_jadwal = dk.id_jadwal
                                    JOIN kelas k ON j.id_kelas = k.id_kelas
                                    JOIN mapel m ON j.id_mapel = m.id_mapel
                                    WHERE j.id_instruktur = '$id_instruktur'
                                    AND j.hari = '$hari'
                                    AND tgl_pertemuan = '$tgl_pertemuan'
                                    AND jam_mulai = "; ?>

                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th class="px-6 py-4 text-amber-500 text-center">
                                        <p><?= $pertemuan['hari'] ?></p>
                                        <p><?= $pertemuan['tgl_pertemuan'] ?></p>
                                    </th>
                                    <?php
                                    $data = $db->query($sql . "'14:30:00'") or die($db->error);
                                    $data = $data->fetch_assoc();
                                    if ($data) : ?>
                                        <td class="px-6 py-4 text-center border group hover:border-transparent relative">
                                            <h6><?= isset($data['nama_kelas']) ? $data['nama_kelas'] : "" ?></h6>
                                            <p><?= isset($data['nama_mapel']) ? $data['nama_mapel'] : "" ?></p>
                                            <a href="?presence=<?= $data['id_detail_jadwal'] ?>" class="w-full h-0 bg-green-500 group-hover:z-[100] absolute bottom-0 left-0 justify-center items-center hidden group-hover:flex group-hover:h-full active:bg-gradient-to-r active:from-cyan-500 active:to-blue-500 transition-all text-xl text-white">Pilih</a>
                                        </td>
                                    <?php else : ?>
                                        <td></td>
                                    <?php endif ?>

                                    <?php
                                    $data = $db->query($sql . "'15:30:00'") or die($db->error);
                                    $data = $data->fetch_assoc();
                                    if ($data) : ?>
                                        <td class="px-6 py-4 text-center border group hover:border-transparent relative">
                                            <h6><?= isset($data['nama_kelas']) ? $data['nama_kelas'] : "" ?></h6>
                                            <p><?= isset($data['nama_mapel']) ? $data['nama_mapel'] : "" ?></p>
                                            <a href="?presence=<?= $data['id_detail_jadwal'] ?>" class="w-full h-0 bg-green-500 group-hover:z-[100] absolute bottom-0 left-0 justify-center items-center hidden group-hover:flex group-hover:h-full active:bg-gradient-to-r active:from-cyan-500 active:to-blue-500 transition-all text-xl text-white">Pilih</a>
                                        </td>
                                    <?php else : ?>
                                        <td></td>
                                    <?php endif ?>

                                    <?php
                                    $data = $db->query($sql . "'16:30:00'") or die($db->error);
                                    $data = $data->fetch_assoc();
                                    if ($data) : ?>
                                        <td class="px-6 py-4 text-center border group hover:border-transparent relative">
                                            <h6><?= isset($data['nama_kelas']) ? $data['nama_kelas'] : "" ?></h6>
                                            <p><?= isset($data['nama_mapel']) ? $data['nama_mapel'] : "" ?></p>
                                            <a href="?presence=<?= $data['id_detail_jadwal'] ?>" class="w-full h-0 bg-green-500 group-hover:z-[100] absolute bottom-0 left-0 justify-center items-center hidden group-hover:flex group-hover:h-full active:bg-gradient-to-r active:from-cyan-500 active:to-blue-500 transition-all text-xl text-white">Pilih</a>
                                        </td>
                                    <?php else : ?>
                                        <td></td>
                                    <?php endif ?>
                                </tr>
                            <?php endforeach ?>

                        </tbody>
                    </table>
                </div>
            <?php endif ?>

            <?php if (isset($_GET['presence'])) : ?>
                <?php generate_breadcrumb([['title' => 'Pertemuan Hari Ini', 'filename' => 'pertemuan_hari_ini.php'], ['title' => 'Pertemuan Kelas 2A', 'filename' => 'pertemuan_hari_ini.php']]); ?>
                <div class="flex justify-between items-center">
                    <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Pertemuan Kelas 2A</h4>
                </div>

                <div id="accordion-open" data-accordion="open">
                    <h2 id="accordion-open-heading-1">
                        <button type="button" class="flex items-center justify-between w-full p-5 font-medium text-left text-xl text-gray-500 border border-b-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800" data-accordion-target="#accordion-open-body-1" aria-expanded="true" aria-controls="accordion-open-body-1">
                            Absensi Siswa
                        </button>
                    </h2>
                    <div id="accordion-open-body-1" class="hidden" aria-labelledby="accordion-open-heading-1">
                        <form action="../../api/instruktur/pertemuan_hari_ini.php" method="post">
                            <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900">
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
                                                        <input id="kehadiran" type="radio" value="H" name="kehadiran" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" checked>
                                                    </td>
                                                    <td class="px-6 py-4 text-center">
                                                        <input id="kehadiran" type="radio" value="I" name="kehadiran" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                    </td>
                                                    <td class="px-6 py-4 text-center">
                                                        <input id="kehadiran" type="radio" value="T" name="kehadiran" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                    </td>
                                                </tr>
                                                <input type="hidden" name="id_siswa[]" value="<?= $siswa['id_siswa'] ?>">
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                    <button type="submit" name="presence" class="block ml-auto mt-5 px-4 py-2 bg-green-300 rounded">Selesai Absensi</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <h2 id="accordion-open-heading-2">
                        <button type="button" class="flex items-center justify-between w-full p-5 font-medium text-left text-xl text-gray-500 border border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800" data-accordion-target="#accordion-open-body-2" aria-expanded="false" aria-controls="accordion-open-body-2">
                            Data Penilaian
                        </button>
                    </h2>
                    <div id="accordion-open-body-2" class="hidden" aria-labelledby="accordion-open-heading-2">
                        <form action="">
                            <input type="text" class="w-50 m-5" name="nama_penilaian" placeholder="Nama Penilaian" required>
                            <div class="relative overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-center">#</th>
                                            <th scope="col" class="px-6 py-3 text-center">Nama Siswa</th>
                                            <th scope="col" class="px-6 py-3 text-center" colspan="3">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <th class="px-6 py-4 text-amber-500 text-center">1</th>
                                            <td class="px-6 py-4 text-center">Putin</td>
                                            <td class="px-6 py-4 text-center">
                                                <input type="number" name="nilai">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="submit" href="./penilaian.php?id=blablabla" class="block ml-auto mt-5 px-4 py-2 bg-green-300 rounded">Selesai Penilaian</button>
                            </div>
                        </form>
                    </div>
                </div>

            <?php endif ?>

        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>