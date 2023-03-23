<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
include_once('../../api/util/db.php');
user_access('siswa');

$id_siswa = $_SESSION['user_id'];
$sql = "SELECT * FROM tunggakan t, siswa s WHERE t.id_siswa = s.id_siswa AND s.id_siswa = '$id_siswa'";
$data_tunggakan = $db->query($sql);
?>

<div class="w-full min-h-screen flex">
    <?php include_once './components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <?php include_once './components/dashboard_navbar.php' ?>
        <div class="w-full px-10">

            <div class="flex items-center gap-2">
                <a class="text-xl text-gray-400 hover:text-amber-500" href="#">Home</a>
                <i class="ri-arrow-right-s-line text-gray-400 text-xl"></i>
                <a class="text-xl text-slate-800 hover:text-amber-500" href="#">Pembayaran SPP</a>
            </div>
            <h4 class="mt-7 mb-1 font-semibold">Data Tunggakan</h4>
            <p>Silahkan melakukan pembayaran secara onsite</p>
            <table class="mt-5 w-full text-sm text-left shadow-xl rounded">
                <thead class="text-xs text-gray-700 uppercase bg-white">
                    <tr>
                        <th scope="col" class="px-6 py-3">#</th>
                        <th scope="col" class="px-6 py-3">Tanggal Pembayaran</th>
                        <th scope="col" class="px-6 py-3">Tenggat Pembayaran</th>
                        <th scope="col" class="px-6 py-3">Nominal</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $data_tunggakan->fetch_assoc()) : ?>
                        <tr class="bg-white">
                            <th class="px-6 py-4 text-amber-500"></th>
                            <td class="px-6 py-4"><?= $row['tgl_pembayaran'] ?></td>
                            <td class="px-6 py-4"><?= $row['tenggat_pembayaran'] ?></td>
                            <td class="px-6 py-4"><?= $row['nominal'] ?></td>
                            <td class="px-6 py-4"><?= $row['status'] ?></td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>