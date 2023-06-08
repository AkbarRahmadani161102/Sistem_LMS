<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('admin', 'jenjang.php');

$sql = "SELECT j.*, COUNT(m.id_mapel) count_mapel, COUNT(k.id_kelas) count_kelas FROM jenjang j
LEFT JOIN mapel m ON j.id_jenjang = m.id_jenjang
LEFT JOIN kelas k ON j.id_jenjang = k.id_jenjang
GROUP BY j.id_jenjang";
$result = $db->query($sql) or die($sql);
$result->fetch_assoc();

$exclude_deletion = ['SD', 'SMP', 'SMA']; // Mencegah kesalahan sistem
?>

<div class="dashboard__main">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="dashboard__content">
        <div class="dashboard__subcontent">
            <?php include_once '../components/dashboard_navbar.php'; ?>

            <div class="flex items-center gap-5">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Jenjang</h4>

                <button data-modal-target="add_jenjang_modal" data-modal-toggle="add_jenjang_modal" class="btn" type="button">
                    Tambah Jenjang
                </button>
            </div>

            <?php generate_breadcrumb([['title' => 'Jenjang', 'filename' => 'jenjang.php']]); ?>
            <div class="table__container">
                <table class="datatable table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Jenjang</th>
                            <th>Biaya Pendidikan</th>
                            <th>Biaya Per Pertemuan</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $key => $value) : ?>
                            <?php $delete_able = $value['count_mapel'] === '0' && $value['count_kelas'] === '0' && !in_array($value['nama'], $exclude_deletion) ?>
                            <tr>
                                <th><?= $key + 1 ?></th>
                                <td><?= $value['nama'] ?></td>
                                <td><?= $value['biaya_pendidikan'] ?></td>
                                <td><?= $value['biaya_per_pertemuan'] ?></td>
                                <td class="flex gap-4">
                                    <button type="button" class="btn btn--outline-blue" data-modal-target="edit<?= $value['id_jenjang'] ?>" data-modal-toggle="edit<?= $value['id_jenjang'] ?>">
                                        <i class="ri-edit-box-line"></i>
                                    </button>

                                    <?php if ($delete_able) : ?>
                                        <button onclick="generateConfirmationDialog('../../api/admin/jenjang.php', {delete: '<?= $value['id_jenjang'] ?>'})" class="btn btn--outline-red">
                                            <i class="ri-delete-bin-6-line"></i>
                                        </button>
                                    <?php endif ?>
                                </td>
                            </tr>


                            <div id="edit<?= $value['id_jenjang'] ?>" tabindex="-1" aria-hidden="true" class="modal">
                                <div class="modal__backdrop">
                                    <!-- Modal content -->
                                    <div class="modal__content">
                                        <!-- Modal header -->
                                        <div class="modal__header">
                                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                Tambah Jenjang
                                            </h3>
                                            <button type="button" class="modal__close" data-modal-hide="edit<?= $value['id_jenjang'] ?>">
                                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="sr-only">Close modal</span>
                                            </button>
                                        </div>
                                        <form action="../../api/admin/jenjang.php" method="post">
                                            <!-- Modal body -->
                                            <div class="modal__body dark:text-white">
                                                <div class="flex flex-col gap-2">
                                                    <label for="nama">Nama Jenjang</label>
                                                    <input type="text" id="nama" name="nama" class="input" value="<?= $value['nama'] ?>">
                                                </div>
                                                <div class="flex flex-col gap-2">
                                                    <label for="biaya_pendidikan">Biaya Pendidikan</label>
                                                    <input type="number" id="biaya_pendidikan" name="biaya_pendidikan" class="input" value="<?= $value['biaya_pendidikan'] ?>">
                                                </div>
                                                <div class="flex flex-col gap-2">
                                                    <label for="biaya_per_pertemuan">Biaya Per Pertemuan</label>
                                                    <input type="number" id="biaya_per_pertemuan" name="biaya_per_pertemuan" class="input" value="<?= $value['biaya_per_pertemuan'] ?>">
                                                </div>
                                            </div>
                                            <!-- Modal footer -->
                                            <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                                                <button type="submit" value="<?= $value['id_jenjang'] ?>" name="update" class="btn btn--blue">Ubah</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="add_jenjang_modal" tabindex="-1" aria-hidden="true" class="modal">
    <div class="modal__backdrop">
        <!-- Modal content -->
        <div class="modal__content">
            <!-- Modal header -->
            <div class="modal__header">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Ubah Jenjang
                </h3>
                <button type="button" class="modal__close" data-modal-hide="add_jenjang_modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <form class="form" action="../../api/admin/jenjang.php" method="post">
                <!-- Modal body -->
                <div class="modal__body">
                    <div class="flex flex-col gap-2">
                        <label for="nama">Nama Jenjang</label>
                        <input type="text" id="nama" name="nama" class="input">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="biaya_pendidikan">Biaya Pendidikan</label>
                        <input type="number" id="biaya_pendidikan" name="biaya_pendidikan" class="input">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="biaya_per_pertemuan">Biaya Per Pertemuan</label>
                        <input type="number" id="biaya_per_pertemuan" name="biaya_per_pertemuan" class="input" value="<?= $value['biaya_per_pertemuan'] ?>">
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button type="submit" name="create" class="btn btn--blue">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>