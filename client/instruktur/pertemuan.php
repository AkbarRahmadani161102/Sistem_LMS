<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('instruktur');

$id_instruktur = $_SESSION['user_id'];
$sql = "SELECT DISTINCT hari FROM jadwal j  WHERE j.id_instruktur = '$id_instruktur'";
$data_pertemuan = $db->query($sql) or die($db->error);
?>

<div class="dashboard__main">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="dashboard__content">
        <div class="dashboard__subcontent">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Pertemuan', 'filename' => 'pertemuan.php']]);
            ?>
            <div class="flex justify-between items-center">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Data Pertemuan Bulan <?= BULAN[date('n') - 1] ?></h4>
            </div>
            <div class="table__container">
                <table class="table table-fixed">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center">Hari</th>
                            <th colspan="3" class="px-6 py-3 text-center">Jam Mulai</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th scope="col" class="px-6 py-3 text-center">14.30</th>
                            <th scope="col" class="px-6 py-3 text-center">15.30</th>
                            <th scope="col" class="px-6 py-3 text-center">16.30</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_pertemuan as $pertemuan) :
                            $hari = $pertemuan['hari'];
                            $sql = "SELECT j.*, k.nama nama_kelas, m.nama nama_mapel FROM jadwal j
                                    JOIN kelas k ON j.id_kelas = k.id_kelas
                                    JOIN mapel m ON j.id_mapel = m.id_mapel
                                    WHERE id_instruktur = '$id_instruktur'
                                    AND j.hari = '$hari'
                                    AND jam_mulai = "; ?>
                            <tr>
                                <th class="px-6 py-4 text-amber-500 text-center">
                                    <p><?= $pertemuan['hari'] ?></p>
                                </th>

                                <td class="text-center">
                                    <?php
                                    $data = $db->query($sql . "'14:30:00'") or die($db->error);
                                    $data = $data->fetch_assoc();
                                    ?>
                                    <h6><?= isset($data['nama_kelas']) ? $data['nama_kelas'] : "" ?></h6>
                                    <p><?= isset($data['nama_mapel']) ? $data['nama_mapel'] : "" ?></p>
                                </td>

                                <td class="text-center">
                                    <?php
                                    $data = $db->query($sql . "'15:30:00'") or die($db->error);
                                    $data = $data->fetch_assoc();
                                    ?>
                                    <h6><?= isset($data['nama_kelas']) ? $data['nama_kelas'] : "" ?></h6>
                                    <p><?= isset($data['nama_mapel']) ? $data['nama_mapel'] : "" ?></p>
                                </td>

                                <td class="text-center">
                                    <?php
                                    $data = $db->query($sql . "'16:30:00'") or die($db->error);
                                    $data = $data->fetch_assoc();
                                    ?>
                                    <h6><?= isset($data['nama_kelas']) ? $data['nama_kelas'] : "" ?></h6>
                                    <p><?= isset($data['nama_mapel']) ? $data['nama_mapel'] : "" ?></p>
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