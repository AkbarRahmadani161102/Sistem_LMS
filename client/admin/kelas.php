<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access(['Super Admin', 'Admin Akademik']);
$sql = "SELECT * FROM jenjang";
$data_jenjang = $db->query($sql) or die($db->error);
$data_jenjang->fetch_assoc();

if (isset($_GET['edit'])) {
    $id_kelas = $_GET['edit'];
    $sql = "SELECT k.*, j.nama nama_jenjang FROM kelas k, jenjang j WHERE k.id_jenjang = j.id_jenjang AND k.id_kelas = $id_kelas";
    $data_kelas = $db->query($sql) or die($db->error);
    $data_kelas = $data_kelas->fetch_assoc();
    $nama_kelas = $data_kelas['nama'];

    $sql = "SELECT s.* FROM siswa s, detail_kelas dk WHERE dk.id_siswa = s.id_siswa AND dk.id_kelas = '$id_kelas'";
    $data_siswa = $db->query($sql) or die($db->error);
    $data_siswa->fetch_assoc();
} else {
    $sql = "
    SELECT k.*, j.nama jenjang, s.nama ketua_kelas, COUNT(*) jumlah_siswa FROM kelas k
    LEFT JOIN detail_kelas dk ON k.id_kelas = dk.id_kelas 
    LEFT JOIN jenjang j ON k.id_jenjang = j.id_jenjang
    LEFT JOIN siswa s ON k.id_ketua_kelas = s.id_siswa 
    GROUP BY k.id_kelas ORDER BY ketua_kelas";
    $result = $db->query($sql) or die($sql);
    $result->fetch_assoc();
}
?>

<div id="anggota_kelas" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php'; ?>
            <div class="flex items-center gap-5">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Data Kelas</h4>
                <?php if (!isset($_GET['edit'])) : ?>
                    <button data-modal-target="add_kelas_modal" data-modal-toggle="add_kelas_modal" class="btn" type="button">
                        Tambah kelas
                    </button>
                <?php endif ?>
            </div>
            <?php if (isset($_GET['edit'])) : ?>
                <?php generate_breadcrumb([['title' => 'Kelas', 'filename' => 'kelas.php'], ['title' => "Edit Kelas $nama_kelas", 'filename' => 'kelas.php']]); ?>
                <form action="../../api/admin/kelas.php" method="post" class="bg-gray-700 rounded p-8 mt-8 flex flex-col gap-5">
                    <div class="flex gap-5 flex-wrap">
                        <div class="flex-1 flex-col space-y-2">
                            <div class="flex flex-col gap-1">
                                <label class="text-gray-800 dark:text-white" for="nama_kelas">Nama Kelas</label>
                                <input id="nama_kelas" name="nama_kelas" type="text" class="rounded" value="<?= $nama_kelas ?>">
                            </div>
                            <div class="flex flex-col gap-1">
                                <label class="text-gray-800 dark:text-white" for="ketua_kelas">Ketua Kelas</label>
                                <select name="ketua_kelas" id="ketua_kelas" class="rounded">
                                    <?php foreach ($data_siswa as $key => $siswa) : ?>
                                        <option value="<?= $siswa['id_siswa'] ?>"><?= $siswa['nama'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="flex flex-1 gap-3">
                                <div class="flex flex-1 flex-col gap-1">
                                    <label class="text-gray-800 dark:text-white" for="status_kelas">Status Kelas</label>
                                    <select name="status_kelas" id="status_kelas" class="rounded">
                                        <option value="Reguler" <?= $data_kelas['status'] === 'Reguler' ? 'selected' : '' ?>>Reguler</option>
                                        <option value="Privat" <?= $data_kelas['status'] === 'Privat' ? 'selected' : '' ?>>Privat</option>
                                    </select>
                                </div>
                                <div class="flex flex-1 flex-col gap-1">
                                    <label class="text-gray-800 dark:text-white" for="jenjang">Jenjang</label>
                                    <select name="jenjang" id="jenjang" class="rounded">
                                        <?php foreach ($data_jenjang as $key => $jenjang) : ?>
                                            <option value="<?= $jenjang['id_jenjang'] ?>" <?= $data_kelas['nama_jenjang'] === $jenjang['nama'] ? 'selected' : '' ?>><?= $jenjang['nama'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="w-fit text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" name="update" value="<?= $id_kelas ?>">Update</button>
                </form>
            <?php else : ?>
                <?php generate_breadcrumb([['title' => 'Kelas', 'filename' => 'kelas.php']]); ?>
                <div class="relative overflow-x-auto overflow-y-hidden mt-5">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3"></th>
                                <th scope="col" class="px-6 py-3">Kelas</th>
                                <th scope="col" class="px-6 py-3">Jenjang</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Ketua Kelas</th>
                                <th scope="col" class="px-6 py-3">Jumlah Siswa</th>
                                <th scope="col" class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($result as $key => $value) : ?>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th class="px-6 py-4 text-amber-500"><?= $key + 1 ?></th>
                                    <td class="px-6 py-4"><?= $value['nama'] ?></td>
                                    <td class="px-6 py-4"><?= $value['jenjang'] ?></td>
                                    <td class="px-6 py-4"><?= $value['status'] ?></td>
                                    <td class="px-6 py-4"><?= $value['ketua_kelas'] ?></td>
                                    <td class="px-6 py-4">
                                        <?php if ($value['jumlah_siswa'] <= 1) : ?>
                                            <p class="text-green-500">New</p>
                                        <?php else : ?>
                                            <div class="flex gap-1">
                                                <?= $value['jumlah_siswa'] ?>
                                                <button data-popover-target="data_anggota_kelas<?= $key ?>" data-popover-placement="right" type="button" class="text-white"><i class="ri-question-line"></i></button>
                                                <div data-popover id="data_anggota_kelas<?= $key ?>" role="tooltip" class="absolute z-10 invisible inline-block w-64 text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800">
                                                    <div class="px-3 py-2 bg-gray-100 border-b border-gray-200 rounded-t-lg dark:border-gray-600 dark:bg-gray-700">
                                                        <h5 class="font-semibold text-gray-900 dark:text-white">Anggota Kelas</h5>
                                                    </div>
                                                    <div class="px-3 py-2 space-y-2">
                                                        <?php
                                                        $id_kelas = $value['id_kelas'];
                                                        $sql_anggota_kelas = "SELECT s.nama FROM detail_kelas dk, siswa s WHERE dk.id_siswa = s.id_siswa AND dk.id_kelas = '$id_kelas'";
                                                        $data_anggota_kelas = $db->query($sql_anggota_kelas) or die($db->error);
                                                        $data_anggota_kelas->fetch_assoc();
                                                        ?>
                                                        <?php foreach ($data_anggota_kelas as $key => $siswa) : ?>
                                                            <p><?= $siswa['nama'] ?></p>
                                                        <?php endforeach ?>
                                                    </div>
                                                    <div data-popper-arrow></div>
                                                </div>
                                            </div>
                                        <?php endif ?>
                                    </td>
                                    <td class="px-6 py-4 flex gap-4">
                                        <a class="btn btn--outline-blue group" href="?edit=<?= $value['id_kelas'] ?>" class="px-5 py-2 border border-blue-500 rounded group hover:bg-blue-500">
                                            <i class="ri-edit-box-line text-blue-500 group-hover:text-white"></i>
                                        </a>
                                        <?php if ($value['jumlah_siswa'] <= 1) : ?>
                                            <form action="../../api/admin/kelas.php" method="post">
                                                <button type="submit" class="btn btn--outline-blue group" name="delete" value="<?= $value['id_kelas'] ?>">
                                                    <i class="ri-delete-bin-6-line text-red-500 group-hover:text-white"></i>
                                                </button>
                                            </form>
                                        <?php endif ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>

<?php if (!isset($_GET['edit'])) : ?>

    <div id="add_kelas_modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
        <div class="relative w-full h-full max-w-2xl md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <form action="../../api/admin/kelas.php" method="post">
                    <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Tambah Kelas
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="add_kelas_modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="glex flex-col space-y-5 p-5">
                        <div class="flex flex-col gap-2">
                            <label class="text-gray-800 dark:text-white" for="jenjang">Jenjang Kelas</label>
                            <select id="jenjang" name="jenjang" class="w-full p-2 rounded" required>
                                <?php foreach ($data_jenjang as $key => $value) : ?>
                                    <option value="<?= $value['id_jenjang'] ?>"><?= $value['nama'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-gray-800 dark:text-white" for="nama_kelas">Nama Kelas</label>
                            <input id="nama_kelas" type="text" name="nama_kelas" class="w-full p-2 rounded" required>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-gray-800 dark:text-white" for="status">Status Kelas</label>
                            <select id="status" name="status" class="w-full p-2 rounded" required>
                                <option value="Reguler">Reguler</option>
                                <option value="Privat">Privat</option>
                            </select>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button data-modal-hide="add_kelas_modal" type="submit" name="create" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif ?>

<?php include_once('../template/footer.php') ?>