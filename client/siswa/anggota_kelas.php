<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('siswa');

$id_siswa = $_SESSION['user_id'];

$sql = "SELECT s.nama, s.no_telp, s.alamat, s.status FROM siswa s
JOIN detail_kelas dk on dk.id_siswa = s.id_siswa
JOIN kelas k on k.id_kelas = dk.id_kelas
WHERE dk.id_kelas = k.id_kelas AND dk.id_siswa = s.id_siswa AND dk.id_siswa = '$id_siswa'";
$data_kelas = $db->query($sql) or die($db->error);
$data_kelas->fetch_assoc();
?>

<div id="anggota_kelas" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php
            include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Anggota Kelas', 'filename' => 'anggota_kelas.php']]);
            ?>

            <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Anggota kelas</h4>

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3"></th>
                            <th scope="col" class="px-6 py-3">Nama</th>
                            <th scope="col" class="px-6 py-3">No.Telp</th>
                            <th scope="col" class="px-6 py-3">Alamat</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_kelas as $key => $kelas) : ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th class="px-6 py-4 text-amber-500"><?= $key + 1 ?></th>
                                <td class="px-6 py-4"><?= $kelas['nama'] ?></th>
                                <td class="px-6 py-4"><?= $kelas['no_telp'] ?></td>
                                <td class="px-6 py-4"><?= $kelas['alamat'] ?></td>
                                <td class="px-6 py-4"><?= $kelas['status'] ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>