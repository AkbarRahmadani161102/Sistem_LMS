<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access(['Super Admin', 'Admin Akademik']);

$sql = "SELECT * FROM jenjang";
$result = $db->query($sql) or die($sql);
$result->fetch_assoc();
?>

<div id="jenjang" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php'; ?>

            <div class="flex items-center gap-5">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Jenjang</h4>

                <button data-modal-target="add_jenjang_modal" data-modal-toggle="add_jenjang_modal" class="btn" type="button">
                    Tambah Jenjang
                </button>
            </div>

            <?php generate_breadcrumb([['title' => 'Jenjang', 'filename' => 'jenjang.php']]); ?>
            <div class="relative overflow-x-auto mt-5">
                <table class="datatable w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3"></th>
                            <th scope="col" class="px-6 py-3">Jenjang</th>
                            <th scope="col" class="px-6 py-3">Biaya Pendidikan</th>
                            <th scope="col" class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $key => $value) : ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th class="px-6 py-4 text-amber-500"><?= $key + 1 ?></th>
                                <td class="px-6 py-4"><?= $value['nama'] ?></td>
                                <td class="px-6 py-4"><?= $value['biaya_pendidikan'] ?></td>
                                <td class="px-6 py-4 flex gap-4">
                                    <button type="button" class="btn btn--outline-blue group" data-modal-target="edit<?= $value['id_jenjang'] ?>" data-modal-toggle="edit<?= $value['id_jenjang'] ?>">
                                        <i class="ri-edit-box-line text-blue-500 group-hover:text-white"></i>
                                    </button>
                                    <form action="../../api/admin/jenjang.php" method="post">
                                        <button type="submit" class="btn btn--outline-blue group" name="delete" value="<?= $value['id_jenjang'] ?>">
                                            <i class="ri-delete-bin-6-line text-red-500 group-hover:text-white"></i>
                                        </button>
                                    </form>
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
                                            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="edit<?= $value['id_jenjang'] ?>">
                                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="sr-only">Close modal</span>
                                            </button>
                                        </div>
                                        <form action="../../api/admin/jenjang.php" method="post">
                                            <!-- Modal body -->
                                            <div class="modal__body">
                                                <div class="flex flex-col gap-2">
                                                    <label for="">Nama Jenjang</label>
                                                    <input type="text" name="nama" class="input" value="<?= $value['nama'] ?>">
                                                </div>
                                                <div class="flex flex-col gap-2">
                                                    <label for="">Biaya Pendidikan</label>
                                                    <input type="number" name="biaya_pendidikan" class="input" value="<?= $value['biaya_pendidikan'] ?>">
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
                    Tambah Jenjang
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="add_jenjang_modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <form action="../../api/admin/jenjang.php" method="post">
                <!-- Modal body -->
                <div class="modal__body">
                    <div class="flex flex-col gap-2">
                        <label for="">Nama Jenjang</label>
                        <input type="text" name="nama" class="input">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="">Biaya Pendidikan</label>
                        <input type="number" name="biaya_pendidikan" class="input">
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