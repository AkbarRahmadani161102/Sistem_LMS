<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('instruktur');

$id_instruktur = $_SESSION['user_id'];
$sql = "SELECT k.*, s.nama nama_siswa FROM kuesioner_instruktur k, siswa s WHERE k.id_instruktur = '$id_instruktur' AND k.id_siswa = s.id_siswa";
$data_umpan_balik = $db->query($sql) or die($db->error);
$data_umpan_balik->fetch_assoc();
?>

<div id="index" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Dashboard', 'filename' => 'index.php']]);
            ?>

            <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Pertemuan Minggu Ini</h4>

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3"></th>
                            <th scope="col" class="px-6 py-3">Siswa</th>
                            <th scope="col" class="px-6 py-3">Pesan</th>
                            <th scope="col" class="px-6 py-3">Rating</th>
                            <th scope="col" class="px-6 py-3">Tanggal Diunggah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data_umpan_balik as $key => $row) : ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th class="px-6 py-4 text-amber-500"><?= $key + 1?></th>
                                <td class="px-6 py-4"><?= $row['nama_siswa'] ?></td>
                                <td class="px-6 py-4"><?= $row['deskripsi'] ?></td>
                                <td class="px-6 py-4">
                                    <?php for ($i = 0; $i < $row['rating']; $i++) : ?>
                                        <i class="ri-star-fill text-amber-500"></i>
                                    <?php endfor ?>
                                <td class="px-6 py-4"><?= $row['tgl_dibuat'] ?></td>
                                <!-- <form action="../../api/siswa/umpan_balik.php" method="post">
                                    <td class="px-6 py-4"><button type="submit" name="delete_instruktur" value="<?= $row['id_kuesioner'] ?>"><i class="ri-delete-bin-line text-red-500"></i></button></td>
                                </form> -->
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>