<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access(['Super Admin', 'Admin Akademik']);
$sql = "SELECT * FROM jenjang";
$data_jenjang = $db->query($sql) or die($db);
$data_jenjang->fetch_assoc();

$sql = "SELECT * FROM mapel";
$data_mapel = $db->query($sql) or die($db);
$data_mapel->fetch_assoc();

if (isset($_GET['edit'])) {
    $id_instruktur = $_GET['edit'];
    $sql = "SELECT * FROM instruktur WHERE id_instruktur = '$id_instruktur'";
    $result = $db->query($sql) or die($db);
    $result = $result->fetch_assoc();

    $data_mapel_instruktur = [];
    $sql = "SELECT * FROM detail_mapel d, mapel m WHERE d.id_mapel = m.id_mapel AND id_instruktur = '$id_instruktur'";
    $detail_mapel = $db->query($sql) or die($db);
    $detail_mapel->fetch_assoc();
    foreach ($detail_mapel as $key => $mapel) {
        $data_mapel_instruktur[] = $mapel['id_mapel'];
    }
} else {
    $sql = "SELECT * FROM instruktur";
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
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Data Instruktur</h4>

                <?php if (!isset($_GET['edit'])) : ?>
                    <button data-modal-target="add_instruktur_modal" data-modal-toggle="add_instruktur_modal" class="btn" type="button">
                        Tambah Instruktur
                    </button>
                <?php endif ?>
            </div>

            <?php if (isset($_GET['edit'])) : ?>
                <?php generate_breadcrumb([['title' => 'Data Instruktur', 'filename' => 'instruktur.php'], ['title' => 'Edit Data Instruktur', 'filename' => '#']]); ?>

                <div class="flex gap-5 mt-5 flex-col lg:flex-row lg:items-start">
                    <div class="flex flex-1 flex-col gap-5 bg-gray-200 dark:bg-gray-700 shadow-lg rounded p-5">
                        <h4 class="text-gray-800 dark:text-white">Data Personal</h4>
                        <form class="flex-1 flex flex-col justify-between" action="../../api/admin/instruktur.php" method="post">
                            <div class="mb-5">
                                <label for="nama" class="form-label text-secondary text-gray-400 dark:text-white">Nama</label>
                                <input type="text" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="nama" name="nama" value="<?= $result['nama'] ?>" maxlength="50" required>
                            </div>
                            <div class="mb-5">
                                <label for="no_telp" class="form-label text-secondary text-gray-400 dark:text-white">No Telp</label>
                                <input type="text" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="no_telp" name="no_telp" value="<?= $result['no_telp'] ?>" maxlength="14" required>
                            </div>
                            <div class="mb-5">
                                <label for="alamat" class="form-label text-secondary text-gray-400 dark:text-white">Alamat</label>
                                <textarea class="resize-none border rounded w-full py-1.5 border-gray-400 mt-1" name="alamat" id="" cols="30" rows="3" maxlength="50"><?= $result['alamat'] ?>"</textarea>
                            </div>
                            <button type="submit" class="w-full bg-orange-500 rounded py-1.5 text-white" name="update_profil" value="<?= $result['id_instruktur'] ?>">Ubah Data Profil</button>
                        </form>
                    </div>
                    <div class="flex flex-1 flex-col gap-5 bg-gray-200 dark:bg-gray-700 shadow-lg rounded p-5">
                        <h4 class="text-gray-800 dark:text-white">Data Kredensial</h4>
                        <form class="flex-1 flex flex-col justify-between" action="../../api/admin/instruktur.php" method="post">
                            <div class="mb-5">
                                <label for="email" class="form-label text-secondary text-gray-400 dark:text-white">Email</label>
                                <input type="email" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="email" name="email" value="<?= $result['email'] ?>" maxlength="30" required>
                            </div>
                            <div class="mb-5">
                                <label for="password" class="form-label text-secondary text-gray-400 dark:text-white">Password</label>
                                <input type="password" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="password" name="password" value="<?= $result['password'] ?>" maxlength="50" required>
                            </div>
                            <div class="mb-5">
                                <label for="confirm_password" class="form-label text-secondary text-gray-400 dark:text-white">Konfirmasi Password</label>
                                <input type="password" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="confirm_password" name="confirm_password" value="<?= $result['password'] ?>" maxlength="50" required>
                            </div>
                            <button type="submit" class="w-full bg-red-500 rounded py-1.5 text-white" name="update_kredensial" value="<?= $result['id_instruktur'] ?>">Ubah Data Kredensial</button>
                        </form>
                    </div>
                    <div class="flex flex-1 flex-col gap-5 bg-gray-200 dark:bg-gray-700 shadow-lg rounded p-5">
                        <h4 class="text-gray-800 dark:text-white">Data Mapel yang Diampu</h4>
                        <form id="form_mapel_instruktur" class="flex-1 flex flex-col" action="../../api/admin/instruktur.php" method="post">
                            <div class="flex flex-col gap-5">
                                <div id="accordion-collapse" data-accordion="collapse">
                                    <?php foreach ($data_jenjang as $key => $jenjang) : ?>
                                        <h2 id="accordion-collapse-heading-<?= $key ?>">
                                            <button type="button" data-accordion-target="#accordion-collapse-body-<?= $key ?>" aria-expanded="true" aria-controls="accordion-collapse-body-<?= $key ?>" class="flex items-center justify-between w-full p-3 font-medium text-left text-gray-500 dark:text-gray-400 hover:bg-gray-100 bg-white text-gray-800 dark:bg-gray-500 dark:text-white dark:hover:bg-gray-800 <?= $key === 0 ? 'rounded-t-xl' : '' ?>">
                                                <span class="text-base"><?= $jenjang['nama'] ?></span>
                                            </button>
                                        </h2>
                                        <div id="accordion-collapse-body-<?= $key ?>" class="hidden" aria-labelledby="accordion-collapse-heading-<?= $key ?>">
                                            <div class="p-5 dark:bg-gray-900">
                                                <?php
                                                $id_jenjang = $jenjang['id_jenjang'];
                                                $sql = "SELECT * FROM mapel WHERE id_jenjang = '$id_jenjang'";
                                                $data_mapel = $db->query($sql) or die($db->error);
                                                $data_mapel->fetch_assoc();
                                                foreach ($data_mapel as $key => $mapel) : ?>
                                                    <div class="flex flex-1 items-center mb-4">
                                                        <label for="mapel<?= $mapel['id_mapel'] ?>" class="cursor-pointer ml-2 text-sm font-medium text-gray-900 dark:text-gray-300 flex flex-1 hover:bg-gray-500 hover:text-white items-center gap-2 rounded px-2 py-1">
                                                            <input id="mapel<?= $mapel['id_mapel'] ?>" value="<?= $mapel['id_mapel'] ?>" name="mapel[]" type="checkbox" <?= in_array($mapel['id_mapel'], $data_mapel_instruktur) ? 'checked' : '' ?> class="w-4 h-4 text-blue-600 bg-gray-100 dark:bg-gray-700 dark:border-gray-600">
                                                            <div class="flex flex-1 justify-between">
                                                                <p><?= $mapel['nama'] ?> </p>
                                                            </div>
                                                        </label>
                                                    </div>
                                                <?php endforeach ?>
                                            </div>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            </div>
                            <button type="submit" class="w-full bg-gray-500 dark:bg-gray-600 dark:text-white rounded-b-lg py-1.5 text-white" name="update_mapel" value="<?= $result['id_instruktur'] ?>">Ubah Mapel</button>
                        </form>
                    </div>
                </div>
            <?php else : ?>
                <?php generate_breadcrumb([['title' => 'Data Instruktur', 'filename' => 'instruktur.php']]); ?>
                <div class="relative overflow-x-auto mt-5">
                    <table class="datatable w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3"></th>
                                <th scope="col" class="px-6 py-3">Nama</th>
                                <th scope="col" class="px-6 py-3">No.Telp</th>
                                <th scope="col" class="px-6 py-3">Alamat</th>
                                <th scope="col" class="px-6 py-3">Email</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Mapel yang diampu</th>
                                <th scope="col" class="px-6 py-3">Lama Bekerja</th>
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
                                    <td class="px-6 py-4"><?= $value['email'] ?></td>
                                    <td class="px-6 py-4"><?= $value['status'] ?></td>
                                    <td class="px-6 py-4">
                                        <ul>
                                            <?php
                                            $id_instruktur = $value['id_instruktur'];
                                            $sql = "SELECT j.nama nama_jenjang, m.nama nama_mapel  FROM instruktur i, mapel m, detail_mapel dm, jenjang j WHERE dm.id_mapel = m.id_mapel AND dm.id_instruktur = i.id_instruktur AND m.id_jenjang = j.id_jenjang AND i.id_instruktur = '$id_instruktur'";
                                            $mapel_diampu = $db->query($sql) or die(mysqli_error($db));
                                            $mapel_diampu->fetch_assoc();
                                            foreach ($mapel_diampu as $key => $mapel) : ?>
                                                <li><?= $mapel['nama_jenjang'] ?> - <?= $mapel['nama_mapel'] ?> </li>
                                            <?php endforeach ?>
                                        </ul>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php
                                        $from = new DateTime($value['tgl_dibuat']);
                                        $to = new DateTime("now");
                                        $range = $from->diff($to);
                                        $range_int = (int) $range->format('%a');
                                        echo $range_int < 365
                                            ? "<span class='text-green-500'>NEW</span>"
                                            : $range->format('%R%a days/%R%y years');
                                        ?>
                                    </td>
                                    <td class="px-6 py-4 flex gap-2">
                                        <a class="btn btn--outline-blue group" href="?edit=<?= $value['id_instruktur'] ?>">
                                            <i class="ri-edit-box-line text-blue-500 group-hover:text-white"></i>
                                        </a>
                                        <form action="../../api/admin/instruktur.php" method="post">
                                            <button class="btn btn--outline-blue group" type="submit" name="delete" value="<?= $value['id_instruktur'] ?>"><i class="ri-delete-bin-6-line text-red-500 group-hover:text-white"></i></button>
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

<div id="add_instruktur_modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
    <div class="relative w-full h-full max-w-7xl md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="px-6 py-6 lg:px-8">
                <form action="../../api/admin/instruktur.php" method="post">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between border-b rounded-t dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Tambah Data Instruktur
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="add_instruktur_modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="pt-6 flex gap-5">
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
                        <div id="form_mapel_instruktur" class="flex-1 flex flex-col" method="post">
                            <p class="text-normal text-gray-400 dark:text-white">Mapel</p>
                            <div class="flex flex-col gap-5">
                                <div id="accordion-collapse" data-accordion="collapse">
                                    <?php foreach ($data_jenjang as $key => $jenjang) : ?>
                                        <h2 id="accordion-collapse-heading-<?= $key ?>">
                                            <button type="button" data-accordion-target="#accordion-collapse-body-<?= $key ?>" aria-expanded="true" aria-controls="accordion-collapse-body-<?= $key ?>" class="flex items-center justify-between w-full p-3 font-medium text-left text-gray-500 dark:text-gray-400 hover:bg-gray-200 bg-gray-200 text-gray-800 dark:bg-gray-500 dark:text-white dark:hover:bg-gray-800 <?= $key === 0 ? 'rounded-t-xl' : '' ?>">
                                                <span class="text-base"><?= $jenjang['nama'] ?></span>
                                            </button>
                                        </h2>
                                        <div id="accordion-collapse-body-<?= $key ?>" class="hidden" aria-labelledby="accordion-collapse-heading-<?= $key ?>">
                                            <div class="p-5 dark:bg-gray-900">
                                                <?php
                                                $id_jenjang = $jenjang['id_jenjang'];
                                                $sql = "SELECT * FROM mapel WHERE id_jenjang = '$id_jenjang'";
                                                $data_mapel = $db->query($sql) or die($db->error);
                                                $data_mapel->fetch_assoc();
                                                foreach ($data_mapel as $key => $mapel) : ?>
                                                    <div class="flex flex-1 items-center mb-4">
                                                        <label for="mapel<?= $mapel['id_mapel'] ?>" class="cursor-pointer ml-2 text-sm font-medium text-gray-900 dark:text-gray-300 flex flex-1 hover:bg-gray-500 hover:text-white items-center gap-2 rounded px-2 py-1">
                                                            <input id="mapel<?= $mapel['id_mapel'] ?>" value="<?= $mapel['id_mapel'] ?>" name="mapel[]" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 dark:bg-gray-700 dark:border-gray-600">
                                                            <div class="flex flex-1 justify-between">
                                                                <p><?= $mapel['nama'] ?> </p>
                                                            </div>
                                                        </label>
                                                    </div>
                                                <?php endforeach ?>
                                            </div>
                                        </div>
                                    <?php endforeach ?>
                                </div>
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
</div>
<?php include_once('../template/footer.php') ?>