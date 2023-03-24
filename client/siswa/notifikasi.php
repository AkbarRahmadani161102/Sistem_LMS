<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('siswa');
?>

<div class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Notifikasi', 'filename' => 'notifikasi.php']]);
            ?>

            <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Notifikasi</h4>

            <div class="relative overflow-x-auto">

            </div>

        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>