<?php
include_once('../template/header.php');
include_once('../../api/auth/access_control.php');
user_access('siswa');

$id_siswa = $_SESSION['user_id'];


if (isset($_GET['pergantian_instruktur'])) {
    $id_detail_jadwal = $_GET['pergantian_instruktur'];
    $sql = "SELECT *, i.nama nama_instruktur, m.nama nama_mapel, k.nama nama_kelas, k.id_ketua_kelas FROM detail_jadwal dj
    JOIN jadwal j ON dj.id_jadwal = j.id_jadwal
    JOIN mapel m ON j.id_mapel = m.id_mapel
    JOIN kelas k ON j.id_kelas = k.id_kelas
    JOIN instruktur i ON dj.id_instruktur = i.id_instruktur
    WHERE dj.id_detail_jadwal = '$id_detail_jadwal'";

    $data_detail_jadwal = $db->query($sql) or die($db->error);
    $data_detail_jadwal = $data_detail_jadwal->fetch_assoc();

    $ketua_kelas =  $data_detail_jadwal['id_ketua_kelas'] === $id_siswa;

    $id_detail_jadwal = $data_detail_jadwal['id_detail_jadwal'];
    $id_mapel = $data_detail_jadwal['id_mapel'];
    $id_instruktur = $data_detail_jadwal['id_instruktur'];
    $nama_instruktur = $data_detail_jadwal['nama_instruktur'];
    $nama_kelas = $data_detail_jadwal['nama_kelas'];
    $nama_mapel = $data_detail_jadwal['nama_mapel'];
    $tgl_pertemuan = $data_detail_jadwal['tgl_pertemuan'];
    $sql = "SELECT * FROM detail_mapel dm
    JOIN instruktur i ON dm.id_instruktur = i.id_instruktur
    WHERE dm.id_mapel = '$id_mapel'
    AND dm.id_instruktur != '$id_instruktur'";

    $data_instruktur = $db->query($sql) or die($db->error);
    $data_instruktur->fetch_assoc();
} else {
    $sql = "SELECT *, m.nama nama_mapel, i.nama nama_instruktur, abs.status status_kehadiran FROM detail_jadwal dj
    JOIN jadwal j ON dj.id_jadwal = j.id_jadwal
    JOIN mapel m ON j.id_mapel = m.id_mapel
    JOIN instruktur i ON j.id_instruktur = i.id_instruktur
    JOIN kelas k ON j.id_kelas = k.id_kelas
    LEFT JOIN absensi_siswa abs ON dj.id_detail_jadwal = abs.id_detail_jadwal
    WHERE j.id_kelas = (SELECT id_kelas FROM detail_kelas WHERE id_siswa = '$id_siswa')
    GROUP BY dj.id_detail_jadwal
    ORDER BY dj.tgl_pertemuan DESC";
    $data_pertemuan = $db->query($sql) or die($db->error);
    $data_pertemuan->fetch_assoc();

    $ketua_kelas =  $data_pertemuan->fetch_assoc()['id_ketua_kelas'] === $id_siswa;
}
?>

<div class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php'; ?>
            <?php if (isset($_GET['pergantian_instruktur'])) : ?>
                <?php generate_breadcrumb([['title' => 'Pertemuan', 'filename' => 'pertemuan.php'], ['title' => 'Pergantian Instruktur', 'filename' => '#']]); ?>
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Pergantian Instruktur</h4>

                <?php if ($ketua_kelas) : ?>
                    <div class="flex flex-1 flex-wrap text-gray-800 dark:text-white rounded-lg bg-gray-200 dark:bg-gray-700 p-5">
                        <div class="flex w-fit flex-col space-y-4 border-r-2 pr-5">
                            <div class="flex flex-col">
                                <h6 class="font-semibold">Mapel:</h6>
                                <p><?= $nama_mapel ?></p>
                            </div>
                            <div class="flex flex-col">
                                <h6 class="font-semibold">Instruktur:</h6>
                                <p><?= $nama_instruktur ?></p>
                            </div>
                        </div>
                        <div class="flex flex-1 flex-col space-y-4">
                            <form action="../../api/siswa/pertemuan.php" method="post" class="form pl-5 flex flex-1 flex-col items-start justify-between">
                                <label for="instruktur">Pilih Instruktur Pengganti</label>
                                <select class="selectize input" name="instruktur" id="instruktur" required>
                                    <?php foreach ($data_instruktur as $instruktur) : ?>
                                        <option value="<?= $instruktur['id_instruktur'] ?>|<?= $instruktur['nama'] ?>"><?= $instruktur['nama'] ?></option>
                                    <?php endforeach ?>
                                </select>

                                <input type="hidden" name="id_siswa" value="<?= $id_siswa ?>">
                                <input type="hidden" name="kelas" value="<?= $nama_kelas ?>">
                                <input type="hidden" name="mapel" value="<?= $nama_mapel ?>">
                                <input type="hidden" name="tgl_pertemuan" value="<?= $tgl_pertemuan ?>">
                                <button name="ajukan_instruktur" type="submit" class="btn" value="<?= $id_detail_jadwal ?>">Ajukan Instruktur Pengganti</button>
                            </form>
                        </div>
                    </div>
                <?php endif ?>
            <?php else : ?>
                <?php generate_breadcrumb([['title' => 'Pertemuan', 'filename' => 'pertemuan.php']]); ?>
                <h4 class="my-7 font-semibold text-gray-800 dark:text-white">Pertemuan Kelas</h4>
                <div class="relative overflow-x-auto">
                    <table class="datatable w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3"></th>
                                <th scope="col" class="px-6 py-3">Mata Pelajaran</th>
                                <th scope="col" class="px-6 py-3">Instruktur</th>
                                <th scope="col" class="px-6 py-3">Tanggal</th>
                                <th scope="col" class="px-6 py-3">Jam Mulai</th>
                                <th scope="col" class="px-6 py-3">Jam Selesai</th>
                                <th scope="col" class="px-6 py-3">Status Kehadiran</th>
                                <th scope="col" class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data_pertemuan as $key => $pertemuan) : ?>
                                <tr class="border-b bg-white dark:bg-gray-800 dark:border-gray-700">
                                    <th class="px-6 py-4 text-amber-500"><?= $key + 1 ?></th>
                                    <td class="px-6 py-4"><?= $pertemuan['nama_mapel'] ?></td>
                                    <td class="px-6 py-4"><?= $pertemuan['nama_instruktur'] ?></td>
                                    <td class="px-6 py-4"><?= $pertemuan['tgl_pertemuan'] ?></td>
                                    <td class="px-6 py-4"><?= $pertemuan['jam_mulai'] ?></td>
                                    <td class="px-6 py-4"><?= $pertemuan['jam_selesai'] ?></td>
                                    <td class="px-6 py-4">
                                        <?php if ($pertemuan['status_kehadiran'] === 'H') : ?>
                                            <span class="text-green-500">Hadir</span>
                                        <?php endif ?>
                                        <?php if ($pertemuan['status_kehadiran'] === 'I') : ?>
                                            <span class="text-amber-500">Izin</span>
                                        <?php endif ?>
                                        <?php if ($pertemuan['status_kehadiran'] === 'A') : ?>
                                            <span class="text-red-500">Tidak ada keterangan</span>
                                        <?php endif ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if ($pertemuan['tgl_pertemuan'] <= date('Y-m-d') || $pertemuan['status_kehadiran_instruktur'] === 'Hadir') : ?>
                                            <p class="text-red-500">Pertemuan telah usai</p>
                                        <?php else : ?>
                                            <?php if ($ketua_kelas) : ?>
                                                <?php if ($pertemuan['status_kehadiran_instruktur'] !== "Proses Pergantian") : ?>
                                                    <a href="?pergantian_instruktur=<?= $pertemuan['id_detail_jadwal'] ?>" class="btn block w-fit btn--outline-amber">Ajukan Pergantian Instruktur</a>
                                                <?php else : ?>
                                                    <p class="text-amber-500">Pergantian instruktur sedang dalam proses</p>
                                                <?php endif ?>
                                            <?php endif ?>
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