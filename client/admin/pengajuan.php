<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access(['Super Admin', 'Admin Akademik']);

$sql = "SELECT *, p.status, s.nama nama_siswa, IF(p.id_siswa IN (SELECT id_ketua_kelas FROM kelas ), 'Ketua Kelas', 'Siswa') role_siswa FROM pengajuan p, siswa s WHERE p.id_siswa = s.id_siswa;";
$data_pengajuan = $db->query($sql) or die($db->error);
$data_pengajuan->fetch_assoc();
?>

<div id="anggota_kelas" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php'; ?>
            <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Data Pengajuan Siswa</h4>

            <?php generate_breadcrumb([['title' => 'Pengajuan', 'filename' => 'pengajuan.php']]); ?>

            <div class="table__container">
                <table class="datatable table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Nama Siswa</th>
                            <th>Role Siswa</th>
                            <th>Email</th>
                            <th>Kontak</th>
                            <th>Judul</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th>Tanggal Dibuat</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_pengajuan as $key => $pengajuan) : ?>
                            <tr>
                                <th><?= $key + 1 ?></th>
                                <td><?= $pengajuan['nama_siswa'] ?></td>
                                <td><?= $pengajuan['role_siswa'] ?></td>
                                <td><?= $pengajuan['email'] ?></td>
                                <td><?= $pengajuan['no_telp'] ? $pengajuan['no_telp'] : $pengajuan['email']  ?></td>
                                <td><?= $pengajuan['judul'] ?></td>
                                <td><?= $pengajuan['keterangan'] ?></td>

                                <?php
                                $text_color = "text-amber-500";
                                $status = $pengajuan['status'];
                                if ($status === 'Selesai')
                                    $text_color = 'text-green-500';
                                else if ($status === 'Ditolak')
                                    $text_color = 'text-red-500';
                                ?>
                                <td class="<?= $text_color ?>">
                                    <?= $status ?>
                                </td>
                                <td><?= $pengajuan['tgl_dibuat'] ?></td>
                                <td class="flex">
                                    <?php if ($pengajuan['id_detail_jadwal']) : ?>
                                        <div class="flex gap-5">
                                            <a href="../admin/pertemuan.php?reassign_instruktur=<?= $pengajuan['id_detail_jadwal'] ?>&pengajuan=<?= $pengajuan['id_pengajuan'] ?>&instruktur=<?= $pengajuan['id_instruktur'] ?>" class="btn btn--outline-green">Ubah Instruktur</a>
                                            <form class="form flex-1" action="../../api/admin/pengajuan.php" method="post">
                                                <input type="hidden" name="status" value="Ditolak">
                                                <button type="submit" name="update" value="<?= $pengajuan['id_pengajuan'] ?>" class="btn btn--outline-red">Tolak Pengajuan</button>
                                            </form>
                                        </div>
                                    <?php else : ?>
                                        <?php if ($pengajuan['status'] === 'Pending') : ?>
                                            <div class="flex flex-1 gap-5 justify-between">
                                                <form class="form flex-1" action="../../api/admin/pengajuan.php" method="post">
                                                    <input type="hidden" name="status" value="Selesai">
                                                    <button type="submit" name="update" value="<?= $pengajuan['id_pengajuan'] ?>" class="btn btn--outline-green">Tandai Sebagai Selesai</button>
                                                </form>
                                                <form class="form flex-1" action="../../api/admin/pengajuan.php" method="post">
                                                    <input type="hidden" name="status" value="Ditolak">
                                                    <button type="submit" name="update" value="<?= $pengajuan['id_pengajuan'] ?>" class="btn btn--outline-red">Tolak Pengajuan</button>
                                                </form>
                                            </div>
                                        <?php else : ?>
                                            <!-- <form action="../../api/admin/pengajuan.php" method="post">
                                            <input type="hidden" name="status" value="Pending">
                                            <button type="submit" name="update" value="<?= $pengajuan['id_pengajuan'] ?>" class="btn btn--outline-amber">Tandai Sebagai Pending</button>
                                        </form> -->
                                        <?php endif ?>
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