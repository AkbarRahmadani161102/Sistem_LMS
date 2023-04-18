<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access(['Super Admin']);

$sql = "SELECT * FROM role";
$data_hak_akses = $db->query($sql) or die($db);
$data_hak_akses->fetch_assoc();


if (isset($_GET['edit'])) {
    $id_admin = $_GET['edit'];
    $sql = "SELECT a.*, r.* FROM admin a, role r, detail_role dr WHERE a.id_admin = dr.id_admin AND r.id_role = dr.id_role AND a.id_admin = '$id_admin'";
    $result = $db->query($sql) or die($db);
    $result->fetch_assoc();

    $hak_akses_admin = [];
    foreach ($result as $key => $value) {
        $hak_akses_admin[] = $value['id_role'];
    }
} else {
    $sql = "SELECT * FROM admin";
    $result = $db->query($sql) or die($db);
    $result->fetch_assoc();
}

?>

<div id="anggota_kelas" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php'; ?>

            <div class="flex items-center gap-5">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Staff Administrasi</h4>

                <button data-modal-target="add_admin_modal" data-modal-toggle="add_admin_modal" class="btn" type="button">
                    Tambah Staff
                </button>
            </div>

            <?php if (isset($_GET['edit'])) : ?>
                <?php generate_breadcrumb([['title' => 'Staff Administrasi', 'filename' => 'admin.php'], ['title' => 'Edit Staff Administrasi', 'filename' => '#']]); ?>

                <?php foreach ($result as $key => $admin_data) :  ?>
                    <div class="flex gap-5 mt-5 flex-col md:flex-row">
                        <div class="flex flex-1 flex-col gap-5 bg-gray-200 dark:bg-gray-700 shadow-lg rounded p-5">
                            <h4 class="text-gray-800 dark:text-white">Data Personal</h4>
                            <form class="flex-1 flex flex-col justify-between" action="../../api/admin/admin.php" method="post">
                                <div class="mb-5">
                                    <label for="nama" class="form-label text-secondary text-gray-400 dark:text-white">Nama</label>
                                    <input type="text" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="nama" name="nama" value="<?= $admin_data['nama'] ?>" maxlength="50" required>
                                </div>
                                <div class="mb-5">
                                    <label for="no_telp" class="form-label text-secondary text-gray-400 dark:text-white">No Telp</label>
                                    <input type="text" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="no_telp" name="no_telp" value="<?= $admin_data['no_telp'] ?>" maxlength="14" required>
                                </div>
                                <div class="mb-5">
                                    <label for="alamat" class="form-label text-secondary text-gray-400 dark:text-white">Alamat</label>
                                    <textarea class="resize-none border rounded w-full py-1.5 border-gray-400 mt-1" name="alamat" id="" cols="30" rows="3" maxlength="50"><?= $admin_data['alamat'] ?>"</textarea>
                                </div>
                                <button type="submit" class="w-full bg-orange-500 rounded py-1.5 text-white" name="update_profil" value="<?= $admin_data['id_admin'] ?>">Ubah Data Profil</button>
                            </form>
                        </div>

                        <div class="flex flex-1 flex-col gap-5 bg-gray-200 dark:bg-gray-700 shadow-lg rounded p-5">
                            <h4 class="text-gray-800 dark:text-white">Data Kredensial</h4>
                            <form class="flex-1 flex flex-col justify-between" action="../../api/admin/admin.php" method="post">
                                <div class="mb-5">
                                    <label for="email" class="form-label text-secondary text-gray-400 dark:text-white">Email</label>
                                    <input type="email" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="email" name="email" value="<?= $admin_data['email'] ?>" maxlength="30" required>
                                </div>
                                <div class="mb-5">
                                    <label for="password" class="form-label text-secondary text-gray-400 dark:text-white">Password</label>
                                    <input type="password" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="password" name="password" value="<?= $admin_data['password'] ?>" maxlength="50" required>
                                </div>
                                <div class="mb-5">
                                    <label for="confirm_password" class="form-label text-secondary text-gray-400 dark:text-white">Konfirmasi Password</label>
                                    <input type="password" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="confirm_password" name="confirm_password" value="<?= $admin_data['password'] ?>" maxlength="50" required>
                                </div>
                                <button type="submit" class="w-full bg-red-500 rounded py-1.5 text-white" name="update_kredensial" value="<?= $admin_data['id_admin'] ?>">Ubah Data Kredensial</button>
                            </form>
                        </div>

                        <div class="flex flex-1 flex-col gap-5 bg-gray-200 dark:bg-gray-700 shadow-lg rounded p-5">
                            <h4 class="text-gray-800 dark:text-white">Hak Akses</h4>
                            <form id="form_hak_akses_admin" class="flex-1 flex flex-col justify-between" action="../../api/admin/admin.php" method="post">
                                <div class="flex flex-col gap-5">
                                    <?php foreach ($data_hak_akses as $key => $value) : ?>
                                        <label class="flex items-center bg-white dark:bg-gray-200 p-5 rounded">
                                            <input name="hak_akses[]" id="<?= $value['id_role'] ?>" type="checkbox" value="<?= $value['id_role'] ?>" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600" <?= in_array($value['id_role'], $hak_akses_admin) ? 'checked' : '' ?>>
                                            <p for="<?= $value['id_role'] ?>" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-800"><?= $value['title'] ?></p>
                                        </label>
                                    <?php endforeach ?>
                                </div>
                                <button type="submit" class="w-full bg-gray-500 rounded py-1.5 text-white" name="update_hak_akses" value="<?= $admin_data['id_admin'] ?>">Ubah Hak Akses</button>
                            </form>
                        </div>

                    </div>
                    <?php return // Agar menampilkan 1 data saja 
                    ?>
                <?php endforeach ?>

            <?php else : ?>
                <?php generate_breadcrumb([['title' => 'Staff Administrasi', 'filename' => 'admin.php']]); ?>
                <div class="relative overflow-x-auto mt-5">
                    <table class="datatable w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3"></th>
                                <th scope="col" class="px-6 py-3">Nama</th>
                                <th scope="col" class="px-6 py-3">No.Telp</th>
                                <th scope="col" class="px-6 py-3">Alamat</th>
                                <th scope="col" class="px-6 py-3">Email</th>
                                <th scope="col" class="px-6 py-3">Role</th>
                                <th scope="col" class="px-6 py-3">Lama Bekerja</th>
                                <th scope="col" class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($result as $key => $value) : ?>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th class="px-6 py-4 text-amber-500"></th>
                                    <td class="px-6 py-4"><?= $value['nama'] ?></td>
                                    <td class="px-6 py-4"><?= $value['no_telp'] ?></td>
                                    <td class="px-6 py-4"><?= $value['alamat'] ?></td>
                                    <td class="px-6 py-4"><?= $value['email'] ?></td>
                                    <td class="px-6 py-4">
                                        <?php
                                        $id_admin = $value['id_admin'];
                                        $sql = "SELECT r.title FROM admin a, role r, detail_role dr WHERE dr.id_role = r.id_role AND dr.id_admin = a.id_admin AND a.id_admin = '$id_admin'";
                                        $roles = $db->query($sql) or die(mysqli_error($db));
                                        $roles->fetch_assoc();
                                        foreach ($roles as $key => $role) {
                                            echo $role['title'];
                                            echo "<br/>";
                                        }
                                        ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php
                                        $from = new DateTime($value['tgl_dibuat']);
                                        $to = new DateTime("now");
                                        $range = $from->diff($to);
                                        $range_int = (int) $range->format('%a');
                                        echo $range_int < 365
                                            ? "<span class='text-green-500'>NEW</span>"
                                            : $range->format('%R%a days/%R%y years');
                                        ?>
                                    </td>

                                    <td class="px-6 py-4 flex gap-2">
                                        <a class="btn btn--outline-blue" href="?edit=<?= $value['id_admin'] ?>">
                                            <i class="ri-edit-box-line"></i>
                                        </a>
                                        <form action="../../api/admin/admin.php" method="post">
                                            <button class="btn btn--outline-red" type="submit" name="delete" value="<?= $value['id_admin'] ?>">
                                                <i class="ri-delete-bin-6-line"></i>
                                            </button>
                                        </form>
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

<div id="add_admin_modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
    <div class="relative w-full h-full max-w-7xl md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="px-6 py-6 lg:px-8">
                <form action="../../api/admin/admin.php" method="post">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between border-b rounded-t dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Tambah Staff Administrasi
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="add_admin_modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="pt-6 flex gap-5">
                        <div class="flex-1 flex flex-col" method="post">
                            <div class="mb-5">
                                <label for="nama" class="form-label text-secondary text-gray-400 dark:text-white">Nama</label>
                                <input type="text" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="nama" name="nama" maxlength="50" required>
                            </div>
                            <div class="mb-5">
                                <label for="no_telp" class="form-label text-secondary text-gray-400 dark:text-white">No Telp</label>
                                <input type="text" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="no_telp" name="no_telp" maxlength="14" required>
                            </div>
                            <div class="mb-5">
                                <label for="alamat" class="form-label text-secondary text-gray-400 dark:text-white">Alamat</label>
                                <textarea class="resize-none border rounded w-full py-1.5 border-gray-400 mt-1" name="alamat" id="" cols="30" rows="3" maxlength="50"></textarea>
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col" method="post">
                            <div class="mb-5">
                                <label for="email" class="form-label text-secondary text-gray-400 dark:text-white">Email</label>
                                <input type="email" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="email" name="email" maxlength="30" required>
                            </div>
                            <div class="mb-5">
                                <label for="password" class="form-label text-secondary text-gray-400 dark:text-white">Password</label>
                                <input type="text" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="password" name="password" maxlength="50" required>
                            </div>
                        </div>
                        <div id="form_hak_akses_admin" class="flex-1 flex flex-col" method="post">
                            <p class="text-normal text-gray-400 dark:text-white">Role</p>
                            <div class="flex flex-col gap-5">
                                <?php foreach ($data_hak_akses as $key => $value) : ?>
                                    <label class="flex items-center bg-white dark:bg-gray-200 p-5 rounded">
                                        <input name="hak_akses[]" type="checkbox" value="<?= $value['id_role'] ?>" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                                        <p for="<?= $value['id_role'] ?>" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-800"><?= $value['title'] ?></p>
                                    </label>
                                <?php endforeach ?>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end items-center pt-6 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button name="create" type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$result->free_result();
include_once('../template/footer.php') ?>