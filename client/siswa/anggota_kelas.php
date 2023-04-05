<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('siswa');

$id_siswa = $_SESSION['user_id'];
$nama_kelas = [];
$sql = "SELECT k.id_kelas, k.nama nama_kelas FROM detail_kelas dk, kelas k WHERE dk.id_kelas = k.id_kelas AND dk.id_siswa = '$id_siswa'";
$data_kelas = $db->query($sql) or die($db->error);
$data_kelas->fetch_assoc();

$sql = "SELECT *, k.nama nama_kelas FROM kelas k, detail_kelas dk, siswa s WHERE k.id_kelas = dk.id_kelas AND dk.id_siswa = s.id_siswa AND k.id_kelas IN(SELECT k.id_kelas FROM kelas k, detail_kelas dk WHERE k.id_kelas = dk.id_kelas AND dk.id_siswa = '$id_siswa')";
$data_siswa = $db->query($sql) or die($db->error);
$data_siswa->fetch_assoc();

foreach ($data_kelas as $kelas) {
    $nama_kelas[$kelas['id_kelas']] = $kelas['nama_kelas'];
}

if (isset($_GET['kelas'])) {
    $id_kelas = $_GET['kelas'];
    $sql = "SELECT *, k.nama nama_kelas FROM kelas k, detail_kelas dk, siswa s WHERE k.id_kelas = dk.id_kelas AND dk.id_siswa = s.id_siswa AND k.id_kelas = '$id_kelas'";
    $data_siswa = $db->query($sql) or die($db->error);
    $data_siswa->fetch_assoc();
}
?>

<div id="anggota_kelas" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php
            include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Anggota Kelas', 'filename' => 'anggota_kelas.php']]);
            ?>

            <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400">
                <?php foreach ($data_kelas as $key => $kelas) : ?>
                    <li class="mr-2">
                        <a href="?kelas=<?= $kelas['id_kelas'] ?>" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800 dark:hover:text-gray-300 <?= isset($_GET['kelas']) && $_GET['kelas'] === $kelas['id_kelas'] ? 'text-blue-500' : '' ?>"><?= $kelas['nama_kelas'] ?></a>
                    </li>
                <?php endforeach ?>
            </ul>

            <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Anggota kelas <?php if (isset($_GET['kelas'])) echo $nama_kelas[$id_kelas] ?></h4>

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3"></th>
                            <th scope="col" class="px-6 py-3">Kelas</th>
                            <th scope="col" class="px-6 py-3">Nama</th>
                            <th scope="col" class="px-6 py-3">No.Telp</th>
                            <th scope="col" class="px-6 py-3">Alamat</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_siswa as $key => $siswa) : ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th class="px-6 py-4 text-amber-500"><?= $key + 1 ?></th>
                                <td class="px-6 py-4"><?= $siswa['nama_kelas'] ?></th>
                                <td class="px-6 py-4"><?= $siswa['nama'] ?></th>
                                <td class="px-6 py-4"><?= $siswa['no_telp'] ?></td>
                                <td class="px-6 py-4"><?= $siswa['alamat'] ?></td>
                                <td class="px-6 py-4"><?= $siswa['status'] ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>


        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>