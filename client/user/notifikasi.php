<?php
include_once('../template/header.php');
!isset($_SESSION['role']) && redirect('../siswa/login.php');
$_SESSION['role'] === 'admin' && redirect('../admin/index.php');
?>


<div id="pengaturan" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => "Histori Notifikasi $role", 'filename' => '#']]);
            ?>
            <h4 class="my-7 font-semibold text-gray-800 dark:text-white">History Notifikasi</h4>

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">#</th>
                            <th scope="col" class="px-6 py-3">Deskripsi</th>
                            <th scope="col" class="px-6 py-3">Tanggal Dibuat</th>
                            <th scope="col" class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $role = $_SESSION['role'];
                        $id_user = $_SESSION['user_id'];
                        $sql = "SELECT * FROM notifikasi_$role WHERE id_$role = '$id_user' AND status = 'Selesai'";
                        $data_notifikasi = $db->query($sql) or die($db->error);
                        $data_notifikasi->fetch_assoc();

                        foreach ($data_notifikasi as $key => $notifikasi) : ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th class="px-6 py-4 text-amber-500"><?= $key + 1 ?></th>
                                <td class="px-6 py-4"><?= $notifikasi['deskripsi'] ?></td>
                                <td class="px-6 py-4"><?= $notifikasi['tgl_dibuat'] ?></td>
                                <td class="px-6 py-4">
                                    <form action="../../api/user/notifikasi.php" method="post">
                                        <button class="btn btn--outline-red group flex w-full" type="submit" name="delete" value="<?= $notifikasi["id_notifikasi_$role"] ?>">
                                            <i class="ri-delete-bin-6-line text-red-500 mx-auto group-hover:text-white"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>