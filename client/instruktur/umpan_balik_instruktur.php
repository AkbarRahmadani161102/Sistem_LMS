<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('instruktur');

$id_instruktur = $_SESSION['user_id'];
$sql = "SELECT k.*, s.nama nama_siswa FROM kuesioner_instruktur k, siswa s WHERE k.id_instruktur = '$id_instruktur' AND k.id_siswa = s.id_siswa";
$data_umpan_balik = $db->query($sql) or die($db->error);
$data_umpan_balik->fetch_assoc();
?>

<div class="dashboard__main">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="dashboard__content">
        <div class="dashboard__subcontent">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Umpan Balik', 'filename' => 'umpan_balik_instruktur.php']]);
            ?>

            <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Umpan Balik Siswa</h4>

            <div class="table__container">
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Siswa</th>
                            <th>Pesan</th>
                            <th>Rating</th>
                            <th>Tanggal Diunggah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_umpan_balik as $key => $row) : ?>
                            <tr>
                                <th><?= $key + 1 ?></th>
                                <td><?= $row['nama_siswa'] ?></td>
                                <td><?= $row['deskripsi'] ?></td>
                                <td>
                                    <?php for ($i = 0; $i < $row['rating']; $i++) : ?>
                                        <i class="ri-star-fill text-amber-500"></i>
                                    <?php endfor ?>
                                <td><?= $row['tgl_dibuat'] ?></td>
                                <!-- <form action="../../api/siswa/umpan_balik.php" method="post">
                                    <td><button type="submit" name="delete_instruktur" value="<?= $row['id_kuesioner'] ?>"><i class="ri-delete-bin-line text-red-500"></i></button></td>
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