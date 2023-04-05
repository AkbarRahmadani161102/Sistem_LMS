<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access(['Super Admin']);

$sql = "SELECT * FROM role";
$result = $db->query($sql) or die($sql);
$result->fetch_assoc();
?>

<div id="anggota_kelas" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php'; ?>

            <div class="flex items-center gap-5">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Role Admin</h4>

                <button data-modal-target="add_role_modal" data-modal-toggle="add_role_modal" class="btn" type="button">
                    Tambah Role
                </button>
            </div>

            <?php generate_breadcrumb([['title' => 'Role Admin', 'filename' => 'role_admin.php']]); ?>
            <div class="relative overflow-x-auto mt-5">
                <table class="datatable w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3"></th>
                            <th scope="col" class="px-6 py-3">Title</th>
                            <th scope="col" class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $key => $value) : ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th class="px-6 py-4 text-amber-500"></th>
                                <td class="px-6 py-4"><?= $value['title'] ?></td>
                                <td class="px-6 py-4 flex gap-4">
                                    <button type="button" class="btn btn--outline-blue group" data-modal-target="edit<?= $value['id_role'] ?>" data-modal-toggle="edit<?= $value['id_role'] ?>">
                                        <i class="ri-edit-box-line text-blue-500 group-hover:text-white"></i>
                                    </button>
                                    <form action="../../api/admin/admin_role.php" method="post">
                                        <button type="submit" class="btn btn--outline-blue group" name="delete" value="<?= $value['id_role'] ?>">
                                            <i class="ri-delete-bin-6-line text-red-500 group-hover:text-white"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <div id="edit<?= $value['id_role'] ?>" tabindex="-1" aria-hidden="true" class="modal">
                                <div class="modal__backdrop">
                                    <!-- Modal content -->
                                    <div class="modal__content">
                                        <!-- Modal header -->
                                        <form action="../../api/admin/admin_role.php" method="post">
                                            <div class="modal__header">
                                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                    Edit Role <?= $value['title'] ?>
                                                </h3>
                                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="edit<?= $value['id_role'] ?>">
                                                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span class="sr-only">Close modal</span>
                                                </button>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="modal__body">
                                                <input type="text" name="title" class="input" value="<?= $value['title'] ?>">
                                            </div>
                                            <!-- Modal footer -->
                                            <div class="modal__footer">
                                                <button type="submit" name="update" value="<?= $value['id_role'] ?>" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Ubah</button>
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

<div id="add_role_modal" tabindex="-1" aria-hidden="true" class="modal">
    <div class="modal__backdrop">
        <!-- Modal content -->
        <div class="modal__content">
            <!-- Modal header -->
            <form class="form" action="../../api/admin/admin_role.php" method="post">
                <div class="modal__header">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Tambah Role
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="add_role_modal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="modal__body">
                    <label for="title">Nama role</label>
                    <input id="title" type="text" name="title" class="input">
                </div>
                <!-- Modal footer -->
                <div class="modal__footer">
                    <button ype="submit" name="create" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$result->free_result();
include_once('../template/footer.php') ?>