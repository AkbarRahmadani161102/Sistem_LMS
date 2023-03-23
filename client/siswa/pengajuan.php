<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
include_once('../../api/util/db.php');
user_access('siswa');

$id_siswa = $_SESSION['user_id'];
$sql = "SELECT * FROM pengajuan p WHERE p.id_siswa = '$id_siswa'";
$data_tunggakan = $db->query($sql);
?>

<div class="w-full min-h-screen flex">
    <?php include_once './components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <?php include_once './components/dashboard_navbar.php' ?>
        <div class="w-full px-10">

            <div class="flex items-center gap-2">
                <a class="text-xl text-gray-400 hover:text-amber-500" href="#">Home</a>
                <i class="ri-arrow-right-s-line text-gray-400 text-xl"></i>
                <a class="text-xl text-slate-800 hover:text-amber-500" href="#">Pengajuan</a>
            </div>
            <div class="mt-7 flex w-full gap-5">
                <h4 class="font-semibold">Data Pengajuan</h4>
                <button class="px-4 py-2 bg-green-300 rounded" data-modal-target="add-pengajuan-modal" data-modal-toggle="add-pengajuan-modal">Ajukan Pengajuan</button>
            </div>
            <table class="mt-5 w-full text-sm text-left shadow-xl rounded">
                <thead class="text-xs text-gray-700 uppercase bg-white">
                    <tr>
                        <th scope="col" class="px-6 py-3">#</th>
                        <th scope="col" class="px-6 py-3">Judul</th>
                        <th scope="col" class="px-6 py-3">Keterangan</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $data_tunggakan->fetch_assoc()) : ?>
                        <tr class="bg-white">
                            <th class="px-6 py-4 text-amber-500"></th>
                            <td class="px-6 py-4"><?= $row['judul'] ?></td>
                            <td class="px-6 py-4"><?= $row['keterangan'] ?></td>
                            <td class="px-6 py-4"><?= $row['status'] ?></td>
                            <form action="../../api/siswa/pengajuan.php" method="post">
                                <td class="px-6 py-4"><button type="submit" name="delete" value="<?= $row['id_pengajuan'] ?>" class="text-red-500"><i class="ri-delete-bin-line"></i></button></td>
                            </form>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="add-pengajuan-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
    <div class="relative w-full h-full max-w-2xl md:h-auto">
        <div class="relative bg-white rounded-lg shadow">
            <!-- Modal header -->
            <div class="flex items-start justify-between p-4 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    Ajukan Pengajuan
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="add-pengajuan-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <form action="../../api/siswa/pengajuan.php" method="post">
                <!-- Modal body -->
                <div class="p-6 space-y-6">
                    <label class="block" for="judul">Judul</label>
                    <input id="judul" class="border rounded w-full py-1.5 px-2 border-gray-400 mt-1" name="judul" />

                    <label for="keterangan" class="block">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="4" class="border rounded w-full py-1.5 px-2 border-gray-400 mt-1" placeholder="Write your thoughts here..."></textarea>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                    <button data-modal-hide="add-pengajuan-modal" name="create" type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                    <button data-modal-hide="add-pengajuan-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>