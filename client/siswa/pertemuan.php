<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('siswa');

$id_siswa = $_SESSION['user_id'];

$sql = "SELECT j.*,  i.nama nama_instruktur, m.nama nama_mapel, dj.tgl_pertemuan tgl 
FROM jadwal j 
JOIN kelas k on j.id_kelas = k.id_kelas
JOIN detail_kelas dk on dk.id_kelas = k.id_kelas
JOIN mapel m on j.id_mapel = m.id_mapel 
JOIN siswa s on s.id_siswa = dk.id_siswa
JOIN detail_jadwal dj on dj.id_jadwal = j.id_jadwal 
JOIN instruktur i on i.id_instruktur = dj.id_instruktur
WHERE s.id_siswa = '$id_siswa' AND j.id_jadwal = dj.id_jadwal
GROUP BY j.id_mapel";
$data_pertemuan = $db->query($sql) or die($db->error);
$data_pertemuan->fetch_assoc();
?>

<div id="pertemuan" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Pertemuan', 'filename' => 'pertemuan.php']]);
            ?>
            
            <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Pertemuan Kelas</h4>

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3"></th>
                            <th scope="col" class="px-6 py-3">Mata Pelajaran</th>
                            <th scope="col" class="px-6 py-3">Instruktur</th>
                            <th scope="col" class="px-6 py-3">Tanggal</th>
                            <th scope="col" class="px-6 py-3">Jam Mulai</th>
                            <th scope="col" class="px-6 py-3">Jam Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_pertemuan as $key => $pertemuan) : ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th class="px-6 py-4 text-amber-500"><?= $key + 1 ?></th>
                            <td class="px-6 py-4"><?= $pertemuan['nama_mapel'] ?></td>
                            <td class="px-6 py-4"><?= $pertemuan['nama_instruktur'] ?></td>
                            <td class="px-6 py-4"><?= $pertemuan['tgl'] ?></td>
                            <td class="px-6 py-4"><?= $pertemuan['jam_mulai'] ?></td>
                            <td class="px-6 py-4"><?= $pertemuan['jam_selesai'] ?></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>