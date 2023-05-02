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
} else if (isset($_GET['create'])) {
    $sql = "SELECT * FROM siswa s WHERE id_siswa NOT IN (SELECT id_siswa FROM detail_kelas WHERE id_siswa = s.id_siswa)";
    $data_siswa = $db->query($sql) or die($db->error);
    $data_siswa->fetch_assoc();
} else if (!isset($_GET['edit']) && !isset($_GET['create'])) {
    $sql = "SELECT k.*, s.nama nama_ketua_kelas, j.nama nama_jenjang, COUNT(dk.id_detail_kelas) count_detail_kelas, (SELECT COUNT(*) FROM jadwal ja WHERE k.id_kelas = ja.id_kelas) count_jadwal FROM kelas k
    JOIN jenjang j ON k.id_jenjang = j.id_jenjang
    LEFT JOIN detail_kelas dk ON k.id_kelas = dk.id_kelas
    LEFT JOIN siswa s ON k.id_ketua_kelas = s.id_siswa
    GROUP BY k.id_kelas";
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
                    <a href="?create" class="btn">
                        Tambah kelas
                    </a>
                <?php endif ?>
            </div>

            <?php if (isset($_GET['edit'])) : ?>
                <?php generate_breadcrumb([['title' => 'Kelas', 'filename' => 'kelas.php'], ['title' => "Edit Kelas $nama_kelas", 'filename' => '#']]); ?>
                <form action="../../api/admin/kelas.php" method="post" class="bg-white dark:bg-gray-700 rounded p-8 mt-8 flex flex-col gap-5">
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
                                        <!-- <option value="Privat" <?= $data_kelas['status'] === 'Privat' ? 'selected' : '' ?>>Privat</option> -->
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
                    <button type="submit" class="btn btn--blue w-fit" name="update" value="<?= $id_kelas ?>">Update</button>
                </form>
            <?php endif ?>

            <?php if (isset($_GET['create'])) : ?>
                <?php generate_breadcrumb([['title' => 'Kelas', 'filename' => 'kelas.php'], ['title' => "Tambah Kelas", 'filename' => '#']]); ?>
                <form action="../../api/admin/kelas.php" method="post" class="bg-white dark:bg-gray-700 rounded p-8 mt-8 flex flex-col">
                    <div class="flex-1 flex-col space-y-2">
                        <div class="flex flex-col gap-1">
                            <label class="text-gray-800 dark:text-white" for="nama_kelas">Nama Kelas</label>
                            <input id="nama_kelas" name="nama_kelas" type="text" class="rounded" required>
                        </div>
                        <div class="flex flex-1 gap-3">
                            <div class="flex flex-1 flex-col gap-1">
                                <label class="text-gray-800 dark:text-white" for="status_kelas">Status Kelas</label>
                                <select name="status_kelas" id="status_kelas" class="rounded" required>
                                    <option value="Reguler">Reguler</option>
                                    <!-- <option value="Privat">Privat</option> -->
                                </select>
                            </div>
                            <div class="flex flex-1 flex-col gap-1">
                                <label class="text-gray-800 dark:text-white" for="jenjang">Jenjang</label>
                                <select name="jenjang" id="jenjang" class="rounded" required>
                                    <?php foreach ($data_jenjang as $key => $jenjang) : ?>
                                        <option value="<?= $jenjang['id_jenjang'] ?>"><?= $jenjang['nama'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-12">
                    <div class="relative overflow-x-auto">
                        <table class="datatable-add-siswa table">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3"></th>
                                    <th scope="col" class="px-6 py-3">Nama Siswa</th>
                                    <th scope="col" class="px-6 py-3">Nomor Telepon</th>
                                    <th scope="col" class="px-6 py-3">Anggota Kelas</th>
                                    <th scope="col" class="px-6 py-3">Ketua Kelas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data_siswa as $key => $siswa) : ?>
                                    <tr class="relative">
                                        <td class="text-amber-500"><?= (int) $key + 1 ?></td>
                                        <td><?= $siswa['nama'] ?></td>
                                        <td><?= $siswa['no_telp'] ?></td>
                                        <td>
                                            <label class="absolute top-0 left-0 w-full h-full z-10" for="anggota_kelas<?= $key ?>">&nbsp;</label>
                                            <input type="checkbox" id="anggota_kelas<?= $key ?>" name="anggota_kelas[]" value="<?= $siswa['id_siswa'] ?>">
                                        </td>
                                        <td><input type="radio" name="ketua_kelas" value="<?= $siswa['id_siswa'] ?>" class="relative z-20"></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" class="btn btn--blue w-fit" name="create" value="<?= $id_kelas ?>">Tambah</button>
                </form>
            <?php endif ?>

            <?php if (!isset($_GET['create']) && !isset($_GET['edit'])) : ?>
                <?php generate_breadcrumb([['title' => 'Kelas', 'filename' => 'kelas.php']]); ?>
                <div class="relative overflow-x-auto overflow-y-hidden mt-5">
                    <table class="datatable table">
                        <thead>
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
                                <?php $delete_able = $value['count_detail_kelas'] === '0' && $value['count_jadwal'] === '0'  ?>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th class="px-6 py-4 text-amber-500"><?= $key + 1 ?></th>
                                    <td class="px-6 py-4"><?= $value['nama'] ?></td>
                                    <td class="px-6 py-4"><?= $value['nama_jenjang'] ?></td>
                                    <td class="px-6 py-4"><?= $value['status'] ?></td>
                                    <td class="px-6 py-4"><?= $value['nama_ketua_kelas'] ?></td>
                                    <td class="px-6 py-4">
                                        <?php if ((int) $value['count_detail_kelas'] < 1) : ?>
                                            <p class="text-green-500">New</p>
                                        <?php else : ?>
                                            <div class="flex gap-1">
                                                <?= $value['count_detail_kelas'] ?>
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
                                        <?php if ($delete_able) : ?>
                                            <button onclick="generateConfirmationDialog('../../api/admin/kelas.php', {delete: '<?= $value['id_kelas'] ?>'})" class="btn btn--outline-red">
                                                <i class="ri-delete-bin-6-line"></i>
                                            </button>
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
<?php include_once('../template/footer.php') ?>