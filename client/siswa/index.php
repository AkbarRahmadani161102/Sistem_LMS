<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('siswa');

$id_siswa = $_SESSION['user_id']; ?>

<div class="dashboard__main">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="dashboard__content">
        <div class="dashboard__subcontent">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Dashboard', 'filename' => 'index.php']]);
            ?>
            <div class="flex justify-between items-center">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Jadwal</h4>
            </div>

            <div class="table__container">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center"></th>
                            <th scope="col" class="px-6 py-3 text-center">Instruktur dan Mapel</th>
                            <th scope="col" class="px-6 py-3 text-center">Jam Mulai - Jam Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT j.*, k.nama nama_kelas, m.nama nama_mapel, i.nama nama_instruktur, COUNT(j.hari) jumlah_jadwal FROM jadwal j 
                        JOIN mapel m ON j.id_mapel = m.id_mapel 
                        JOIN kelas k ON j.id_kelas = k.id_kelas 
                        JOIN detail_kelas dk ON k.id_kelas = dk.id_kelas 
                        JOIN instruktur i ON j.id_instruktur = i.id_instruktur 
                        WHERE dk.id_siswa = '$id_siswa'" ?>
                        <?php foreach (HARI as $index => $hari) : ?>
                            <?php
                            $data_jadwal_per_hari_sql = $sql . " AND j.hari = '$hari' GROUP BY j.hari";
                            $data_jadwal_per_hari = $db->query($data_jadwal_per_hari_sql) or die($db->error);
                            $data_jadwal_per_hari->fetch_assoc() ?>

                            <?php if ($data_jadwal_per_hari->num_rows > 0) : ?>
                                <?php foreach ($data_jadwal_per_hari as $jadwal) : ?>
                                    <tr>
                                        <th class="px-6 py-4 text-amber-500 text-center">
                                            <p><?= $hari ?></p>
                                        </th>

                                        <td <?= $jadwal['jumlah_jadwal'] > 1 ? "data-popover-target='help-jadwal$index'" : '' ?> class="text-center <?= $jadwal['jumlah_jadwal'] > 1 ? 'bg-red-500' : '' ?>">
                                            <p><?= isset($jadwal['nama_instruktur']) ? $jadwal['nama_instruktur'] : "" ?></p>
                                            <p><?= isset($jadwal['nama_mapel']) ? $jadwal['nama_mapel'] : "" ?></p>
                                        </td>

                                        <?php if ($jadwal['jumlah_jadwal'] > 1) : ?>
                                            <div data-popover id="help-jadwal<?= $index ?>" role="tooltip" class="absolute z-10 invisible inline-block w-96 text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800">
                                                <div class="px-3 py-2 bg-gray-100 border-b border-gray-200 rounded-t-lg dark:border-gray-600 dark:bg-gray-700">
                                                    <h5 class="font-semibold text-gray-900 dark:text-white">Mata pelajaran lebih dari 1</h5>
                                                </div>
                                                <div class="px-3 py-2">
                                                    <?php
                                                    $id_jadwal = $jadwal['id_jadwal'];
                                                    $detail_data_jadwal_per_hari_sql = "SELECT j.*, k.nama nama_kelas, m.nama nama_mapel, i.nama nama_instruktur FROM jadwal j 
                                                    JOIN mapel m ON j.id_mapel = m.id_mapel 
                                                    JOIN kelas k ON j.id_kelas = k.id_kelas 
                                                    JOIN detail_kelas dk ON k.id_kelas = dk.id_kelas 
                                                    JOIN instruktur i ON j.id_instruktur = i.id_instruktur 
                                                    WHERE dk.id_siswa = '$id_siswa' AND j.hari = '$hari'";
                                                    $detail_data_jadwal_per_hari = $db->query($detail_data_jadwal_per_hari_sql) or die($db->error);
                                                    $detail_data_jadwal_per_hari->fetch_assoc()
                                                    ?>
                                                    <?php foreach ($detail_data_jadwal_per_hari as $detail_jadwal) : ?>
                                                        <p><?= $detail_jadwal['nama_kelas'] ?>, <?= $detail_jadwal['nama_mapel'] ?></p>
                                                    <?php endforeach ?>

                                                    <br>
                                                    <p>Silahkan hubungi admin akademik jika terdapat pesan seperti ini</p>
                                                </div>
                                                <div data-popper-arrow></div>
                                            </div>
                                        <?php endif ?>

                                        <th class="px-6 py-4 text-amber-500 text-center">
                                            <p><?= $jadwal['jam_mulai'] ?> - <?= $jadwal['jam_selesai'] ?></p>
                                        </th>
                                    </tr>
                                <?php endforeach ?>
                            <?php else : ?>
                                <tr>
                                    <th class="px-6 py-4 text-center">
                                        <p><?= $hari ?></p>
                                    </th>

                                    <!-- Equal Sizing -->
                                    <td class="text-center">
                                        <p>&nbsp;</p>
                                        <p>&nbsp;</p>
                                    </td>

                                    <!-- Equal Sizing -->
                                    <td class="text-center">
                                        <p>&nbsp;</p>
                                        <p>&nbsp;</p>
                                    </td>
                                </tr>
                            <?php endif ?>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

            <a class="text-gray-400 text-semibold flex items-center mt-5 group hover:text-amber-500" href="./jadwal.php">
                <span>Lihat lebih lanjut</span>
                <span class="text-xl group-hover:translate-x-2 transition"><i class="ri-arrow-right-s-line align-bottom"></i></span>
            </a>

        </div>
    </div>
</div>

<?php
$sql = "SELECT k.id_kelas FROM kelas k, detail_kelas dk WHERE k.id_kelas = dk.id_kelas AND dk.id_siswa = '$id_siswa' LIMIT 1";
$id_kelas = $db->query($sql)->fetch_assoc() or die($db->error);
$id_kelas = $id_kelas['id_kelas'];

$sql = "SELECT COUNT(*) jumlah_pertemuan FROM detail_jadwal dj JOIN jadwal j ON dj.id_jadwal = j.id_jadwal JOIN kelas k ON j.id_kelas = k.id_kelas WHERE k.id_kelas = '$id_kelas' AND status_kehadiran_instruktur IS NULL AND tgl_pertemuan = CURDATE()";
$jumlah_pertemuan = $db->query($sql)->fetch_assoc() or die($db->error);
$jumlah_pertemuan = $jumlah_pertemuan['jumlah_pertemuan']; ?>

<?php if ($jumlah_pertemuan > 0) : ?>
    <script>
        const redirectTo = () => window.location = './pertemuan.php';
        pushNotification('tglNotifikasiSiswa', 'Pemberitahuan Pertemuan', "Anda diharapkan untuk menghadiri <?= $jumlah_pertemuan ?> pertemuan hari ini", redirectTo)
    </script>
<?php endif ?>

<?php include_once('../template/footer.php') ?>