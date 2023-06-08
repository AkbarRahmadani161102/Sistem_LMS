<?php
include_once('../template/header.php');
user_access('siswa');

$id_siswa = $_SESSION['user_id'];
$sql = "SELECT k.*, i.* FROM kuesioner_instruktur k, instruktur i, siswa s WHERE k.id_siswa = s.id_siswa AND k.id_instruktur = i.id_instruktur AND s.id_siswa = '$id_siswa'";
$data_umpan_balik = $db->query($sql);

$sql = "SELECT * FROM instruktur";
$data_instruktur = $db->query($sql) or die($db->error);
$data_instruktur->fetch_assoc();
?>

<div class="dashboard__main">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="dashboard__content">
        <div class="dashboard__subcontent">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Umpan Balik Instruktur', 'filename' => 'umpan_balik_instruktur.php']]);
            ?>

            <div class="flex gap-2 items-center">
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Umpan Balik Instruktur</h4>
                <button class="btn" data-modal-target="add-feedback-modal" data-modal-toggle="add-feedback-modal">Isi Feedback</button>
            </div>

            <div class="table__container">
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Instruktur</th>
                            <th>Pesan Anda</th>
                            <th>Rating</th>
                            <th>Tanggal Diunggah</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $data_umpan_balik->fetch_assoc()) : ?>
                            <tr>
                                <th></th>
                                <td><?= $row['nama'] ?></td>
                                <td><?= $row['deskripsi'] ?></td>
                                <td>
                                    <?php for ($i = 0; $i < $row['rating']; $i++) : ?>
                                        <i class="ri-star-fill text-amber-500"></i>
                                    <?php endfor ?>
                                <td><?= $row['tgl_dibuat'] ?></td>
                                <td>
                                    <form action="../../api/siswa/umpan_balik.php" method="post">
                                        <button type="submit" name="delete_instruktur" value="<?= $row['id_kuesioner'] ?>" class="btn btn--outline-red"><i class="ri-delete-bin-line"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="add-feedback-modal" tabindex="-1" aria-hidden="true" class="modal">
    <div class="modal__backdrop">
        <div class="modal__content">
            <!-- Modal header -->
            <div class="modal__header">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Tambah Feedback
                </h3>
                <button type="button" class="modal__close" data-modal-hide="add-feedback-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <form class="form" action="../../api/siswa/umpan_balik.php" method="post">
                <!-- Modal body -->
                <div class="modal__body">
                    <label class="block" for="id_instruktur">Instruktur</label>
                    <select class="selectize input" id="id_instruktur" name="id_instruktur" required>
                        <?php foreach ($data_instruktur as $instruktur) : ?>
                            <option value="<?= $instruktur['id_instruktur'] ?>"><?= $instruktur['nama'] ?></option>
                        <?php endforeach ?>
                    </select>

                    <label for="deskripsi" class="block">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" class="input" placeholder="Write your thoughts here..."></textarea>

                    <label for="rating" class="block mb-2 text-sm font-medium text-gray-900 ">Rating</label>
                    <div class="flex items-center gap-2 text-gray-900 dark:text-white">
                        <span>1</span>
                        <input id="rating" type="range" name="rating" min="1" max="5" class="input">
                        <span>5</span>
                    </div>

                </div>
                <!-- Modal footer -->
                <div class="modal__footer">
                    <button data-modal-hide="add-feedback-modal" name="create_instruktur" type="submit" class="btn btn--blue">Submit</button>
                    <button data-modal-hide="add-feedback-modal" type="button" class="btn">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>