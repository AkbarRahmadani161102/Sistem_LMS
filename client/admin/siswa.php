<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access(['Super Admin', 'Admin Akademik']);

$sql = "SELECT *, j.nama nama_jenjang FROM kelas k JOIN jenjang j ON k.id_jenjang = j.id_jenjang";
$data_kelas = $db->query($sql) or die($db->error);
$data_kelas->fetch_assoc();

if (isset($_GET['edit'])) {
    $id_siswa = $_GET['edit'];
    $sql = "SELECT s.*, k.nama nama_kelas, j.nama nama_jenjang FROM siswa s
    LEFT JOIN detail_kelas dk ON s.id_siswa = dk.id_siswa
    LEFT JOIN kelas k ON k.id_kelas = dk.id_kelas
    LEFT JOIN jenjang j ON j.id_jenjang = k.id_jenjang
    WHERE s.id_siswa = '$id_siswa'";
    $result = $db->query($sql) or die($db);
    $result = $result->fetch_assoc();

    $sql = "SELECT k.id_kelas FROM detail_kelas dk
    JOIN kelas k ON dk.id_kelas = k.id_kelas
    JOIN siswa s ON dk.id_siswa = s.id_siswa 
    WHERE s.id_siswa = '$id_siswa' 
    LIMIT 1";
    $data_kelas_siswa = $db->query($sql) or die($db->error);
    $data_kelas_siswa = $data_kelas_siswa->fetch_assoc();
} else {
    $sql = "SELECT *, YEAR(tgl_dibuat) tgl_dibuat FROM siswa";
    $data_siswa = $db->query($sql) or die($db);
    $data_siswa->fetch_assoc();
}
?>

<div id="anggota_kelas" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php'; ?>
            <div class="flex items-center gap-5">
                <h4 class="mt-7 font-semibold text-gray-800 dark:text-white">Data Siswa</h4>
                <?php if (!isset($_GET['edit'])) : ?>
                    <button data-modal-target="add_siswa_modal" data-modal-toggle="add_siswa_modal" class="btn" type="button">
                        Tambah Siswa
                    </button>
                <?php endif ?>
            </div>
            <?php if (isset($_GET['edit'])) : ?>
                <?php generate_breadcrumb([['title' => 'Siswa', 'filename' => 'siswa.php'], ['title' => 'Edit Siswa', 'filename' => '#']]); ?>
                <form class="form rounded bg-gray-200 dark:bg-gray-700 p-5 mt-8" action="../../api/admin/siswa.php" method="post">
                    <div class="flex gap-5 flex-col md:flex-row">
                        <div class="flex-1 flex flex-col space-y-5">
                            <div class="space-y-2">
                                <label for="nama">Nama</label>
                                <input type="text" class="input" id="nama" name="nama" value="<?= $result['nama'] ?>" maxlength="50">
                            </div>
                            <div class="flex gap-5">
                                <div class="flex flex-1 flex-col space-y-2">
                                    <label for="email">Email</label>
                                    <input type="email" class="input" id="email" name="email" value="<?= $result['email'] ?>" maxlength="30">
                                </div>
                                <div class="flex flex-1 flex-col space-y-2">
                                    <label for="password">Password</label>
                                    <input type="password" class="input" id="password" name="password" value="<?= $result['password'] ?>" maxlength="50">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label for="no_telp">No Telp</label>
                                <input type="text" class="input" id="no_telp" name="no_telp" value="<?= $result['no_telp'] ?>" maxlength="14">
                            </div>
                            <div class="space-y-2">
                                <label for="alamat">Alamat</label>
                                <textarea class="resize-none input" name="alamat" id="" cols="30" rows="3" maxlength="50"><?= $result['alamat'] ?></textarea>
                            </div>
                            <div class="flex gap-5">
                                <div class="flex flex-1 flex-col space-y-2">
                                    <label for="kelas">Kelas</label>
                                    <select name="kelas" id="kelas" class="input" required>
                                        <?php
                                        $sql = "SELECT * FROM jenjang";
                                        $data_jenjang = $db->query($sql) or die($db->error);
                                        $data_jenjang->fetch_assoc(); ?>

                                        <?php foreach ($data_jenjang as $jenjang) : ?>
                                            <optgroup label="<?= $jenjang['nama'] ?>">
                                                <?php
                                                $id_jenjang = $jenjang['id_jenjang'];
                                                $sql = "SELECT * FROM kelas WHERE id_jenjang = '$id_jenjang'";
                                                $data_kelas = $db->query($sql) or die($db->error);
                                                $data_kelas->fetch_assoc(); ?>

                                                <?php foreach ($data_kelas as $kelas) : ?>
                                                    <option value="<?= isset($kelas['id_kelas']) ? $kelas['id_kelas'] : null ?>" <?= isset($data_kelas_siswa['id_kelas']) && $data_kelas_siswa['id_kelas'] === $kelas['id_kelas'] ? 'selected' : '' ?>>
                                                        <?= $kelas['nama'] ?>
                                                    </option>
                                                <?php endforeach ?>
                                            </optgroup>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="flex flex-1 flex-col space-y-2">
                                    <label for="status">Status</label>
                                    <select class="input" name="status" id="status">
                                        <option value="Aktif" selected>Aktif</option>
                                        <option value="Alumni">Alumni</option>
                                    </select>
                                </div>
                            </div>

                            <button type="submit" name="update" value="<?= $id_siswa ?>" class="self-end block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Update
                            </button>
                        </div>


                    </div>
                </form>
            <?php else : ?>
                <?php generate_breadcrumb([['title' => 'Data Siswa', 'filename' => 'siswa.php']]); ?>
                <div class="relative overflow-x-auto mt-5">
                    <table class="datatable w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3"></th>
                                <th scope="col" class="px-6 py-3">Nama</th>
                                <th scope="col" class="px-6 py-3">No.Telp</th>
                                <th scope="col" class="px-6 py-3">Alamat</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Email</th>
                                <th scope="col" class="px-6 py-3">Kelas</th>
                                <th scope="col" class="px-6 py-3">Belajar Sejak</th>
                                <th scope="col" class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data_siswa as $key => $siswa) : ?>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th class="px-6 py-4 text-amber-500"></th>
                                    <td class="px-6 py-4"><?= $siswa['nama'] ?></td>
                                    <td class="px-6 py-4"><?= $siswa['no_telp'] ?></td>
                                    <td class="px-6 py-4"><?= $siswa['alamat'] ?></td>
                                    <td class="px-6 py-4"><?= $siswa['status'] ?></td>
                                    <td class="px-6 py-4"><?= $siswa['email'] ?></td>
                                    <td class="px-6 py-4">
                                        <ul>
                                            <?php
                                            $id_siswa = $siswa['id_siswa'];
                                            $sql = "SELECT k.* FROM detail_kelas dk, kelas k WHERE dk.id_kelas = k.id_kelas AND dk.id_siswa = '$id_siswa'";
                                            $data_kelas_siswa = $db->query($sql) or die($db->error);
                                            $data_kelas_siswa->fetch_assoc(); ?>

                                            <?php foreach ($data_kelas_siswa as $key => $kelas) : ?>
                                                <li class="flex gap-2"><?= $kelas['nama'] ?></li>
                                            <?php endforeach ?>
                                        </ul>
                                    </td>
                                    <td class="px-6 py-4"><?= $siswa['tgl_dibuat'] ?></td>

                                    <td class="px-6 py-4 flex gap-2">
                                        <a class="btn btn--outline-blue group" href="?edit=<?= $siswa['id_siswa'] ?>">
                                            <i class="ri-edit-box-line text-blue-500 group-hover:text-white"></i>
                                        </a>
                                        <form action="../../api/admin/siswa.php" method="post">
                                            <button class="btn btn--outline-blue group" type="submit" name="delete" value="<?= $siswa['id_siswa'] ?>"><i class="ri-delete-bin-6-line text-red-500 group-hover:text-white"></i></button>
                                        </form>
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
    <div id="add_siswa_modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
        <div class="relative w-full h-full max-w-7xl md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="px-6 py-6 lg:px-8">
                    <form class="form" action="../../api/admin/siswa.php" method="post">
                        <!-- Modal header -->
                        <div class="flex items-start justify-between border-b rounded-t dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Tambah Siswa
                            </h3>
                            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="add_siswa_modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <div class="pt-6 flex gap-5 flex-col md:flex-row">
                            <div class="flex-1 flex flex-col" method="post">
                                <div class="mb-5">
                                    <label for="nama">Nama</label>
                                    <input type="text" class="input" id="nama" name="nama" maxlength="50" required>
                                </div>
                                <div class="mb-5">
                                    <label for="no_telp">No Telp</label>
                                    <input type="text" class="input" id="no_telp" name="no_telp" maxlength="14" required>
                                </div>
                                <div class="mb-5">
                                    <label for="alamat">Alamat</label>
                                    <textarea class="resize-none input" name="alamat" id="" cols="30" rows="2" maxlength="50"></textarea>
                                </div>
                            </div>
                            <div class="flex-1 flex flex-col" method="post">
                                <div class="mb-5">
                                    <label for="email">Email</label>
                                    <input type="email" class="input" id="email" name="email" maxlength="30" required>
                                </div>
                                <div class="mb-5">
                                    <label for="password">Password</label>
                                    <input type="text" class="input" id="password" name="password" maxlength="50" required>
                                </div>
                                <div class="mb-5">
                                    <label for="kelas" class="text-normal text-gray-400 dark:text-white">Kelas</label>
                                    <select name="kelas" id="kelas" class="input" required>
                                        <?php
                                        $sql = "SELECT * FROM jenjang";
                                        $data_jenjang = $db->query($sql) or die($db->error);
                                        $data_jenjang->fetch_assoc(); ?>

                                        <?php foreach ($data_jenjang as $jenjang) : ?>
                                            <optgroup label="<?= $jenjang['nama'] ?>">
                                                <?php
                                                $id_jenjang = $jenjang['id_jenjang'];
                                                $sql = "SELECT * FROM kelas WHERE id_jenjang = '$id_jenjang'";
                                                $data_kelas = $db->query($sql) or die($db->error);
                                                $data_kelas->fetch_assoc(); ?>

                                                <?php foreach ($data_kelas as $kelas) : ?>
                                                    <option value="<?= $kelas['id_kelas'] ?>"><?= $kelas['nama'] ?></option>
                                                <?php endforeach ?>
                                            </optgroup>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end items-center pt-6 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <button name="create" type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>

<?php include_once('../template/footer.php') ?>