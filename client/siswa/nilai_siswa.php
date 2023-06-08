<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('siswa');

$id_siswa = $_SESSION['user_id'];
$sql = "SELECT dp.*, p.*, dj.tgl_pertemuan,m.nama nama_mapel FROM detail_penilaian dp 
JOIN penilaian p ON dp.id_penilaian = p.id_penilaian
JOIN detail_jadwal dj ON p.id_detail_jadwal = dj.id_detail_jadwal
JOIN jadwal j ON dj.id_jadwal = j.id_jadwal
JOIN mapel m ON j.id_mapel = m.id_mapel
WHERE id_siswa = '$id_siswa'";
$data_nilai = $db->query($sql);
?>

<div class="dashboard__main">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="dashboard__content">
        <div class="dashboard__subcontent">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Nilai', 'filename' => 'nilai_siswa.php']]);
            ?>

            <div class="flex gap-2 items-center">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Nilai Siswa</h4>
            </div>

            <div class="table__container">
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Mata Pelajaran</th>
                            <th>Judul</th>
                            <th>Keterangan</th>
                            <th>Tanggal Pertemuan</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_nilai as $key => $value) : ?>
                            <tr>
                                <th><?= (int) $key + 1 ?></th>
                                <td><?= $value['nama_mapel'] ?></td>
                                <td><?= $value['judul_penilaian'] ?></td>
                                <td><?= $value['keterangan_penilaian'] ?></td>
                                <td><?= $value['tgl_pertemuan'] ?></td>
                                <td><?= $value['nilai'] ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>