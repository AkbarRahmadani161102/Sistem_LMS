<?php
include_once('../template/header.php');
!isset($_SESSION['role']) && redirect('../siswa/login.php');
$_SESSION['role'] === 'admin' && redirect('../admin/index.php');
?>


<div class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => "Histori Notifikasi $role", 'filename' => '#']]);
            ?>
            <h4 class="my-7 font-semibold text-gray-800 dark:text-white">History Notifikasi</h4>

            <div class="table__container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Deskripsi</th>
                            <th>Tanggal Dibuat</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $role = $_SESSION['role'];
                        $id_user = $_SESSION['user_id'];
                        $sql = "SELECT * FROM notifikasi_$role WHERE id_$role = '$id_user'";
                        $data_notifikasi = $db->query($sql) or die($db->error);
                        $data_notifikasi->fetch_assoc();

                        foreach ($data_notifikasi as $key => $notifikasi) : ?>
                            <tr>
                                <th><?= $key + 1 ?></th>
                                <td><?= $notifikasi['deskripsi'] ?></td>
                                <td><?= $notifikasi['tgl_dibuat'] ?></td>
                                <td>
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