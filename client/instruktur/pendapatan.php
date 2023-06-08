<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('instruktur');

$id_instruktur = $_SESSION['user_id'];
$sql = "SELECT * FROM gaji WHERE id_instruktur = '$id_instruktur'";
$data_pendapatan = $db->query($sql) or die($sql);
$data_pendapatan->fetch_assoc();
?>

<div class="dashboard__main">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="dashboard__content">
        <div class="dashboard__subcontent">
            <?php include_once '../components/dashboard_navbar.php'; ?>

            <div class="flex items-center gap-5">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Pendapatan</h4>
            </div>

            <?php generate_breadcrumb([['title' => 'Pendapatan', 'filename' => 'pendapatan.php']]); ?>
            <div class="table__container">
                <table class="datatable table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Nominal</th>
                            <th>Tanggal Penerimaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_pendapatan as $key => $value) : ?>
                            <tr>
                                <th><?= $key + 1 ?></th>
                                <td><?= $value['nominal'] ?></td>
                                <td>
                                    <?php if ($value['tgl_penerimaan']) : ?>
                                        <?= $value['tgl_penerimaan'] ?>
                                    <?php else : ?>
                                        <span class="text-amber-500">Belum Diterima</span>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>