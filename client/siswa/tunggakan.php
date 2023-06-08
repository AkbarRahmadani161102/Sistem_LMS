<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('siswa');

$id_siswa = $_SESSION['user_id'];

$sql = "SELECT t.* FROM tunggakan t
JOIN siswa s on s.id_siswa = t.id_siswa 
WHERE s.id_siswa = '$id_siswa'";
$data_tunggakan = $db->query($sql) or die($db->error);
$data_tunggakan->fetch_assoc();
?>

<div class="dashboard__main">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="dashboard__content">
        <div class="dashboard__subcontent">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Pengaturan', 'filename' => 'pengaturan.php']]);
            ?>

            <div class="flex flex-col my-7 gap-4 text-gray-800 dark:text-white">
                <h4 class="font-semibold">Tunggakan</h4>
                <p>Untuk melakukan pembayaran, silahkan menuju ke administrasi keuangan</p>
            </div>

            <div class="table__container">
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Tanggal Pembayaran</th>
                            <th>Tenggat Pembayaran</th>
                            <th>Deskripsi</th>
                            <th>Nominal (Rp)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_tunggakan as $key => $tunggakan) : ?>
                            <tr>
                                <th><?= $key + 1 ?></th>
                                <td><?= $tunggakan['tgl_pembayaran'] === '0000-00-00' ? '-' : $tunggakan['tgl_pembayaran'] ?></td>
                                <td><?= $tunggakan['tenggat_pembayaran'] ?></td>
                                <td><?= $tunggakan['deskripsi'] ?></td>
                                <td><?= $tunggakan['nominal'] ?></td>
                                <td class="<?= $tunggakan['status'] === 'Lunas' ? 'text-green-500' : 'text-red-500' ?> "><?= $tunggakan['status'] === 'Lunas' ? $tunggakan['status'] : 'Belum Terbayar' ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>