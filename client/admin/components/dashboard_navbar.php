<?php
include_once '../../api/util/db.php';
$sql = "SELECT * FROM instruktur";
$data_instruktur = $db->query($sql);
?>

<div class="w-full flex p-10">
    <div class="w-full h-fit">
        <form action="">
            <button type="submit"><i class="ri-search-line"></i></button>
            <input type="text" class="ml-auto border-none focus:ring-0 w-11/12" placeholder="Search">
        </form>
    </div>
    <div class="w-full flex items-center justify-end h-fit">
        <div class="flex gap-3 items-center">
            <p id="live-clock" class="m-0 me-3 font-semibold"></p>
            <a href="./notifikasi.php" class="rounded-full px-3 py-1.5 border border-slate-800 text-slate-800 hover:bg-amber-500 hover:border-amber-500 hover:text-white transition active:translate-y-1"><i class="ri-notification-line"></i></a>
        </div>
        <hr class="vr">
        <img class="w-10 h-10 rounded-full border border-amber-500 border" src="https://images.pexels.com/photos/302769/pexels-photo-302769.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="User image">
        <div class="name_role d-flex flex-column ml-3">
            <p class="m-0 text-secondary font-semibold"><?= $_SESSION['nama'] ?></p>
            <p class="m-0 text-secondary">Admin Akademik</p>
        </div>

        <button data-dropdown-toggle="dropdown" class="ml-5" type="button"><i class="ri-arrow-down-s-line text-2xl hover:text-amber-500"></i></button>
        <div id="dropdown" class="z-10 hidden bg-white rounded-lg shadow-lg">
            <ul class="text-sm text-gray-700">
                <a href="./pengaturan.php" class="block px-4 py-2 hover:bg-amber-500 rounded hover:text-white gap-2 flex align-center"><i class="ri-settings-4-line"></i> Pengaturan</a>
                <hr>
                <a href="../../api/auth/logout.php" class="block px-4 py-2 hover:bg-amber-500 rounded hover:text-white gap-2 flex align-center"><i class="ri-door-open-line"></i> Keluar</a>
            </ul>
        </div>
    </div>
</div>

