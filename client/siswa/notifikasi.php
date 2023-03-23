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
                <a class="text-xl text-slate-800 hover:text-amber-500" href="#">Notifikasi</a>
            </div>

            <div class="flex flex-col mb-5 mt-7 gap-10">
                <div class="w-full flex flex-col justify-start items-start bg-gray-200 p-5 rounded">
                    <div class="flex items-start gap-5">
                        <img src="https://picsum.photos/200/300?random=1" alt="" class="w-12 h-12 rounded-full mt-1 border border-amber-500">
                        <div class="flex flex-col">
                            <h4>Pertemuan besok (Ketua kelas)</h4>
                            <p>Instruktur: Deva vivaldi</p>
                            <p>Waktu: 13.00 - 14.00</p>
                            <div class="flex mt-3 gap-2">
                                <button class="px-3 py-2 rounded bg-blue-300 active:translate-y-2 transition">Accept</button>
                                <button class="px-3 py-2 rounded bg-white hover:bg-slate-400 active:translate-y-2 transition">Deny</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full flex flex justify-between items-center bg-gray-200 p-5 rounded">
                    <div class="flex items-start gap-5">
                        <img src="https://picsum.photos/200/300?random=1" alt="" class="w-12 h-12 rounded-full mt-1 border border-amber-500">
                        <div class="flex flex-col">
                            <h4>Pertemuan besok</h4>
                            <p>Instruktur: Wiwid</p>
                            <p>Waktu: 14.00 - 15.00</p>
                        </div>
                    </div>
                    <h4 class="hover:text-red-500 transition"><i class="ri-close-line"></i></h4>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>