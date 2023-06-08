<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('admin', 'umpan_balik_instruktur.php');

$sql = "SELECT k.*, s.nama nama_siswa, i.nama nama_instruktur FROM kuesioner_instruktur k, siswa s, instruktur i WHERE k.id_siswa = s.id_siswa AND k.id_instruktur = i.id_instruktur";
$result = $db->query($sql) or die($sql);
$result->fetch_assoc();
?>

<div class="dashboard__main">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="dashboard__content">
        <div class="dashboard__subcontent">
            <?php include_once '../components/dashboard_navbar.php'; ?>

            <div class="flex items-center gap-5">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Jenjang</h4>
            </div>

            <?php generate_breadcrumb([['title' => 'Umpan Balik Instruktur', 'filename' => 'umpan_balik_instruktur.php']]); ?>
            <div class="table__container">
                <table class="datatable table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Siswa</th>
                            <th>Instruktur</th>
                            <th>Deskripsi</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $key => $value) : ?>
                            <tr>
                                <th><?= $key + 1 ?></th>
                                <td><?= $value['nama_siswa'] ?></td>
                                <td><?= $value['nama_instruktur'] ?></td>
                                <td><?= $value['deskripsi'] ?></td>
                                <td><?= $value['tgl_dibuat'] ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php
$result->free_result();
include_once('../template/footer.php') ?>