<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('admin', 'hak_akses.php');

$sql = "SELECT *, r.id_role, COUNT(id_admin) count_user,  (SELECT COUNT(id_detail_hak_akses) FROM detail_hak_akses WHERE id_role = r.id_role) count_hak_akses FROM role r 
LEFT JOIN detail_role dr ON r.id_role = dr.id_role 
LEFT JOIN detail_hak_akses dha ON r.id_role = dha.id_role
GROUP BY r.id_role

";
$result = $db->query($sql) or die($sql);
$result->fetch_assoc();

if (isset($_GET['sync'])) {
    $path = './';
    $file_hak_akses = array_diff(scandir($path), array('.', '..', 'login.php', 'index.php'));

    $sql = "SELECT * FROM hak_akses";
    $data_hak_akses = $db->query($sql) or die($db->error);
    $arr_hak_akses = [];
    foreach ($data_hak_akses as $hak_akses) {
        $arr_hak_akses[] = $hak_akses['nama_file'];
    }

    $total_hak_akses = 0;
}
if (isset($_GET['create'])) {
    $sql = "SELECT * FROM hak_akses ORDER BY kategori";
    $data_hak_akses = $db->query($sql) or die($db->error);
}
if (isset($_GET['update'])) {
    $id_role = $_GET['update'];
    $sql = "SELECT * FROM role WHERE id_role = '$id_role'";
    $data_role = $db->query($sql)->fetch_assoc() or die($db->error);

    $sql = "SELECT * FROM hak_akses ha
    JOIN detail_hak_akses dha ON ha.id_hak_akses = dha.id_hak_akses
    JOIN role r ON dha.id_role = r.id_role
    WHERE r.id_role = $id_role";
    $arr_hak_akses = [];
    $hak_akses_role = $db->query($sql) or die($db->error);

    foreach ($hak_akses_role as $hak_akses)
        $arr_hak_akses[] = $hak_akses['nama'];

    $sql = "SELECT * FROM hak_akses ORDER BY kategori";
    $data_hak_akses = $db->query($sql) or die($db->error);
}
?>

<div class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php'; ?>

            <div class="flex items-center gap-5">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Role Admin</h4>
                <?php if (isset($_GET['create'])) : ?>
                    <a href="?sync" class="btn">Update Hak Akses</a>
                <?php else : ?>
                    <a href="?create" class="btn">Tambah</a>
                <?php endif ?>
            </div>

            <?php if (isset($_GET['sync'])) : ?>
                <?php generate_breadcrumb([['title' => 'Role Admin', 'filename' => 'hak_akses.php'], ['title' => 'Tambah', 'filename' => 'hak_akses.php?create'], ['title' => 'Update Hak Akses', 'filename' => '#']]); ?>

                <form method="post" action="../../api/admin/hak_akses.php" class="form bg-gray-200 dark:bg-gray-700 p-5 rounded mt-5">
                    <div class="flex flex-wrap gap-2 justify-between">
                        <?php foreach ($file_hak_akses as $key => $file) : ?>
                            <?php if (!in_array($file, $arr_hak_akses)) : ?>
                                <?php $total_hak_akses++ ?>
                                <div class="flex flex-col bg-white dark:bg-gray-800 rounded text-gray-800 dark:text-white p-5 mb-5 gap-5 w-25">
                                    <h6><?= $file ?></h6>
                                    <div class="flex flex-col gap-3">
                                        <label for="nama">Nama Hak Akses</label>
                                        <?php
                                        $find = ['.php', '_'];
                                        $replace = ['', ' '];
                                        $nama = ucwords(str_replace($find, $replace, $file));
                                        ?>
                                        <input type="text" name="data_hak_akses[<?= $key ?>][nama]" value="<?= $nama ?>" class="input" required>
                                    </div>
                                    <div class="flex flex-col gap-3">
                                        <div class="flex gap-1">
                                            <label for="kategori">Kategori</label>
                                            <button data-popover-target="help_kategori<?= $key ?>" data-popover-placement="right" type="button" class="text-gray-800 dark:text-white"><i class="ri-question-line"></i></button>
                                            <div data-popover id="help_kategori<?= $key ?>" role="tooltip" class="absolute z-10 invisible inline-block w-64 text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800">
                                                <div class="px-3 py-2 bg-gray-100 border-b border-gray-200 rounded-t-lg dark:border-gray-600 dark:bg-gray-700">
                                                    <h6 class="font-semibold text-gray-900 dark:text-white">Kategori</h6>
                                                </div>
                                                <div class="px-3 py-2"><i class="ri-school-line"></i>
                                                    <div class="flex flex-col gap-3">
                                                        <p>Merupakan penggabung submenu pada bagian navigasi</p>
                                                    </div>
                                                </div>
                                                <div data-popper-arrow></div>
                                            </div>
                                        </div>
                                        <input type="text" name="data_hak_akses[<?= $key ?>][kategori]" class="input">
                                    </div>
                                    <div class="flex flex-col gap-3">
                                        <div class="flex gap-1">
                                            <label for="icon">Icon</label>
                                            <button data-popover-target="help_icon<?= $key ?>" data-popover-placement="right" type="button" class="text-gray-800 dark:text-white"><i class="ri-question-line"></i></button>
                                            <div data-popover id="help_icon<?= $key ?>" role="tooltip" class="absolute z-10 invisible inline-block w-64 text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800">
                                                <div class="px-3 py-2 bg-gray-100 border-b border-gray-200 rounded-t-lg dark:border-gray-600 dark:bg-gray-700">
                                                    <h6 class="font-semibold text-gray-900 dark:text-white">Icon</h6>
                                                </div>
                                                <div class="px-3 py-2"><i class="ri-school-line"></i>
                                                    <div class="flex flex-col gap-3">
                                                        <p>Icon digunakan sebagai penanda pada masing masing menu, contoh input</p>
                                                        <code class="bg-gray-200 rounded p-2">&lt;i class="ri-school-line"&gt;&lt;/i&gt;</code>
                                                        <a class="text-blue-500 hover:text-blue-700" target="_blank" href="https://remixicon.com/">Dokumentasi Icon</a>
                                                    </div>
                                                </div>
                                                <div data-popper-arrow></div>
                                            </div>
                                        </div>
                                        <input type="text" name="data_hak_akses[<?= $key ?>][icon]" class="input" maxlength="50">
                                    </div>
                                    <input type="hidden" name="data_hak_akses[<?= $key ?>][nama_file]" value="<?= $file ?>">
                                </div>
                            <?php endif ?>
                        <?php endforeach ?>
                    </div>
                    <?php if ($total_hak_akses > 0) : ?>
                        <button type="submit" name="sync" value="ok" class="btn btn--blue w-fit">Tambah</button>
                    <?php else : ?>
                        <h5 class="text-gray-800 dark:text-white">Hak akses telah disinkronkan</h5>
                    <?php endif ?>
                </form>
            <?php endif ?>

            <?php if (isset($_GET['create'])) : ?>
                <?php generate_breadcrumb([['title' => 'Role Admin', 'filename' => 'hak_akses.php'], ['title' => 'Tambah', 'filename' => '#']]); ?>

                <form action="../../api/admin/hak_akses.php" method="post" class="form flex flex-col gap-2 bg-gray-200 dark:bg-gray-700 p-5 rounded mt-5">
                    <label for="nama_role">Nama Role</label>
                    <input id="nama_role" name="title" type="text" class="input" required>

                    <div class="overflow-auto bg-white dark:bg-gray-800 px-8 rounded mt-5">
                        <h4 class="text-gray-800 dark:text-white mt-5">Data Hak Akses</h4>
                        <table class="datatable-disable-paging table">
                            <thead>
                                <tr>
                                    <th>Nama Hak Akses</th>
                                    <th>Kategori</th>
                                    <th>Akses yang diberikan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data_hak_akses as $index => $hak_akses) : ?>
                                    <tr class="relative">
                                        <td class="flex items-center gap-1">
                                            <?= $hak_akses['nama'] ?>
                                            <button type="button" data-modal-target="edit_hak_akses<?= $index ?>" data-modal-toggle="edit_hak_akses<?= $index ?>" class="relative z-20 text-blue-500">
                                                <i class="ri-edit-box-line"></i>
                                            </button>
                                        </td>
                                        <td><?= $hak_akses['kategori'] ?></td>
                                        <td>
                                            <label for="check<?= $index ?>" class="absolute top-0 left-0 w-full h-full">&nbsp;</label>
                                            <input id="check<?= $index ?>" name="hak_akses[]" value="<?= $hak_akses['id_hak_akses'] ?>" type="checkbox" name="hak_akses">
                                        </td>
                                    </tr>

                                    <div id="edit_hak_akses<?= $index ?>" tabindex="-1" aria-hidden="true" class="modal">
                                        <div class="modal__backdrop">
                                            <form action="../../api/admin/hak_akses.php" method="post">
                                                <div class="modal__content">
                                                    <div class="modal__header">
                                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                            Update <?= $hak_akses['nama'] ?>
                                                        </h3>
                                                        <button type="button" class="modal__close-button" data-modal-hide="edit_hak_akses<?= $index ?>">
                                                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <div class="modal__body">
                                                        <div class="flex flex-col gap-2">
                                                            <label for="nama">Nama</label>
                                                            <input id="nama" name="nama" type="text" class="input" value="<?= $hak_akses['nama'] ?>" required>
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label for="kategori">Kategori</label>
                                                            <input id="kategori" name="kategori" type="text" class="input" value="<?= $hak_akses['kategori'] ?>" required>
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <div class="flex gap-1">
                                                                <label for="icon">Icon</label>
                                                                <button data-popover-target="help_update_icon<?= $index ?>" data-popover-placement="right" type="button" class="text-gray-800 dark:text-white"><i class="ri-question-line"></i></button>
                                                                <div data-popover id="help_update_icon<?= $index ?>" role="tooltip" class="absolute z-10 invisible inline-block w-64 text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800">
                                                                    <div class="px-3 py-2 bg-gray-100 border-b border-gray-200 rounded-t-lg dark:border-gray-600 dark:bg-gray-700">
                                                                        <h6 class="font-semibold text-gray-900 dark:text-white">Icon</h6>
                                                                    </div>
                                                                    <div class="px-3 py-2"><i class="ri-school-line"></i>
                                                                        <div class="flex flex-col gap-3">
                                                                            <p>Icon digunakan sebagai penanda pada masing masing menu, contoh input</p>
                                                                            <code class="bg-gray-200 rounded p-2">&lt;i class="ri-school-line"&gt;&lt;/i&gt;</code>
                                                                            <a class="text-blue-500 hover:text-blue-700" target="_blank" href="https://remixicon.com/">Dokumentasi Icon</a>
                                                                        </div>
                                                                    </div>
                                                                    <div data-popper-arrow></div>
                                                                </div>
                                                            </div>
                                                            <input id="icon" name="icon" type="text" class="input" value="<?= htmlentities($hak_akses['icon']) ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal__footer">
                                                        <button type="submit" name="update_hak_akses" value="<?= $hak_akses['id_hak_akses'] ?>" class="btn btn--blue">Update</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" name="create" class="btn btn--blue w-fit mt-5">Tambah</button>
                </form>
            <?php endif ?>

            <?php if (isset($_GET['update'])) : ?>
                <?php generate_breadcrumb([['title' => 'Role Admin', 'filename' => 'hak_akses.php'], ['title' => 'Tambah', 'filename' => '#']]); ?>

                <form action="../../api/admin/hak_akses.php" method="post" class="form flex flex-col gap-2 bg-gray-200 dark:bg-gray-700 p-5 rounded mt-5">
                    <label for="nama_role">Nama Role</label>
                    <input id="nama_role" name="title" type="text" class="input" value="<?= $data_role['title'] ?>" required>

                    <div class="overflow-auto bg-white dark:bg-gray-800 px-8 rounded mt-5">
                        <h4 class="text-gray-800 dark:text-white mt-5">Data Hak Akses</h4>
                        <table class="datatable-disable-paging table">
                            <thead>
                                <tr>
                                    <th>Nama Hak Akses</th>
                                    <th>Kategori</th>
                                    <th>Akses yang diberikan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data_hak_akses as $index => $hak_akses) : ?>
                                    <tr class="relative">
                                        <td class="flex items-center gap-1">
                                            <?= $hak_akses['nama'] ?>
                                            <button type="button" data-modal-target="edit_hak_akses<?= $index ?>" data-modal-toggle="edit_hak_akses<?= $index ?>" class="relative z-20 text-blue-500">
                                                <i class="ri-edit-box-line"></i>
                                            </button>
                                        </td>
                                        <td><?= $hak_akses['kategori'] ?></td>
                                        <td>
                                            <label for="check<?= $index ?>" class="absolute top-0 left-0 w-full h-full">&nbsp;</label>
                                            <input id="check<?= $index ?>" name="hak_akses[]" value="<?= $hak_akses['id_hak_akses'] ?>" type="checkbox" name="hak_akses" <?= in_array($hak_akses['nama'], $arr_hak_akses) ? 'checked' : '' ?>>
                                        </td>
                                    </tr>

                                    <div id="edit_hak_akses<?= $index ?>" tabindex="-1" aria-hidden="true" class="modal">
                                        <div class="modal__backdrop">
                                            <form action="../../api/admin/hak_akses.php" method="post">
                                                <div class="modal__content">
                                                    <div class="modal__header">
                                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                            Update <?= $hak_akses['nama'] ?>
                                                        </h3>
                                                        <button type="button" class="modal__close-button" data-modal-hide="edit_hak_akses<?= $index ?>">
                                                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <div class="modal__body">
                                                        <div class="flex flex-col gap-2">
                                                            <label for="nama">Nama</label>
                                                            <input id="nama" name="nama" type="text" class="input" value="<?= $hak_akses['nama'] ?>" required>
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <label for="kategori">Kategori</label>
                                                            <input id="kategori" name="kategori" type="text" class="input" value="<?= $hak_akses['kategori'] ?>" required>
                                                        </div>
                                                        <div class="flex flex-col gap-2">
                                                            <div class="flex gap-1">
                                                                <label for="icon">Icon</label>
                                                                <button data-popover-target="help_update_icon<?= $index ?>" data-popover-placement="right" type="button" class="text-gray-800 dark:text-white"><i class="ri-question-line"></i></button>
                                                                <div data-popover id="help_update_icon<?= $index ?>" role="tooltip" class="absolute z-10 invisible inline-block w-64 text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800">
                                                                    <div class="px-3 py-2 bg-gray-100 border-b border-gray-200 rounded-t-lg dark:border-gray-600 dark:bg-gray-700">
                                                                        <h6 class="font-semibold text-gray-900 dark:text-white">Icon</h6>
                                                                    </div>
                                                                    <div class="px-3 py-2"><i class="ri-school-line"></i>
                                                                        <div class="flex flex-col gap-3">
                                                                            <p>Icon digunakan sebagai penanda pada masing masing menu, contoh input</p>
                                                                            <code class="bg-gray-200 rounded p-2">&lt;i class="ri-school-line"&gt;&lt;/i&gt;</code>
                                                                            <a class="text-blue-500 hover:text-blue-700" target="_blank" href="https://remixicon.com/">Dokumentasi Icon</a>
                                                                        </div>
                                                                    </div>
                                                                    <div data-popper-arrow></div>
                                                                </div>
                                                            </div>
                                                            <input id="icon" name="icon" type="text" class="input" value="<?= htmlentities($hak_akses['icon']) ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal__footer">
                                                        <button type="submit" name="update_hak_akses" value="<?= $hak_akses['id_hak_akses'] ?>" class="btn btn--blue">Update</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" name="update" value="<?= $id_role ?>" class="btn btn--blue w-fit mt-5">Update</button>
                </form>
            <?php endif ?>
            <?php if (!isset($_GET['create']) && !isset($_GET['sync']) && !isset($_GET['update'])) : ?>
                <?php generate_breadcrumb([['title' => 'Role Admin', 'filename' => 'hak_akses.php']]); ?>
                <div class="table__container">
                    <table class="datatable table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Title</th>
                                <th>Hak Akses</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($result as $key => $value) : ?>
                                <tr>
                                    <th></th>
                                    <td><?= $value['title'] ?></td>
                                    <td>
                                        <div class="flex items-center gap-1">
                                            <?= $value['count_hak_akses'] ?>
                                            <button data-popover-target="data_hak_akses<?= $key ?>" data-popover-placement="right" type="button" class="text-white"><i class="ri-question-line"></i></button>
                                        </div>
                                        <div data-popover id="data_hak_akses<?= $key ?>" role="tooltip" class="absolute z-10 invisible inline-block w-64 text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800">
                                            <div class="px-3 py-2 bg-gray-100 border-b border-gray-200 rounded-t-lg dark:border-gray-600 dark:bg-gray-700">
                                                <h5 class="font-semibold text-gray-900 dark:text-white">Hak Akses</h5>
                                            </div>
                                            <div class="px-3 py-2 space-y-2 max-h-72 overflow-auto">
                                                <?php
                                                $id_role = $value['id_role'];
                                                $sql = "SELECT * FROM detail_hak_akses dha
                                                JOIN hak_akses ha ON dha.id_hak_akses = ha.id_hak_akses
                                                WHERE dha.id_role = '$id_role'";
                                                $data_hak_akses = $db->query($sql) or die(mysqli_error($db));
                                                $data_hak_akses->fetch_assoc();
                                                foreach ($data_hak_akses as $key => $hak_akses) : ?>
                                                    <p><?= $hak_akses['nama'] ?></p>
                                                <?php endforeach ?>
                                            </div>
                                            <div data-popper-arrow></div>
                                        </div>
                                    </td>
                                    <td class="flex gap-4">
                                        <a href="?update=<?= $value['id_role'] ?>" class="btn btn--outline-blue">
                                            <i class="ri-edit-box-line"></i>
                                        </a>
                                        <?php if ($value['count_user'] < 1 || $value['title'] !== 'Super Admin') : ?>
                                            <button onclick="generateConfirmationDialog('../../api/admin/hak_akses.php', {delete: '<?= $value['id_role'] ?>'})" class="btn btn--outline-red">
                                                <i class="ri-delete-bin-6-line"></i>
                                            </button>
                                        <?php endif ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>