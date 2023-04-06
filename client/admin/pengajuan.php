<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access(['Super Admin', 'Admin Akademik']);

$sql = "SELECT *, p.status, s.nama nama_siswa FROM pengajuan p, siswa s WHERE p.id_siswa = s.id_siswa";
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
                                <td><?= $pengajuan['judul'] ?></td>
                                <td><?= $pengajuan['keterangan'] ?></td>
                                <td class="<?= $pengajuan['status'] === 'Pending' ? 'text-amber-500' : 'text-green-500' ?>"><?= $pengajuan['status'] ?></td>
                                <td><?= $pengajuan['tgl_dibuat'] ?></td>
                                <td>
                                    <?php if ($pengajuan['status'] === 'Pending') : ?>
                                        <form action="../../api/admin/pengajuan.php" method="post">
                                            <input type="hidden" name="status" value="Selesai">
                                            <button type="submit" name="update" value="<?= $pengajuan['id_pengajuan'] ?>" class="btn btn--outline-green flex items-center gap-2"><i class="ri-check-double-line"></i> Tandai Sebagai Selesai</button>
                                        </form>
                                    <?php else : ?>
                                        <form action="../../api/admin/pengajuan.php" method="post">
                                            <input type="hidden" name="status" value="Pending">
                                            <button type="submit" name="update" value="<?= $pengajuan['id_pengajuan'] ?>" class="btn btn--outline-amber flex items-center gap-2"><i class="ri-history-line"></i> Tandai Sebagai Pending</button>
                                        </form>
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