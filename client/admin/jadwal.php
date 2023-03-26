<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('Super Admin');

$sql = "SELECT j.id_jadwal id_jadwal, k.nama nama_kelas, m.nama nama_mapel, k.status, s.nama nama_ketua_kelas, jg.nama nama_jenjang, j.hari, j.jam_mulai, j.jam_selesai FROM jadwal j, mapel m, kelas k, jenjang jg, siswa s WHERE j.id_mapel = m.id_mapel AND k.id_jenjang = jg.id_jenjang AND k.id_ketua_kelas = s.id_siswa ";
if(isset($_GET['tab'])) {
    $nama_jenjang = $_GET['tab'];
    $sql . " AND nama_jenjang = '$nama_jenjang'";
    $jadwal = $db->query($sql) or die($db->error);
} else {
    $jadwal = $db->query($sql) or die($db->error);
}
$jadwal->fetch_assoc();

$sql = "SELECT * FROM kelas";
$kelas = $db->query($sql) or die($db->error);
$kelas->fetch_assoc();

$sql = "SELECT m.*, j.nama nama_jenjang FROM mapel m, jenjang j WHERE m.id_jenjang = j.id_jenjang";
$mapel = $db->query($sql) or die($db->error);
$mapel->fetch_assoc();

$sql = "SELECT * FROM siswa";
$siswa = $db->query($sql) or die($db->error);
$siswa->fetch_assoc();

$hari = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];
?>

<div id="anggota_kelas" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php'; ?>

            <div class="flex items-center gap-5">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Jadwal</h4>

                <button data-modal-target="add_role_modal" data-modal-toggle="add_role_modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                    Tambah Jadwal
                </button>
            </div>

            <?php generate_breadcrumb([['title' => 'Jadwal', 'filename' => 'jadwal.php']]); ?>

            <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400">
                <li class="mr-2">
                    <a href="?tab=SD" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800 dark:hover:text-gray-300 <?= isset($_GET['tab']) && $_GET['tab'] === "SD" ? 'text-blue-500' : '' ?>">SD</a>
                </li>
                <li class="mr-2">
                    <a href="?tab=SMP" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800 dark:hover:text-gray-300 <?= isset($_GET['tab']) && $_GET['tab'] === "SMP" ? 'text-blue-500' : '' ?>">SMP</a>
                </li>
                <li class="mr-2">
                    <a href="?tab=SMA" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800 dark:hover:text-gray-300 <?= isset($_GET['tab']) && $_GET['tab'] === "SMA" ? 'text-blue-500' : '' ?>">SMA</a>
                </li>
            </ul>

            <div class="relative overflow-x-auto mt-5">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">#</th>
                            <th scope="col" class="px-6 py-3">Nama Kelas</th>
                            <th scope="col" class="px-6 py-3">Nama Mapel</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Nama Ketua Kelas</th>
                            <th scope="col" class="px-6 py-3">Nama Jenjang</th>
                            <th scope="col" class="px-6 py-3">Hari</th>
                            <th scope="col" class="px-6 py-3">Jam Mulai</th>
                            <th scope="col" class="px-6 py-3">Jam Selesai</th>
                            <th scope="col" class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jadwal as $key => $value) : ?>
                            <tr class="border-b dark:bg-gray-800 dark:border-gray-700 bg-transparent hover:bg-gray-100 dark:hover:bg-gray-700">
                                <th class="px-6 py-4 text-amber-500"><?= $key + 1 ?></th>
                                <td class="px-6 py-4"><?= $value['nama_kelas'] ?></td>
                                <td class="px-6 py-4"><?= $value['nama_mapel'] ?></td>
                                <td class="px-6 py-4"><?= $value['status'] ?></td>
                                <td class="px-6 py-4"><?= $value['nama_ketua_kelas'] ?></td>
                                <td class="px-6 py-4"><?= $value['nama_jenjang'] ?></td>
                                <td class="px-6 py-4"><?= $value['hari'] ?></td>
                                <td class="px-6 py-4"><?= $value['jam_mulai'] ?></td>
                                <td class="px-6 py-4"><?= $value['jam_selesai'] ?></td>
                                <td class="px-6 py-4 flex gap-4">
                                    <a href="?edit=<?= $value['id_jadwal'] ?>" type="button" class="px-5 py-2 border border-blue-500 rounded group hover:bg-blue-500">
                                        <i class="ri-edit-box-line text-blue-500 text-base group-hover:text-white"></i>
                                    </a>
                                    <form action="../../api/admin/jadwal.php" method="post">
                                        <button type="submit" class="px-5 py-2 border border-red-500 rounded group hover:bg-red-500" name="delete">
                                            <i class="ri-delete-bin-6-line text-red-500 text-base group-hover:text-white"></i>
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

<div id="add_role_modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
    <div class="relative w-full h-full max-w-2xl md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <form action="../../api/admin/jadwal.php" method="post">
                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Tambah Role
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="defaultModal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6">
                    <div class="flex flex-col mb-5 gap-2">
                        <label for="kelas" class="text-gray-800 dark:text-white">Kelas</label>
                        <select name="kelas" id="kelas" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <?php foreach ($kelas as $key => $value) : ?>
                                <option value="<?= $value['id_kelas'] ?>"><?= $value['nama'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="flex flex-col mb-5 gap-2">
                        <label for="mapel" class="text-gray-800 dark:text-white">Mapel</label>
                        <select name="mapel" id="mapel" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <?php foreach ($mapel as $key => $value) : ?>
                                <option value="<?= $value['id_mapel'] ?>"><?= $value['nama_jenjang'] ?> <?= $value['nama'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="flex flex-col mb-5 gap-2">
                        <label for="nama_kelas" class="text-gray-800 dark:text-white">Hari</label>
                        <select name="hari" id="hari" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <?php foreach ($hari as $value) : ?>
                                <option value="<?= $value ?>"><?= $value ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="flex flex-col mb-5 gap-2">
                        <label for="jam_mulai" class="text-gray-800 dark:text-white">Jam Mulai</label>
                        <select name="jam_mulai" id="jam_mulai" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="14:30:00">14:30:00</option>
                            <option value="15:30:00">15:30:00</option>
                            <option value="16:30:00">16:30:00</option>
                            <option value="17:30:00">17:30:00</option>
                        </select>
                    </div>
                    <div class="flex flex-col mb-5 gap-2">
                        <label for="jam_selesai" class="text-gray-800 dark:text-white">Jam Selesai</label>
                        <select name="jam_selesai" id="jam_selesai" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="14:30:00">14:30:00</option>
                            <option value="15:30:00">15:30:00</option>
                            <option value="16:30:00">16:30:00</option>
                            <option value="17:30:00">17:30:00</option>
                        </select>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button data-modal-hide="defaultModal" type="submit" name="create" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$jadwal->free_result();
include_once('../template/footer.php') ?>