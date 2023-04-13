<?php
include_once('../template/header.php');
!isset($_SESSION['role']) && redirect('../siswa/login.php');

$role = $_SESSION['role'];
$id_user = $_SESSION['user_id'];
$sql = "SELECT * FROM $role WHERE id_$role = '$id_user' LIMIT 1";
$data_user = $db->query($sql)->fetch_assoc();
?>

<div id="pengaturan" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => "Pengaturan $role", 'filename' => '#']]);
            ?>
            <?php if (!isset($_GET['edit'])) : ?>
                <div class="flex flex-col mt-5 ">
                    <div class="flex flex-col bg-white dark:bg-gray-800 rounded-lg text-gray-800 dark:text-white">
                        <div class="h-40 bg-gradient-to-r from-blue-500 relative rounded-t-lg">
                            <img class="w-40 h-40 rounded-full absolute top-2/4 inset-x-1/4 sm:left-16 sm:right-0 border-4 border-white" src="../assets/image/example.png" alt="User Image">
                        </div>
                        <div class="flex flex-col min-h-full mt-28 p-0 lg:pl-16 space-y-5">

                            <div class="flex flex-1 justify-between">
                                <h3 class="font-semibold"><?= $data_user['nama'] ?></h3>
                                <a class="px-3 py-1 rounded-full flex items-center gap-2 lg:bg-gray-800 lg:text-white lg:hover:bg-blue-500 hover:text-white transition" href="?edit"> Edit <i class="ri-edit-box-line"></i></a>
                            </div>

                            <div class="pt-5 flex flex-1 gap-11 flex-col lg:flex-row">
                                <?php if ($_SESSION['role'] === 'siswa') : ?>
                                    <div class="flex flex-1 flex-col p-5 rounded-[1rem] border gap-9">
                                        <h5 class="font-semibold mb-3">Informasi Personal</h5>
                                        <div class="flex flex-1 gap-5 lg:gap-36">
                                            <div class="flex flex-col gap-2">
                                                <label class="text-lg text-gray-500 dark:text-white">Nama Lengkap</label>
                                                <p class="font-semibold"><?= $data_user['nama'] ?></p>
                                            </div>
                                            <div class="flex flex-col gap-2">
                                                <label class="text-lg text-gray-500 dark:text-white">Kontak</label>
                                                <p class="font-semibold"><?= $data_user['no_telp'] ?></p>
                                            </div>
                                        </div>
                                        <div class="flex flex-1 gap-5 lg:gap-36">
                                            <div class="flex flex-col gap-2">
                                                <label class="text-lg text-gray-500 dark:text-white">Email</label>
                                                <p class="font-semibold"><?= $data_user['email'] ?></p>
                                            </div>
                                        </div>
                                        <div class="flex flex-1 gap-5 lg:gap-36">
                                            <div class="flex flex-col gap-2">
                                                <label class="text-lg text-gray-500 dark:text-white">Alamat</label>
                                                <p class="font-semibold"><?= $data_user['alamat'] ?></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-1 flex-col p-5 rounded-[1rem] border gap-9">
                                        <h5 class="font-semibold mb-3">Informasi Akademik</h5>
                                        <div class="flex flex-1 gap-5 lg:gap-36 flex-wrap">
                                            <div class="flex flex-col gap-2">
                                                <label class="text-lg text-gray-500 dark:text-white">Jenjang</label>
                                                <p class="font-semibold">SMA</p>
                                            </div>
                                            <div class="flex flex-col gap-2">
                                                <label class="text-lg text-gray-500 dark:text-white">Kelas</label>
                                                <p class="font-semibold">2A</p>
                                            </div>
                                            <div class="flex flex-col gap-2">
                                                <label class="text-lg text-gray-500 dark:text-white">Ketua Kelas</label>
                                                <p class="font-semibold">Peni Yudianto</p>
                                            </div>
                                        </div>
                                        <div class="flex flex-1 gap-5 lg:gap-36 flex-wrap">
                                            <div class="flex flex-col gap-2">
                                                <label class="text-lg text-gray-500 dark:text-white">Tanggal Pendaftaran</label>
                                                <p class="font-semibold"><?= $data_user['tgl_dibuat'] ?></p>
                                            </div>
                                            <div class="flex flex-col gap-2">
                                                <label class="text-lg text-gray-500 dark:text-white">Status Keaktifan</label>
                                                <p class="font-semibold  <?= $data_user['status'] ? 'text-green-500' : 'text-red-500'?>"><?= $data_user['status'] ? $data_user['status'] : 'Nonaktif' ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div class="flex flex-1 flex-col p-5 rounded-[1rem] border gap-9">
                                        <h5 class="font-semibold mb-3">Informasi Personal</h5>
                                        <div class="flex flex-1 gap-5 lg:gap-36 flex-col md:flex-row">
                                            <div class="flex flex-col gap-2">
                                                <label class="text-lg text-gray-500 dark:text-white">Nama Lengkap</label>
                                                <p class="font-semibold"><?= $data_user['nama'] ?></p>
                                            </div>
                                            <div class="flex flex-col gap-2">
                                                <label class="text-lg text-gray-500 dark:text-white">Email</label>
                                                <p class="font-semibold"><?= $data_user['email'] ?></p>
                                            </div>
                                            <div class="flex flex-col gap-2">
                                                <label class="text-lg text-gray-500 dark:text-white">Kontak</label>
                                                <p class="font-semibold"><?= $data_user['no_telp'] ?></p>
                                            </div>
                                        </div>
                                        <div class="flex flex-1 gap-5 lg:gap-36">
                                            <div class="flex flex-col gap-2">
                                                <label class="text-lg text-gray-500 dark:text-white">Alamat</label>
                                                <p class="font-semibold"><?= $data_user['alamat'] ?></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-col p-5 rounded-[1rem] border gap-9 w-fit">
                                        <h5 class="font-semibold mb-3">Informasi Administrasi</h5>
                                        <div class="flex flex-col gap-5 flex-wrap h-full justify-around">
                                            <div class="flex flex-col gap-2">
                                                <label class="text-lg text-gray-500 dark:text-white">Tanggal Pendaftaran</label>
                                                <p class="font-semibold"><?= $data_user['tgl_dibuat'] ?></p>
                                            </div>
                                            <div class="flex flex-col gap-2">
                                                <label class="text-lg text-gray-500 dark:text-white">Status Keaktifan</label>
                                                <p class="font-semibold text-green-500">Aktif</p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <div class="mt-5 flex flex-col lg:flex-row gap-5 p-3 lg:p-20 bg-white dark:bg-gray-700 rounded">
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg shadow-lg px-5 py-4 flex flex-1 flex-col justify-between">
                        <h5 class="my-3 font-semibold text-slate-800 dark:text-white">Ubah Data Profil</h5>
                        <form class="form" action="../../api/user/user_settings.php" method="post">
                            <div class="mb-5 space-y-2">
                                <label for="nama" class="form-label text-secondary text-gray-400">Nama Lengkap</label>
                                <input type="text" class="input" id="nama" name="nama" value="<?= $data_user['nama'] ?>" maxlength="50" required>
                            </div>
                            <div class="mb-5 space-y-2">
                                <label for="no_telp" class="form-label text-secondary text-gray-400">Nomor Telepon</label>
                                <input type="text" class="input" id="no_telp" name="no_telp" value="<?= $data_user['no_telp'] ?>" maxlength="14" required>
                            </div>
                            <div class="mb-5 space-y-2">
                                <label for="alamat" class="form-label text-secondary text-gray-400">Alamat</label>
                                <textarea class="input resize-none" id="alamat" name="alamat" required maxlength="40"><?= $data_user['alamat'] ?></textarea>
                            </div>
                            <button type="submit" class="btn w-full bg-green-500 dark:bg-green-500 text-white dark:text-white" name="update_profil">Ubah Profil</button>
                        </form>
                    </div>

                    <hr class="hidden lg:block rotate-180 bg-amber-500 h-40 w-1 mt-28 rounded-lg">

                    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg shadow-lg px-5 py-4 flex flex-1 flex-col justify-between">
                        <h5 class="my-3 font-semibold text-slate-800 dark:text-white">Ubah Data Kredensial</h5>
                        <form class="form" action="../../api/user/user_settings.php" method="post">
                            <div class="mb-5 space-y-2">
                                <label for="email" class="form-label text-secondary text-gray-400">Email</label>
                                <input type="email" class="input" id="email" name="email" value="<?= $data_user['email'] ?>" maxlength="30" required>
                            </div>
                            <div class="mb-5 space-y-2">
                                <label for="password" class="form-label text-secondary text-gray-400">Password</label>
                                <input type="password" class="input" id="password" name="password" maxlength="50" required>
                            </div>
                            <div class="mb-5 space-y-2">
                                <label for="confirm_password" class="form-label text-secondary text-gray-400">Konfirmasi Password</label>
                                <input type="password" class="input" id="confirm_password" name="confirm_password" maxlength="50" required>
                            </div>
                            <button type="submit" class="btn w-full bg-red-500 dark:bg-red-500 text-white dark:text-white" name="update_kredensial">Ubah Kredensial</button>
                        </form>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>