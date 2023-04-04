<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('instruktur');

$sql = "SELECT * FROM penilaian";
$result = $db->query($sql) or die($sql);
$result->fetch_assoc();
?>

<div id="jenjang" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php'; ?>

            <div class="flex items-center gap-5">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Penilaian</h4>

                <button data-modal-target="add_nilai_modal" data-modal-toggle="add_nilai_modal" class="btn" type="button">
                    Input Data Nilai
                </button>
            </div>

            <?php generate_breadcrumb([['title' => 'Penilaian', 'filename' => 'penilaian.php']]); ?>
            <div class="relative overflow-x-auto mt-5">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">

                    <a>
                        <tr>
                            <th scope="col" class="px-6 py-3"></th>
                            <th scope="col" class="px-6 py-3">Judul Penilaian</th>
                            <th scope="col" class="px-6 py-3">Keterangan Penilaian</th>
                            <th scope="col" class="px-6 py-3">Instruktur</th>
                            <th scope="col" class="px-6 py-3">Kelas</th>
                            <th scope="col" class="px-6 py-3">Mapel</th>
                            <th scope="col" class="px-6 py-3">Tanggal</th>
                        </tr>
                    </a>
                       
                    </thead>
                    <tbody>
                        <?php foreach ($result as $key => $value) : ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th class="px-6 py-4 text-amber-500"><?= $key + 1 ?></th>
                                <td class="px-6 py-4"><?= $value['judul_penilaian'] ?></td>
                                <td class="px-6 py-4"><?= $value['keterangan_penilaian'] ?></td>
                                <td class="px-6 py-4"></td>
                                <td class="px-6 py-4"></td>
                                <td class="px-6 py-4"></td>
                                <td class="px-6 py-4"><?= $value['tgl_penilaian'] ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


<div id="add_nilai_modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
    <div class="relative w-full h-full max-w-7xl md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="px-6 py-6 lg:px-8">
                <form action="../../api/admin/penilaian.php" method="post">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between border-b rounded-t dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Input Data Nilai
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="add_nilai_modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="pt-6 flex gap-5 flex-col md:flex-row">
                        <div class="flex-1 flex flex-col" method="post">
                            <div class="mb-5">
                                <label for="judul" class="form-label text-secondary text-gray-400 dark:text-white">Judul Penilaian</label>
                                <input type="text" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="judul" name="judul" maxlength="50" required>
                            </div>
                            <div class="mb-5">
                                <label for="Ket" class="form-label text-secondary text-gray-400 dark:text-white">Keterangan Penilaian</label>
                                <textarea type="text" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="Ket" name="Ket" maxlength="50" required> </textarea>
                            </div>
                            <div class="mb-5">
                                <label for="Instruktur" class="form-label text-secondary text-gray-400 dark:text-white">Instruktur</label>
                                <input class="resize-none border rounded w-full py-1.5 border-gray-400 mt-1" name="Instruktur" id="Instruktur"  maxlength="50"></input>
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col" method="post">
                            <div class="mb-5">
                                <label for="Kelas" class="form-label text-secondary text-gray-400 dark:text-white">Kelas</label>
                                <input type="text" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="Kelas" name="Kelas" maxlength="30" required>
                            </div>
                            <div class="mb-5">
                                <label for="Mapel" class="form-label text-secondary text-gray-400 dark:text-white">Mapel</label>
                                <input type="text" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="Mapel" name="Mapel" maxlength="50" required>
                            </div>
                            <div class="mb-5">
                                <label for="Tanggal" class="form-label text-secondary text-gray-400 dark:text-white">Tanggal</label>
                                <input type="date" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="Tanggal" name="Tanggal" maxlength="50" required>
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
$result->free_result();
include_once('../template/footer.php') ?>