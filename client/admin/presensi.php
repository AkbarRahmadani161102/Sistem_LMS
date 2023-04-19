<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access(['Super Admin', 'Admin Akademik']);

if (isset($_GET['role']) && $_GET['role'] === 'siswa') {
    $sql = "SELECT *, k.nama nama_kelas, a.status FROM absensi_siswa a JOIN siswa s ON a.id_siswa = s.id_siswa JOIN detail_jadwal dj ON a.id_detail_jadwal = dj.id_detail_jadwal JOIN detail_kelas dk ON s.id_siswa = dk.id_siswa JOIN kelas k ON dk.id_kelas = k.id_kelas GROUP BY tgl_pertemuan, s.id_siswa";
    $data_presensi_siswa = $db->query($sql) or die($sql);
    $data_presensi_siswa->fetch_assoc();
} else {
    $sql = "SELECT * FROM detail_jadwal dj JOIN instruktur i ON dj.id_instruktur = i.id_instruktur WHERE status_kehadiran_instruktur IS NOT NULL";
    $data_presensi_instruktur = $db->query($sql) or die($sql);
    $data_presensi_instruktur->fetch_assoc();
}
?>

<div id="presensi" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php'; ?>

            <div class="flex items-center gap-5">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Presensi <?= isset($_GET['role']) && $_GET['role'] ? $_GET['role'] : '' ?></h4>
            </div>

            <?php generate_breadcrumb([['title' => 'Presensi', 'filename' => 'presensi.php']]); ?>

            <div class="flex flex-col">
                <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400">
                    <li class="mr-2">
                        <a href="?role=instruktur" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800 dark:hover:text-gray-300 <?= isset($_GET['role']) && $_GET['role'] === 'instruktur' ? 'text-blue-500' : '' ?>">Instruktur</a>
                        <a href="?role=siswa" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800 dark:hover:text-gray-300 <?= isset($_GET['role']) && $_GET['role'] === 'siswa' ? 'text-blue-500' : '' ?>">Siswa</a>
                    </li>
                </ul>
            </div>
            <div class="relative overflow-x-auto mt-5">
                <table class="datatable w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <?php if (isset($_GET['role']) && $_GET['role'] === 'siswa') : ?>
                            <tr>
                                <th scope="col" class="px-6 py-3"></th>
                                <th scope="col" class="px-6 py-3">Tanggal</th>
                                <th scope="col" class="px-6 py-3">Nama</th>
                                <th scope="col" class="px-6 py-3">Kelas</th>
                                <th scope="col" class="px-6 py-3">Status Kehadiran</th>
                            </tr>
                        <?php else : ?>
                            <tr>
                                <th scope="col" class="px-6 py-3"></th>
                                <th scope="col" class="px-6 py-3">Tanggal</th>
                                <th scope="col" class="px-6 py-3">Nama</th>
                                <th scope="col" class="px-6 py-3">Status Kehadiran</th>
                            </tr>
                        <?php endif ?>
                    </thead>
                    <tbody>
                        <?php if (isset($_GET['role']) && $_GET['role'] === 'siswa') : ?>
                            <?php foreach ($data_presensi_siswa as $key => $value) : ?>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th class="px-6 py-4 text-amber-500"><?= $key + 1 ?></th>
                                    <td class="px-6 py-4"><?= $value['tgl_pertemuan'] ?></td>
                                    <td class="px-6 py-4"><?= $value['nama'] ?></td>
                                    <td class="px-6 py-4"><?= $value['nama_kelas'] ?></td>
                                    <td class="px-6 py-4">
                                        <?php if ($value['status'] === 'H') : ?>
                                            <p class="text-green-500">Hadir</p>
                                        <?php endif ?>
                                        <?php if ($value['status'] === 'I') : ?>
                                            <p class="text-amber-500">Izin</p>
                                        <?php endif ?>
                                        <?php if ($value['status'] === 'T') : ?>
                                            <p class="text-red-500">Tidak Ada Keterangan</p>
                                        <?php endif ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>

                        <?php else : ?>
                            <?php foreach ($data_presensi_instruktur as $key => $value) : ?>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th class="px-6 py-4 text-amber-500"><?= $key + 1 ?></th>
                                    <td class="px-6 py-4"><?= $value['tgl_pertemuan'] ?></td>
                                    <td class="px-6 py-4"><?= $value['nama'] ?></td>
                                    <td class="px-6 py-4"><?= $value['status_kehadiran_instruktur'] ?></td>
                                </tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<div id="add_presensi_modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
    <div class="relative w-full h-full max-w-2xl md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <form action="../../api/admin/presensi.php" method="post">
                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Tambah presensi
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="add_presensi_modal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6">
                    <input type="text" name="title" class="w-full p-3 rounded">
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button dtype="submit" name="create" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Ubah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>