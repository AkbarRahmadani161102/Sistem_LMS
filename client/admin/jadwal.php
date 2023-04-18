<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access(['Super Admin', 'Admin Akademik']);

$hari = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];
$jam_kbm = ["14:30:00", "15:30:00", "16:30:00", "17:30:00"];
$sql = "SELECT * FROM jenjang";
$data_jenjang = $db->query($sql) or die($db->error);
$data_jenjang->fetch_assoc();

$sql = "SELECT j.*, k.nama nama_kelas, i.nama nama_instruktur, k.status, je.nama nama_jenjang, s.nama ketua_kelas, m.nama nama_mapel FROM jadwal j
JOIN kelas k ON j.id_kelas = k.id_kelas
JOIN jenjang je ON k.id_jenjang = je.id_jenjang
JOIN mapel m ON j.id_mapel = m.id_mapel
LEFT JOIN instruktur i ON j.id_instruktur = i.id_instruktur
LEFT JOIN siswa s ON k.id_ketua_kelas = s.id_siswa
ORDER BY nama_kelas, nama_jenjang, hari";
$data_jadwal = $db->query($sql) or die($db->error);
$data_jadwal->fetch_assoc();

if (isset($_GET['jenjang'])) {
    $id_jenjang = $_GET['jenjang'];

    $sql = "SELECT * FROM mapel WHERE id_jenjang = '$id_jenjang'";
    $data_mapel = $db->query($sql) or die($db->error);
    $data_mapel->fetch_assoc();

    $sql = "SELECT * FROM kelas WHERE id_jenjang = '$id_jenjang'";
    $data_kelas = $db->query($sql) or die($db->error);
    $data_kelas->fetch_assoc();

    $sql = "SELECT j.*, k.nama nama_kelas, i.nama nama_instruktur, k.status, je.nama nama_jenjang, s.nama ketua_kelas, m.nama nama_mapel FROM jadwal j
    JOIN kelas k ON j.id_kelas = k.id_kelas
    JOIN jenjang je ON k.id_jenjang = je.id_jenjang
    JOIN mapel m ON j.id_mapel = m.id_mapel
    LEFT JOIN siswa s ON k.id_ketua_kelas = s.id_siswa
    LEFT JOIN instruktur i ON j.id_instruktur = i.id_instruktur
    WHERE je.id_jenjang = $id_jenjang";
    $data_jadwal = $db->query($sql) or die($db->error);
    $data_jadwal->fetch_assoc();
}

if (isset($_GET['edit'])) {
    $sql = "SELECT * FROM mapel";
    $data_mapel = $db->query($sql) or die($db->error);
    $data_mapel->fetch_assoc();

    $sql = "SELECT * FROM kelas";
    $data_kelas = $db->query($sql) or die($db->error);
    $data_kelas->fetch_assoc();

    $id_jadwal = $_GET['edit'];
    $sql = "SELECT * FROM jadwal WHERE id_jadwal = '$id_jadwal'";
    $data_jadwal = $db->query($sql) or die($db->error);
    $data_jadwal = $data_jadwal->fetch_assoc();


    if (isset($data_jadwal['id_instruktur']))
        redirect('jadwal.php');
}

if (isset($_GET['assign_instruktur'])) {
    $id_jadwal = isset($_GET['assign_instruktur']) ? $_GET['assign_instruktur'] : $_GET['reassign_instruktur'];
    $sql = "SELECT j.*, m.nama nama_mapel, k.nama nama_kelas FROM jadwal j, mapel m, kelas k WHERE j.id_mapel = m.id_mapel AND j.id_kelas = k.id_kelas AND id_jadwal = '$id_jadwal'";
    $data_jadwal = $db->query($sql) or die($db->error);
    $data_jadwal = $data_jadwal->fetch_assoc();
    $id_mapel = $data_jadwal['id_mapel'];
}
?>

<div id="jadwal" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php'; ?>
            <div class="flex items-center gap-5 my-7">
                <?php if (!isset($_GET['assign_instruktur'])) : ?>
                    <h4 class="font-semibold text-gray-800 dark:text-white">Jadwal</h4>
                <?php endif ?>
                <?php if (isset($_GET['jenjang'])) : ?>
                    <button data-modal-target="add_jadwal_modal" data-modal-toggle="add_jadwal_modal" class="btn" type="button">
                        Tambah Jadwal
                    </button>
                <?php endif ?>
            </div>
            <?php if (isset($_GET['assign_instruktur'])) { ?>
                <div class="flex gap-2 mt-5 flex-col lg:flex-row">
                    <div class="flex w-full lg:w-1/4 flex-col rounded bg-gray-200 dark:bg-gray-600 p-5 space-y-3 text-gray-800 dark:text-white">
                        <h5>Detail Jadwal</h5>
                        <div class="flex justify-between hover:bg-gray-500 hover:text-white py-3 px-2 rounded-lg">
                            <p>Nama Mapel:</p>
                            <p><?= $data_jadwal['nama_mapel'] ?></p>
                        </div>
                        <div class="flex justify-between hover:bg-gray-500 hover:text-white py-3 px-2 rounded-lg">
                            <p>Kelas:</p>
                            <p><?= $data_jadwal['nama_kelas'] ?></p>
                        </div>
                        <div class="flex justify-between hover:bg-gray-500 hover:text-white py-3 px-2 rounded-lg">
                            <p>Hari:</p>
                            <p><?= $data_jadwal['hari'] ?></p>
                        </div>
                        <div class="flex justify-between hover:bg-gray-500 hover:text-white py-3 px-2 rounded-lg">
                            <p>Jam Mulai:</p>
                            <p><?= $data_jadwal['jam_mulai'] ?></p>
                        </div>
                        <div class="flex justify-between hover:bg-gray-500 hover:text-white py-3 px-2 rounded-lg">
                            <p>Jam Selesai:</p>
                            <p><?= $data_jadwal['jam_selesai'] ?></p>
                        </div>
                    </div>
                    <form class="form flex-1 rounded bg-gray-200 dark:bg-gray-600 p-5 space-y-5" action="../../api/admin/jadwal.php" method="post">
                        <label class="text-xl" for="instruktur">Instruktur yang mengampu</label>
                        <label class="block" for="instruktur">Instruktur dibawah ini telah disaring berdasarkan mata pelajaran yang diampu</label>
                        <select class="input selectize" name="instruktur" id="instruktur" required>
                            <?php
                            $sql = "SELECT i.* FROM detail_mapel d, instruktur i WHERE d.id_instruktur = i.id_instruktur AND d.id_mapel = '$id_mapel'";
                            $data_instruktur = $db->query($sql) or die($db->error);
                            while ($instruktur = $data_instruktur->fetch_assoc()) : ?>
                                <option value="<?= $instruktur['id_instruktur'] ?>"><?= $instruktur['nama'] ?></option>
                            <?php endwhile ?>
                        </select>
                        <button class="btn dark:bg-green-500 dark:text-white" name="assign_instruktur" value="<?= $id_jadwal ?>">Tetapkan</button>
                    </form>
                </div>
            <?php } else if (isset($_GET['edit'])) { ?>
                <?php generate_breadcrumb([['title' => 'Jadwal', 'filename' => 'jadwal.php'], ['title' => 'Edit Jadwal', 'filename' => 'jadwal.php']]); ?>
                <form action="../../api/admin/jadwal.php" method="post" class="form flex flex-col rounded bg-gray-700 p-5 mt-5">
                    <div class="flex flex-col mb-5 gap-2">
                        <label for="kelas" class="text-gray-800 dark:text-white">Kelas</label>
                        <select name="kelas" id="kelas" class="input" required>
                            <?php foreach ($data_kelas as $key => $value) : ?>
                                <option value="<?= $value['id_kelas'] ?>" <?= $value['id_kelas'] === $data_jadwal['id_kelas'] ? 'selected' : '' ?>><?= $value['nama'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="flex flex-col mb-5 gap-2">
                        <label for="mapel" class="text-gray-800 dark:text-white">Mapel</label>
                        <select name="mapel" id="mapel" class="input" required>
                            <?php foreach ($data_mapel as $key => $value) : ?>
                                <option value="<?= $value['id_mapel'] ?>" <?= $value['id_mapel'] === $data_jadwal['id_mapel'] ? 'selected' : '' ?>><?= $value['nama'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="flex flex-col mb-5 gap-2">
                        <label for="hari" class="text-gray-800 dark:text-white">Hari</label>
                        <select name="hari" id="hari" class="input" required>
                            <?php foreach ($hari as $value) : ?>
                                <option value="<?= $value ?>" <?= $value === $data_jadwal['hari'] ? 'selected' : '' ?>><?= $value ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-1 flex-col mb-5 gap-2">
                            <label for="jam_mulai" class="text-gray-800 dark:text-white">Jam Mulai</label>
                            <select name="jam_mulai" id="jam_mulai" class="input" required>
                                <?php foreach ($jam_kbm as $jam) : ?>
                                    <option value="<?= $jam ?>" <?= $jam === $data_jadwal['jam_mulai'] ? 'selected' : '' ?>><?= $jam ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="flex-1 flex-col mb-5 gap-2">
                            <label for="jam_selesai" class="text-gray-800 dark:text-white">Jam Selesai</label>
                            <select name="jam_selesai" id="jam_selesai" class="input" required>
                                <?php foreach ($jam_kbm as $jam) : ?>
                                    <option value="<?= $jam ?>" <?= $jam === $data_jadwal['jam_selesai'] ? 'selected' : '' ?>><?= $jam ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" name="update" value="<?= $id_jadwal ?>" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Ubah</button>
                </form>
            <?php } else { ?>
                <?php generate_breadcrumb([['title' => 'Jadwal', 'filename' => 'jadwal.php']]); ?>
                <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400 mt-5">
                    <?php foreach ($data_jenjang as $key => $jenjang) : ?>
                        <li class="mr-2">
                            <a href="?jenjang=<?= $jenjang['id_jenjang'] ?>" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800 dark:hover:text-gray-300 <?= isset($_GET['jenjang']) && $_GET['jenjang'] === $jenjang['id_jenjang'] ? 'text-blue-500' : '' ?>"><?= $jenjang['nama'] ?></a>
                        </li>
                    <?php endforeach ?>
                </ul>
                <div class="relative overflow-x-auto">
                    <table class="datatable w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">#</th>
                                <th scope="col" class="px-6 py-3">Nama Kelas</th>
                                <th scope="col" class="px-6 py-3">Nama Mapel</th>
                                <th scope="col" class="px-6 py-3">Nama Instruktur</th>
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
                            <?php foreach ($data_jadwal as $key => $jadwal) : ?>
                                <tr class="border-b dark:bg-gray-800 dark:border-gray-700 bg-transparent">
                                    <th class="px-6 py-4 text-amber-500"><?= $key + 1 ?></th>
                                    <td class="px-6 py-4"><?= $jadwal['nama_kelas'] ?></td>
                                    <td class="px-6 py-4"><?= $jadwal['nama_mapel'] ?></td>
                                    <td class="px-6 py-4"><?= $jadwal['nama_instruktur'] ?></td>
                                    <td class="px-6 py-4"><?= $jadwal['status'] ?></td>
                                    <td class="px-6 py-4"><?= $jadwal['ketua_kelas'] ?></td>
                                    <td class="px-6 py-4"><?= $jadwal['nama_jenjang'] ?></td>
                                    <td class="px-6 py-4"><?= $jadwal['hari'] ?></td>
                                    <td class="px-6 py-4"><?= $jadwal['jam_mulai'] ?></td>
                                    <td class="px-6 py-4"><?= $jadwal['jam_selesai'] ?></td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-3">
                                            <a class="btn btn--outline-green flex justify-around gap-3" href="?assign_instruktur=<?= $jadwal['id_jadwal'] ?>">
                                                <i class="ri-arrow-left-right-line"></i>
                                                <p>Ganti Instruktur</p>
                                            </a>
                                            <?php if (!$jadwal['nama_instruktur']) : ?>
                                                <a class="btn btn--outline-blue group flex" href="?edit=<?= $jadwal['id_jadwal'] ?>">
                                                    <i class="ri-edit-box-line text-blue-500 mx-auto group-hover:text-white"></i>
                                                </a>
                                            <?php endif ?>
                                            <form action="../../api/admin/jadwal.php" method="post">
                                                <button class="btn btn--outline-red group flex w-full" type="submit" name="delete" value="<?= $jadwal['id_jadwal'] ?>">
                                                    <i class="ri-delete-bin-6-line text-red-500 mx-auto group-hover:text-white"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php if (isset($_GET['jenjang'])) : ?>
    <div id="add_jadwal_modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
        <div class="relative w-full h-full max-w-2xl md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <form class="form" action="../../api/admin/jadwal.php" method="post">
                    <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Tambah Role
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="add_jadwal_modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 space-y-6">
                        <div class="flex flex-col mb-5 gap-2">
                            <label for="kelas">Kelas</label>
                            <select name="kelas" id="kelas" class="input" required>
                                <?php foreach ($data_kelas as $key => $value) : ?>
                                    <option value="<?= $value['id_kelas'] ?>"><?= $value['nama'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="flex flex-col mb-5 gap-2">
                            <label for="mapel">Mapel</label>
                            <select name="mapel" id="mapel" class="input" required>
                                <?php foreach ($data_mapel as $key => $value) : ?>
                                    <option value="<?= $value['id_mapel'] ?>"><?= $value['nama'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="flex flex-col mb-5 gap-2">
                            <label for="nama_kelas">Hari</label>
                            <select name="hari" id="hari" class="input" required>
                                <?php foreach ($hari as $value) : ?>
                                    <option value="<?= $value ?>"><?= $value ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="flex flex-col mb-5 gap-2">
                            <label for="jam_mulai">Jam Mulai</label>
                            <select name="jam_mulai" id="jam_mulai" class="input" required>
                                <?php foreach ($jam_kbm as $jam) : ?>
                                    <option value="<?= $jam ?>"><?= $jam ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="flex flex-col mb-5 gap-2">
                            <label for="jam_selesai">Jam Selesai</label>
                            <select name="jam_selesai" id="jam_selesai" class="input" required>
                                <?php foreach ($jam_kbm as $jam) : ?>
                                    <option value="<?= $jam ?>"><?= $jam ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button type="submit" name="create" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif ?>

<?php
include_once('../template/footer.php') ?>