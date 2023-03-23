<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
include_once('../../api/util/db.php');
user_access('siswa');

$id_siswa = $_SESSION['user_id'];
$sql = "SELECT * FROM siswa WHERE id_siswa = '$id_siswa' LIMIT 1";
$data_siswa = $db->query($sql)->fetch_assoc();
?>

<div class="w-full min-h-screen flex">
    <?php include_once './components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <?php include_once './components/dashboard_navbar.php' ?>
        <div class="w-full px-10">

            <div class="flex items-center gap-2">
                <a class="text-xl text-gray-400 hover:text-amber-500" href="#">Home</a>
                <i class="ri-arrow-right-s-line text-gray-400 text-xl"></i>
                <a class="text-xl text-slate-800 hover:text-amber-500" href="#">Pengaturan</a>
            </div>

            <div class="flex flex-col mb-5 mt-7 gap-10">
                <div class="bg-gray-100 rounded shadow-lg px-5 py-4">
                    <h5 class="my-5 font-semibold text-slate-800">Ubah Data Profil</h5>
                    <form class="w-full" action="../../api/user/user_settings.php" method="post">
                        <div class="mb-5">
                            <label for="nama" class="form-label text-secondary text-gray-400">Nama Lengkap</label>
                            <input type="text" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="nama" name="nama" value="<?= $data_siswa['nama'] ?>" required>
                        </div>
                        <div class="mb-5">
                            <label for="no_telp" class="form-label text-secondary text-gray-400">Nomor Telepon</label>
                            <input type="text" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="no_telp" name="no_telp" value="<?= $data_siswa['no_telp'] ?>" required>
                        </div>
                        <div class="mb-5">
                            <label for="alamat" class="form-label text-secondary text-gray-400">Alamat</label>
                            <textarea class="border rounded w-full py-1.5 border-gray-400 mt-1" id="alamat" name="alamat" required><?= $data_siswa['alamat'] ?></textarea>
                        </div>
                        <button type="submit" class="w-full bg-green-500 rounded py-1.5 text-white" name="update_profil">Ubah Profil</button>
                    </form>
                </div>

                <div class="bg-gray-100 rounded shadow-lg px-5 py-4">
                    <h5 class="my-5 font-semibold text-slate-800">Ubah Data Kredensial</h5>
                    <form class="w-full" action="../../api/user/user_settings.php" method="post">
                        <div class="mb-5">
                            <label for="email" class="form-label text-secondary text-gray-400">Email</label>
                            <input type="email" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="email" name="email" value="<?= $data_siswa['email'] ?>" required>
                        </div>
                        <div class="mb-5">
                            <label for="password" class="form-label text-secondary text-gray-400">Password</label>
                            <input type="password" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="password" name="password" required>
                        </div>
                        <div class="mb-5">
                            <label for="confirm_password" class="form-label text-secondary text-gray-400">Konfirmasi Password</label>
                            <input type="password" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" class="w-full bg-red-500 rounded py-1.5 text-white" name="update_kredensial">Ubah Kredensial</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>