<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('admin');
?>

<div class="w-full min-h-screen flex">
    <?php include_once './components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <?php include_once './components/dashboard_navbar.php' ?>
        <div class="w-full px-10">

            <div class="flex items-center gap-2">
                <a class="text-xl text-gray-400 hover:text-amber-500 transition" href="#">Home</a>
                <i class="ri-arrow-right-s-line text-gray-400 text-xl"></i>
                <a class="text-xl text-slate-800 hover:text-amber-500 transition" href="#">Dashboard</a>
            </div>

            <div class="flex flex-col gap-4">
                <div class="flex w-full mt-7 gap-4 justify-between">
                    <div class="flex w-fit flex-col bg-stone-100 p-3 px-4 rounded shadow-lg gap-2 justify-between">
                        <div class="flex items-center gap-2 font-semibold">
                            <div class="w-5 h-5 bg-amber-500 rounded-full">&nbsp;</div>
                            <h6>Update</h6>
                        </div>
                        <p>Pendapatan meningkat <span class="text-amber-500">20%</span> dalam 1 bulan</p>
                        <a class="text-stone-400 flex items-center group hover:text-amber-500 transition hover:gap-2" href="#">Lihat hasil analisis <i class="ri-arrow-right-s-line"></i></a>
                    </div>
                    <div class="flex w-fit flex-col bg-stone-100 p-3 px-4 rounded shadow-lg gap-2 justify-between">
                        <div class="flex items-center gap-2 font-semibold">
                            <h6>Rata-rata pendapatan instruktur</h6>
                        </div>
                        <h5 class="font-semibold">Rp. <span>500.000</span></h5>
                        <p class="flex items-center gap-2"><span class="text-amber-500 flex items-center gap-2"><i class="ri-line-chart-line text-2xl"></i> 5%</span> Dibandingkan bulan lalu</p>
                    </div>
                    <div class="flex w-fit flex-col bg-stone-100 p-3 px-4 rounded shadow-lg gap-2 justify-between">
                        <div class="flex items-center gap-2 font-semibold">
                            <h6>Peningkatan peserta didik</h6>
                        </div>
                        <h5 class="font-semibold"><span>12</span> Siswa</h5>
                        <p class="flex items-center gap-2"><span class="text-amber-500 flex items-center gap-2"><i class="ri-line-chart-line text-2xl"></i> 20%</span> Dibandingkan bulan lalu</p>
                    </div>
                    <div class="flex w-fit flex-col bg-stone-100 p-3 px-4 rounded shadow-lg gap-2 justify-between">
                        <div class="flex items-center gap-2 font-semibold">
                            <h6>Peningkatan tenaga pengajar</h6>
                        </div>
                        <h5 class="font-semibold"><span>5</span> Instruktur</h5>
                        <p class="flex items-center gap-2"><span class="text-amber-500 flex items-center gap-2"><i class="ri-line-chart-line text-2xl"></i> 20%</span> Dibandingkan bulan lalu</p>
                    </div>
                </div>

                <div class="flex w-full justify-between gap-4">

                    <div class="flex w-fit flex-col bg-stone-100 p-3 px-4 rounded shadow-lg gap-2 justify-between gap-4">
                        <a href="pengajuan.php" class="text-lg font-semibold">Pengajuan Siswa</a>
                        <div class="flex w-fit gap-3 items-center">
                            <div class="bg-gradient-to-r from-blue-500 to-green-200 w-14 h-12 rounded-full">
                                &nbsp;
                            </div>
                            <div class="flex w-full flex-col gap-2">
                                <div class="flex justify-between gap-5">
                                    <a href="pengajuan.php?id_pengajuan=321321412" class="font-semibold">Adit</a>
                                    <p class="text-amber-500 font-semibold">Selesai</p>
                                </div>
                                <div class="flex justify-between text-neutral-500 gap-5">
                                    <p>Pengajuan kelas privat</p>
                                    <p>20 Maret 2022</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex w-fit gap-3 items-center">
                            <div class="bg-gradient-to-r from-blue-500 to-green-200 w-14 h-12 rounded-full">
                                &nbsp;
                            </div>
                            <div class="flex w-full flex-col gap-2">
                                <div class="flex justify-between gap-5">
                                    <a href="pengajuan.php?id_pengajuan=321321412" class="font-semibold">Adit</a>
                                    <p class="text-amber-500 font-semibold">Selesai</p>
                                </div>
                                <div class="flex justify-between text-neutral-500 gap-5">
                                    <p>Pengajuan kelas privat</p>
                                    <p>20 Maret 2022</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex w-fit gap-3 items-center">
                            <div class="bg-gradient-to-r from-blue-500 to-green-200 w-14 h-12 rounded-full">
                                &nbsp;
                            </div>
                            <div class="flex w-full flex-col gap-2">
                                <div class="flex justify-between gap-5">
                                    <a href="pengajuan.php?id_pengajuan=321321412" class="font-semibold">Adit</a>
                                    <p class="text-amber-500 font-semibold">Selesai</p>
                                </div>
                                <div class="flex justify-between text-neutral-500 gap-5">
                                    <p>Pengajuan kelas privat</p>
                                    <p>20 Maret 2022</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex col">
                        <div class="flex w-fit flex-col bg-stone-100 p-3 px-4 rounded shadow-lg gap-4">
                            <a href="pengajuan.php" class="text-lg font-semibold">Ketersediaan Kelas</a>

                            <div class="flex flex-col gap-3">
                                <div class="flex justify-between gap-10">
                                    <p>Ruang 1</p>
                                    <p class="text-amber-500 font-semibold">Tersedia</p>
                                </div>
                                <div class="flex justify-between gap-10">
                                    <p>Ruang 2</p>
                                    <p>Sedang Digunakan</p>
                                </div>
                                <div class="flex justify-between gap-10">
                                    <p>Ruang 3</p>
                                    <p>Sedang Digunakan</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex col">
                        <div class="flex w-fit flex-col bg-stone-100 rounded shadow-lg px-5 items-center justify-center gap-1 font-semibold text-center">
                            <h6>Jumlah Admin</h6>
                            <h6 class="text-amber-500">2 Staff</h6>
                        </div>
                    </div>
                    <div class="flex flex col">
                        <div class="flex w-fit flex-col bg-stone-100 rounded shadow-lg px-5 items-center justify-center gap-1 font-semibold text-center">
                            <h6>Jumlah Instruktur</h6>
                            <h6 class="text-amber-500">16 Instruktur</h6>
                        </div>
                    </div>
                    <div class="flex flex col">
                        <div class="flex w-fit flex-col bg-stone-100 rounded shadow-lg px-5 items-center justify-center gap-4 font-semibold text-center">
                            <div class="flex flex-col">
                                <h6>Jumlah Siswa</h6>
                                <h6 class="text-amber-500">96 Siswa</h6>
                            </div>
                            <div class="flex gap-2">
                                <div class="flex flex-col w-full">
                                    <h6>SD</h6>
                                    <h6 class="text-amber-500">12</h6>
                                </div>
                                <div class="flex flex-col w-full">
                                    <h6>SMP</h6>
                                    <h6 class="text-amber-500">42</h6>
                                </div>
                                <div class="flex flex-col w-full">
                                    <h6>SMA</h6>
                                    <h6 class="text-amber-500">42</h6>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>