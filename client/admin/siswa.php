<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('Super Admin');

$sql = "SELECT * FROM jenjang";
$data_jenjang = $db->query($sql) or die($db->error);
$data_jenjang->fetch_assoc();

$sql = "SELECT * FROM kelas";
$data_kelas = $db->query($sql) or die($db->error);
$data_kelas->fetch_assoc();

if (isset($_GET['edit'])) {
    $id_siswa = $_GET['edit'];
    $sql = "SELECT s.*, k.nama nama_kelas, j.nama nama_jenjang FROM siswa s, jenjang j, kelas k, detail_kelas dk WHERE s.id_siswa = dk.id_siswa AND dk.id_kelas = k.id_kelas AND k.id_jenjang = j.id_jenjang AND s.id_siswa = '$id_siswa'";
    $result = $db->query($sql) or die($db);
    $result = $result->fetch_assoc();
} else {
    $sql = "SELECT s.*, k.nama nama_kelas, j.nama nama_jenjang FROM siswa s, jenjang j, kelas k, detail_kelas dk WHERE s.id_siswa = dk.id_siswa AND dk.id_kelas = k.id_kelas AND k.id_jenjang = j.id_jenjang";
    $result = $db->query($sql) or die($db);
    $result->fetch_assoc();
}

?>

<div id="anggota_kelas" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php'; ?>

            <div class="flex items-center gap-5">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Data Siswa</h4>

                <button data-modal-target="add_siswa_modal" data-modal-toggle="add_siswa_modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                    Tambah Siswa
                </button>
            </div>

            <?php if (isset($_GET['edit'])) : ?>
                <?php generate_breadcrumb([['title' => 'Siswa', 'filename' => 'siswa.php'], ['title' => 'Edit Siswa', 'filename' => '#']]); ?>

                <div class="pt-6 flex gap-5 flex-col md:flex-row">
                    <div class="flex-1 flex flex-col" method="post">
                        <div class="mb-5">
                            <label for="nama" class="form-label text-secondary text-gray-400 dark:text-white">Nama</label>
                            <input type="text" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="nama" name="nama" value="<?= $result['nama']?>" maxlength="50" required>
                        </div>
                        <div class="mb-5">
                            <label for="no_telp" class="form-label text-secondary text-gray-400 dark:text-white">No Telp</label>
                            <input type="text" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="no_telp" name="no_telp" value="<?= $result['no_telp']?>" maxlength="14" required>
                        </div>
                        <div class="mb-5">
                            <label for="alamat" class="form-label text-secondary text-gray-400 dark:text-white">Alamat</label>
                            <textarea class="resize-none border rounded w-full py-1.5 border-gray-400 mt-1" name="alamat" id="" cols="30" rows="3" maxlength="50"><?= $result['alamat']?></textarea>
                        </div>
                    </div>
                    <div class="flex-1 flex flex-col" method="post">
                        <div class="mb-5">
                            <label for="email" class="form-label text-secondary text-gray-400 dark:text-white">Email</label>
                            <input type="email" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="email" name="email" value="<?= $result['email']?>"maxlength="30" required>
                        </div>
                        <div class="mb-5">
                            <label for="password" class="form-label text-secondary text-gray-400 dark:text-white">Password</label>
                            <input type="text" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="password" name="password" value="<?= $result['password']?>"maxlength="50" required>
                        </div>
                    </div>
                    <div class="flex flex-1 flex-col gap-5">
                        <div id="form_hak_akses_siswa" class="flex flex-col" method="post">
                            <p class="text-normal text-gray-400 dark:text-white">Jenjang</p>
                            <select name="jenjang" id="jenjang" class="border rounded w-full py-1.5 border-gray-400 mt-1 bg-white dark:bg-gray-200">
                                <?php foreach ($data_jenjang as $key => $value) : ?>
                                    <option value="<?= $value['id_jenjang'] ?>" <?= $value['nama'] === $result['nama_jenjang'] ? 'selected' : '' ?>><?= $value['nama'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div id="form_hak_akses_siswa" class="flex flex-col" method="post">
                            <p class="text-normal text-gray-400 dark:text-white">Kelas</p>
                            <select name="kelas" id="kelas" class="border rounded w-full py-1.5 border-gray-400 mt-1 bg-white dark:bg-gray-200">
                                <?php foreach ($data_kelas as $key => $value) : ?>
                                    <option value="<?= $value['id_kelas'] ?>" <?= $value['nama'] === $result['nama_kelas'] ? 'selected' : '' ?>><?= $value['nama'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                </div>


            <?php else : ?>
                <?php generate_breadcrumb([['title' => 'Data Siswa', 'filename' => 'siswa.php']]); ?>
                <div class="relative overflow-x-auto mt-5">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3"></th>
                                <th scope="col" class="px-6 py-3">Nama</th>
                                <th scope="col" class="px-6 py-3">No.Telp</th>
                                <th scope="col" class="px-6 py-3">Alamat</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Email</th>
                                <th scope="col" class="px-6 py-3">Kelas</th>
                                <th scope="col" class="px-6 py-3">Jenjang</th>
                                <th scope="col" class="px-6 py-3">Belajar Sejak</th>
                                <th scope="col" class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($result as $key => $value) : ?>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th class="px-6 py-4 text-amber-500"></th>
                                    <td class="px-6 py-4"><?= $value['nama'] ?></td>
                                    <td class="px-6 py-4"><?= $value['no_telp'] ?></td>
                                    <td class="px-6 py-4"><?= $value['alamat'] ?></td>
                                    <td class="px-6 py-4"><?= $value['status'] ?></td>
                                    <td class="px-6 py-4"><?= $value['email'] ?></td>
                                    <td class="px-6 py-4"><?= $value['nama_kelas'] ?></td>
                                    <td class="px-6 py-4"><?= $value['nama_jenjang'] ?></td>

                                    <td class="px-6 py-4">
                                        <?= $value['tgl_dibuat'] ?>
                                    </td>

                                    <td class="px-6 py-4">
                                        <a href="?edit=<?= $value['id_siswa'] ?>">
                                            <i class="ri-edit-box-line text-blue-500"></i>
                                        </a>
                                        <form action="../../api/admin/siswa.php" method="post">
                                            <button type="submit" name="delete" value="<?= $value['id_siswa'] ?>"><i class="ri-delete-bin-6-line text-red-500"></i></button>
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

<div id="add_siswa_modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
    <div class="relative w-full h-full max-w-7xl md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="px-6 py-6 lg:px-8">
                <form action="../../api/siswa/siswa.php" method="post">
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
                                <label for="nama" class="form-label text-secondary text-gray-400 dark:text-white">Nama</label>
                                <input type="text" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="nama" name="nama" maxlength="50" required>
                            </div>
                            <div class="mb-5">
                                <label for="no_telp" class="form-label text-secondary text-gray-400 dark:text-white">No Telp</label>
                                <input type="text" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="no_telp" name="no_telp" maxlength="14" required>
                            </div>
                            <div class="mb-5">
                                <label for="alamat" class="form-label text-secondary text-gray-400 dark:text-white">Alamat</label>
                                <textarea class="resize-none border rounded w-full py-1.5 border-gray-400 mt-1" name="alamat" id="" cols="30" rows="3" maxlength="50"></textarea>
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col" method="post">
                            <div class="mb-5">
                                <label for="email" class="form-label text-secondary text-gray-400 dark:text-white">Email</label>
                                <input type="email" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="email" name="email" maxlength="30" required>
                            </div>
                            <div class="mb-5">
                                <label for="password" class="form-label text-secondary text-gray-400 dark:text-white">Password</label>
                                <input type="text" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="password" name="password" maxlength="50" required>
                            </div>
                        </div>
                        <div class="flex flex-1 flex-col gap-5">
                            <div id="form_hak_akses_siswa" class="flex flex-col" method="post">
                                <p class="text-normal text-gray-400 dark:text-white">Jenjang</p>
                                <select name="jenjang" id="jenjang" class="border rounded w-full py-1.5 border-gray-400 mt-1 bg-white dark:bg-gray-200">
                                    <?php foreach ($data_jenjang as $key => $value) : ?>
                                        <option value="<?= $value['id_jenjang'] ?>"><?= $value['nama'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div id="form_hak_akses_siswa" class="flex flex-col" method="post">
                                <p class="text-normal text-gray-400 dark:text-white">Kelas</p>
                                <select name="kelas" id="kelas" class="border rounded w-full py-1.5 border-gray-400 mt-1 bg-white dark:bg-gray-200">
                                    <?php foreach ($data_kelas as $key => $value) : ?>
                                        <option value="<?= $value['id_kelas'] ?>"><?= $value['nama'] ?></option>
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

<?php
include_once('../template/footer.php') ?>