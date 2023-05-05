<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('siswa');

$id_siswa = $_SESSION['user_id'];
$sql = "SELECT dp.*, p.*, dj.tgl_pertemuan,m.nama nama_mapel FROM detail_penilaian dp 
JOIN penilaian p ON dp.id_penilaian = p.id_penilaian
JOIN detail_jadwal dj ON p.id_detail_jadwal = dj.id_detail_jadwal
JOIN jadwal j ON dj.id_jadwal = j.id_jadwal
JOIN mapel m ON j.id_mapel = m.id_mapel
WHERE id_siswa = '$id_siswa'";
$data_nilai = $db->query($sql);
?>

<div class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Nilai', 'filename' => 'nilai_siswa.php']]);
            ?>

            <div class="flex gap-2 items-center">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Nilai Siswa</h4>
            </div>

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3"></th>
                            <th scope="col" class="px-6 py-3">Mata Pelajaran</th>
                            <th scope="col" class="px-6 py-3">Judul</th>
                            <th scope="col" class="px-6 py-3">Keterangan</th>
                            <th scope="col" class="px-6 py-3">Tanggal Pertemuan</th>
                            <th scope="col" class="px-6 py-3">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_nilai as $key => $value) : ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th class="px-6 py-4 text-amber-500"><?= (int) $key + 1 ?></th>
                                <td class="px-6 py-4"><?= $value['nama_mapel'] ?></td>
                                <td class="px-6 py-4"><?= $value['judul_penilaian'] ?></td>
                                <td class="px-6 py-4"><?= $value['keterangan_penilaian'] ?></td>
                                <td class="px-6 py-4"><?= $value['tgl_pertemuan'] ?></td>
                                <td class="px-6 py-4"><?= $value['nilai'] ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>