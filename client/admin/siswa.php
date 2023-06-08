<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('admin', 'siswa.php');
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
    $sql = "SELECT * FROM siswa";
    $data_siswa = $db->query($sql) or die($db);
    $data_siswa->fetch_assoc();
}
?>

<div class="dashboard__main">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="dashboard__content">
        <div class="dashboard__subcontent">
            <?php include_once '../components/dashboard_navbar.php'; ?>
            <div class="flex items-center gap-5">
                <h4 class="mt-7 font-semibold text-gray-800 dark:text-white my-7">Data Siswa</h4>
                <?php if (!isset($_GET['edit'])) : ?>
                    <button data-modal-target="add_siswa_modal" data-modal-toggle="add_siswa_modal" class="btn">
                        Tambah Siswa
                    </button>

                    <button data-modal-target="import_file_modal" data-modal-toggle="import_file_modal" class="btn btn--blue">
                        Import Data Siswa
                    </button>

                    <div id="import_file_modal" tabindex="-1" aria-hidden="true" class="modal">
                        <div class="modal__backdrop">
                            <div class="modal__content">
                                <form action="../../api/admin/siswa.php" method="post" enctype="multipart/form-data">
                                    <div class="modal__header">
                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                            Import Data Siswa
                                        </h3>
                                        <button type="button" class="modal__close" data-modal-hide="import_file_modal">
                                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                    <div class="modal__body">
                                        <div class="flex flex-col gap-5">
                                            <div class="flex flex-col bg-gray-200 dark:bg-gray-800 gap-5 rounded-lg p-5">
                                                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                                    Untuk menambahkan data siswa dalam bentuk excel dapat dengan mengunduh file template dibawah ini
                                                </p>
                                                <a href="../../api/admin/siswa.php?file_import_example" class="btn btn--blue w-fit">
                                                    Template File Import
                                                </a>
                                            </div>
                                            <div class="flex flex-col bg-gray-200 dark:bg-gray-800 gap-5 rounded-lg p-5">
                                                <label for="upload_file" class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                                    Unggah file excel (.xlsx)
                                                </label>
                                                <input id="upload_file" type="file" name="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal__footer">
                                        <button type="submit" name="file_import" class="btn btn--green">Unggah</button>
                                        <button data-modal-hide="import_file_modal" type="button" class="btn">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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
                                    <label for="status">Status</label>
                                    <select class="input" name="status" id="status">
                                        <option value="Aktif" selected>Aktif</option>
                                        <option value="Alumni">Alumni</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" name="update" value="<?= $id_siswa ?>" class="self-end btn btn--blue">
                                Update
                            </button>
                        </div>
                    </div>
                </form>
            <?php else : ?>
                <?php generate_breadcrumb([['title' => 'Data Siswa', 'filename' => 'siswa.php']]); ?>
                <div class="table__container">
                    <form action="../../api/admin/siswa.php" method="post">
                        <table class="datatable table">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="flex flex-col items-center gap-3">
                                            <button type="submit" class="btn btn--outline-red w-full">Hapus</button>
                                            <div class="flex gap-4">
                                                <label for="check_all">CHECK ALL</label>
                                                <input type="checkbox" name="" id="check_all">

                                            </div>
                                        </div>
                                    </th>
                                    <th>Nama</th>
                                    <th>No.Telp</th>
                                    <th>Alamat</th>
                                    <th>Status</th>
                                    <th>Email</th>
                                    <th>Kelas</th>
                                    <th>Tanggal Pendaftaran</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data_siswa as $main_key => $siswa) : ?>
                                    <tr class="relative">
                                        <th class="flex justify-center">
                                            <label for="bulk_delete<?= $main_key ?>" class="w-full h-full absolute top-0 left-0">&nbsp;</label>
                                            <input type="checkbox" name="bulk_delete[]" value="<?= $siswa['id_siswa'] ?>" id="bulk_delete<?= $main_key ?>" class="block mx-auto">
                                        </th>
                                        <td><?= $siswa['nama'] ?></td>
                                        <td><?= $siswa['no_telp'] ?></td>
                                        <td><?= $siswa['alamat'] ?></td>
                                        <td><?= $siswa['status'] ?></td>
                                        <td><?= $siswa['email'] ?></td>
                                        <td>
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
                                        <td><?= $siswa['tgl_dibuat'] ?></td>

                                        <td class="flex gap-2">
                                            <a class="btn btn--outline-blue z-20" href="?edit=<?= $siswa['id_siswa'] ?>">
                                                <i class="ri-edit-box-line"></i>
                                            </a>

                                            <button type="button" onclick="generateConfirmationDialog('../../api/admin/siswa.php', {delete: '<?= $siswa['id_siswa'] ?>'})" class="btn btn--outline-red z-20">
                                                <i class="ri-delete-bin-6-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </form>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>

<?php if (!isset($_GET['edit'])) : ?>
    <div id="add_siswa_modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
        <div class="relative w-full h-full max-w-2xl md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="px-6 py-6 lg:px-8">
                    <form class="form" action="../../api/admin/siswa.php" method="post">
                        <!-- Modal header -->
                        <div class="flex items-start justify-between border-b rounded-t dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Tambah Siswa
                            </h3>
                            <button type="button" class="modal__close" data-modal-hide="add_siswa_modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <div class="pt-6 flex flex-col">
                            <div class="mb-5 space-y-2">
                                <label for="nama">Nama</label>
                                <input type="text" class="input" id="nama" name="nama" maxlength="50" required>
                            </div>
                            <div class="mb-5 space-y-2">
                                <label for="no_telp">No Telp</label>
                                <input type="text" class="input" id="no_telp" name="no_telp" maxlength="14" required>
                            </div>
                            <div class="mb-5 space-y-2">
                                <label for="alamat">Alamat</label>
                                <textarea class="resize-none input" name="alamat" id="" cols="30" rows="2" maxlength="50"></textarea>
                            </div>
                            <div class="mb-5 space-y-2">
                                <label for="email">Email</label>
                                <input type="email" class="input" id="email" name="email" maxlength="30" required>
                            </div>
                            <div class="mb-5 space-y-2">
                                <label for="password">Password</label>
                                <input type="text" class="input" id="password" name="password" maxlength="50" required>
                            </div>
                        </div>
                        <div class="flex justify-end items-center pt-6 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <button name="create" type="submit" class="btn btn--blue">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>

<script>
    $('#check_all').on('click', function(e) {
        let condition = this.checked
        if (condition) {
            $("input[name='bulk_delete[]']").each(function() {
                this.checked = true
            })
        } else {
            $("input[name='bulk_delete[]']").each(function() {
                this.checked = false
            })
        }
    })
</script>

<?php include_once('../template/footer.php') ?>