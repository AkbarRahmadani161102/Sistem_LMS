<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('siswa');

$id_siswa = $_SESSION['user_id'];

$sql = "SELECT DISTINCT * FROM jadwal j
JOIN kelas k on j.id_kelas = k.id_kelas
JOIN detail_kelas dk on dk.id_kelas = k.id_kelas 
WHERE dk.id_siswa = '$id_siswa'";
$data_pertemuan = $db->query($sql) or die($db->error);
?>

<div id="index" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Dashboard', 'filename' => 'index.php']]);
            ?>
            <div class="flex justify-between items-center">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Jadwal Minggu Ini</h4>
            </div>

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center"></th>
                            <th scope="col" class="px-6 py-3 text-center">Instruktur dan Mapel</th>
                            <th scope="col" class="px-6 py-3 text-center">Jam Mulai - Jam Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_pertemuan as $jadwal) :
                            $hari = $jadwal['hari'];
                            $sql = "SELECT j.*, k.nama nama_kelas, m.nama nama_mapel, i.nama nama_instruktur FROM jadwal j
                                    JOIN kelas k on j.id_kelas = k.id_kelas
                                    JOIN detail_kelas dk on dk.id_kelas = k.id_kelas
                                    JOIN mapel m on j.id_mapel = m.id_mapel 
                                    JOIN siswa s on s.id_siswa = dk.id_siswa
                                    JOIN detail_jadwal dj on dj.id_jadwal = j.id_jadwal 
                                    JOIN instruktur i on i.id_instruktur = dj.id_instruktur
                                    WHERE dk.id_siswa = '$id_siswa' AND j.hari = '$hari'"; ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th class="px-6 py-4 text-amber-500 text-center">
                                    <p><?= $jadwal['hari'] ?></p>
                                </th>

                                <td class="px-6 py-4 text-center">
                                    <?php
                                    $data = $db->query($sql) or die($db->error);
                                    $data = $data->fetch_assoc();
                                    ?>
                                    <p><?= isset($data['nama_instruktur']) ? $data['nama_instruktur'] : "" ?></p>
                                    <p><?= isset($data['nama_mapel']) ? $data['nama_mapel'] : "" ?></p>
                                </td>

                                <th class="px-6 py-4 text-amber-500 text-center">
                                    <p><?= $jadwal['jam_mulai'] ?> - <?= $jadwal['jam_selesai'] ?></p>
                                </th>
                            </tr>
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

<?php include_once('../template/footer.php') ?>