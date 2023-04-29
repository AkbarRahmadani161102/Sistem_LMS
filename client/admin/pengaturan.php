<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('admin');
?>

<div id="pengaturan" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Pengaturan', 'filename' => 'pengaturan.php']]);

            $id_admin = $_SESSION['user_id'];
            $sql = "SELECT * FROM admin WHERE id_admin = '$id_admin' LIMIT 1";
            $data_admin = $db->query($sql);
            $data_admin = $data_admin->fetch_assoc();
            ?>

            <div class="flex justify-around mt-16 w-full flex-col md:flex-row gap-5">
                <div class="bg-gray-100 dark:bg-gray-800 rounded shadow-lg px-5 py-4 flex flex-col justify-between">
                    <h5 class="my-5 font-semibold text-slate-800 dark:text-white">Ubah Data Profil</h5>
                    <form class="w-full" action="../../api/user/user_settings.php" method="post">
                        <div class="mb-5">
                            <label for="nama" class="form-label text-secondary text-gray-400">Nama Lengkap</label>

                            <input type="text" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="nama" name="nama" value="<?= $data_admin['nama'] ?>" maxlength="50" required>
                        </div>
                        <div class="mb-5">
                            <label for="no_telp" class="form-label text-secondary text-gray-400">Nomor Telepon</label>
                            <input type="text" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="no_telp" name="no_telp" value="<?= $data_admin['no_telp'] ?>" maxlength="14" required>
                        </div>
                        <div class="mb-5">
                            <label for="alamat" class="form-label text-secondary text-gray-400">Alamat</label>
                            <textarea class="border rounded w-full py-1.5 border-gray-400 mt-1 resize-none" id="alamat" name="alamat" required maxlength="40"><?= $data_admin['alamat'] ?></textarea>
                        </div>
                        <button type="submit" class="w-full bg-green-500 rounded py-1.5 text-white" name="update_profil">Ubah Profil</button>
                    </form>
                </div>

                <div class="bg-gray-100 dark:bg-gray-800 rounded shadow-lg px-5 py-4 flex flex-col justify-between">
                    <h5 class="my-5 font-semibold text-slate-800 dark:text-white">Ubah Data Kredensial</h5>
                    <form class="w-full" action="../../api/user/user_settings.php" method="post">
                        <div class="mb-5">
                            <label for="email" class="form-label text-secondary text-gray-400">Email</label>
                            <input type="email" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="email" name="email" value="<?= $data_admin['email'] ?>" maxlength="30" required>
                        </div>
                        <div class="mb-5">
                            <label for="password" class="form-label text-secondary text-gray-400">Password</label>
                            <input type="password" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="password" name="password" maxlength="50" required>
                        </div>
                        <div class="mb-5">
                            <label for="confirm_password" class="form-label text-secondary text-gray-400">Konfirmasi Password</label>
                            <input type="password" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="confirm_password" name="confirm_password" maxlength="50" required>
                        </div>
                        <button type="submit" class="w-full bg-red-500 rounded py-1.5 text-white" name="update_kredensial">Ubah Kredensial</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>