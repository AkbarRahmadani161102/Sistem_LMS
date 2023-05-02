<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access(['Super Admin', 'Admin Akademik']);

$sql = "SELECT m.*, j.id_jenjang id_jenjang, j.nama nama_jenjang, COUNT(dm.id_detail_mapel) count_detail_mapel, COUNT(ja.id_jadwal) count_jadwal FROM mapel m
JOIN jenjang j ON m.id_jenjang = j.id_jenjang
LEFT JOIN detail_mapel dm ON m.id_mapel = dm.id_mapel
LEFT JOIN jadwal ja ON m.id_mapel = ja.id_mapel
GROUP BY m.id_mapel
ORDER BY j.id_jenjang";
$result = $db->query($sql) or die($sql);
$result->fetch_assoc();

$sql = "SELECT * FROM kelas";
$data_kelas = $db->query($sql) or die($db->error);
$data_kelas->fetch_assoc();

$sql = "SELECT * FROM jenjang";
$data_jenjang = $db->query($sql) or die($db->error);
$data_jenjang->fetch_assoc();
?>

<div id="mapel" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php'; ?>

            <div class="flex items-center gap-5">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Data Mapel</h4>

                <button data-modal-target="add_mapel_modal" data-modal-toggle="add_mapel_modal" class="btn" type="button">
                    Tambah Mapel
                </button>
            </div>

            <?php generate_breadcrumb([['title' => 'Mapel', 'filename' => 'mapel.php']]); ?>
            <div class="relative overflow-x-auto mt-5">
                <table class="datatable w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3"></th>
                            <th scope="col" class="px-6 py-3">Mapel</th>
                            <th scope="col" class="px-6 py-3">Jenjang</th>
                            <th scope="col" class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $key => $value) : ?>
                            <?php $delete_able = $value['count_detail_mapel'] === '0' && $value['count_jadwal'] === '0' ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th class="px-6 py-4 text-amber-500"><?= $key + 1 ?></th>
                                <td class="px-6 py-4"><?= $value['nama'] ?></td>
                                <td class="px-6 py-4"><?= $value['nama_jenjang'] ?></td>
                                <td class="px-6 py-4 flex gap-4">
                                    <button type="button" class="btn btn--outline-blue" data-modal-target="edit<?= $value['id_mapel'] ?>" data-modal-toggle="edit<?= $value['id_mapel'] ?>">
                                        <i class="ri-edit-box-line"></i>
                                    </button>

                                    <?php if ($delete_able) : ?>
                                        <button onclick="generateConfirmationDialog('../../api/admin/mapel.php', {delete: '<?= $value['id_mapel'] ?>'})" class="btn btn--outline-red">
                                            <i class="ri-delete-bin-6-line"></i>
                                        </button>
                                    <?php endif ?>
                                </td>
                            </tr>

                            <div id="edit<?= $value['id_mapel'] ?>" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
                                <div class="relative w-full h-full max-w-2xl md:h-auto">
                                    <!-- Modal content -->
                                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                        <!-- Modal header -->
                                        <form action="../../api/admin/mapel.php" method="post">
                                            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                    Mapel
                                                </h3>
                                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="edit<?= $value['id_mapel'] ?>">
                                                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span class="sr-only">Close modal</span>
                                                </button>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="glex flex-col space-y-5 p-5">
                                                <div class="flex flex-col gap-2">
                                                    <label class="text-gray-800 dark:text-white" for="nama_mapel">Nama Mapel</label>
                                                    <input class="input w-full p-2 rounded" id="nama_mapel" name="nama_mapel" value="<?= $value['nama'] ?>" required />
                                                </div>
                                                <div class="flex flex-col gap-2">
                                                    <label class="text-gray-800 dark:text-white" for="jenjang">Jenjang</label>
                                                    <select id="jenjang" name="jenjang" class="input w-full p-2 rounded" required>
                                                        <?php foreach ($data_jenjang as $key => $jenjang) : ?>
                                                            <option value="<?= $jenjang['id_jenjang'] ?>" <?= $jenjang['id_jenjang'] === $value['id_jenjang'] ? 'selected' : '' ?>><?= $jenjang['nama'] ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- Modal footer -->
                                            <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                                                <button type="submit" name="update" value="<?= $value['id_mapel'] ?>" class="btn btn--blue">Ubah</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="add_mapel_modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
    <div class="relative w-full h-full max-w-2xl md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <form action="../../api/admin/mapel.php" method="post">
                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Mapel
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="add_mapel_modal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="glex flex-col space-y-5 p-5">
                    <div class="flex flex-col gap-2">
                        <label class="text-gray-800 dark:text-white" for="nama_mapel">Nama Mapel</label>
                        <input class="input w-full p-2 rounded" id="nama_mapel" name="nama_mapel" required />
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-gray-800 dark:text-white" for="jenjang">Jenjang</label>
                        <select id="jenjang" name="jenjang" class="input w-full p-2 rounded" required>
                            <?php foreach ($data_jenjang as $key => $jenjang) : ?>
                                <option value="<?= $jenjang['id_jenjang'] ?>"><?= $jenjang['nama'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button data-modal-hide="add_mapel_modal" type="submit" name="create" class="btn btn--blue">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$result->free_result();
include_once('../template/footer.php') ?>