<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('siswa');

$id_siswa = $_SESSION['user_id'];
$sql = "SELECT * FROM pengajuan p WHERE p.id_siswa = '$id_siswa'";
$data_tunggakan = $db->query($sql);
?>

<div class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Pengajuan', 'filename' => 'pengajuan.php']]);
            ?>

            <div class="flex gap-2 items-center">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Pengajuan</h4>
                <button class="btn" data-modal-target="add-pengajuan-modal" data-modal-toggle="add-pengajuan-modal">Ajukan Pengajuan</button>
            </div>

            <div class="table__container">
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Judul</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $data_tunggakan->fetch_assoc()) : ?>
                            <tr>
                                <th></th>
                                <td><?= $row['judul'] ?></td>
                                <td><?= $row['keterangan'] ?></td>
                                <td><?= $row['status'] ?></td>
                                <form action="../../api/siswa/pengajuan.php" method="post">
                                    <td><button type="submit" name="delete" value="<?= $row['id_pengajuan'] ?>" class="btn btn--outline-red"><i class="ri-delete-bin-line"></i></button></td>
                                </form>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="add-pengajuan-modal" tabindex="-1" aria-hidden="true" class="modal">
    <div class="modal__backdrop">
        <div class="modal__content">
            <!-- Modal header -->
            <div class="modal__header">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Ajukan Pengajuan
                </h3>
                <button type="button" class="modal__close" data-modal-hide="add-pengajuan-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <form class="form" action="../../api/siswa/pengajuan.php" method="post">
                <!-- Modal body -->
                <div class="modal__body">
                    <label class="block" for="judul">Judul</label>
                    <input id="judul" class="border rounded w-full py-1.5 px-2 border-gray-400 mt-1" name="judul" required />

                    <label for="keterangan" class="block">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="4" class="border rounded w-full py-1.5 px-2 border-gray-400 mt-1" placeholder="Write your thoughts here..."></textarea>
                </div>
                <!-- Modal footer -->
                <div class="modal__footer">
                    <button name="create" type="submit" class="btn btn--blue">Submit</button>
                    <button data-modal-hide="add-pengajuan-modal" type="button" class="btn">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>