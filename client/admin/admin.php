<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('Super Admin');

if (isset($_GET['edit'])) {
    $id_admin = $_GET['edit'];
    $sql = "SELECT * FROM admin WHERE id_admin = '$id_admin'";
    $result = $db->query($sql) or die($db);
    $result->fetch_assoc();
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

            <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Staff Administrasi</h4>

            <?php if (isset($_GET['edit'])) : ?>
                <?php generate_breadcrumb([['title' => 'Staff Administrasi', 'filename' => 'admin.php'], ['title' => 'Edit Staff Administrasi', 'filename' => '#']]); ?>

                <?php foreach ($result as $key => $admin_data) : ?>
                    <div class="flex gap-5 mt-5">
                        <form class="flex-1" action="../../api/admin/admin.php" method="post">
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

                        <hr>

                        <form class="flex-1" action="../../api/admin/admin.php" method="post">
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
                <?php endforeach ?>
            <?php else : ?>
                <?php generate_breadcrumb([['title' => 'Staff Administrasi', 'filename' => 'admin.php']]); ?>
                <div class="relative overflow-x-auto mt-5">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3"></th>
                                <th scope="col" class="px-6 py-3">Nama</th>
                                <th scope="col" class="px-6 py-3">No.Telp</th>
                                <th scope="col" class="px-6 py-3">Alamat</th>
                                <th scope="col" class="px-6 py-3">Email</th>
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
                                        $from = new DateTime($value['tgl_dibuat']);
                                        $to = new DateTime("now");
                                        $range = $from->diff($to);
                                        echo $range->format('%R%a days/%R%y years')
                                        ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="?edit=<?= $value['id_admin'] ?>">
                                            Edit
                                        </a>
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

<?php
$result->free_result();
include_once('../template/footer.php') ?>