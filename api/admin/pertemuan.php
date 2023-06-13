<?php
include_once '../util/db.php';

if (isset($_POST['sync'])) {

    $array_tgl = [
        [], // Senin
        [], // Selasa
        [], // Rabu
        [], // Kamis
        [], // Jumat
        [], // Sabtu
        [], // Minggu
    ];

    $month = date('n');
    $year = date('Y');

    // Menghitung jumlah hari dalam bulan yang dipilih
    $num_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    // Mengulang sebanyak jumlah hari dalam bulan
    for ($day = 1; $day <= $num_days; $day++) {

        // Mendapatkan data tanggal dengan fungsi mktime()
        $date = mktime(0, 0, 0, $month, $day, $year);
        $date_number = date('N', $date);
        $date_string = date('Y-m-d', $date);

        // Menambahkan tanggal pada array sesuai urutan harinya
        array_push($array_tgl[$date_number - 1], $date_string);
    }

    foreach (HARI as $index_hari => $hari) {
        $sql = "SET lc_time_names = 'id_ID';
        SELECT * FROM jadwal 
        WHERE id_jadwal 
        NOT IN (SELECT id_jadwal FROM detail_jadwal WHERE YEAR(tgl_pertemuan) = $year AND MONTH(tgl_pertemuan) = $month AND DAYNAME(tgl_pertemuan) = '$hari') 
        AND id_instruktur IS NOT NULL AND hari = '$hari' ORDER BY jam_mulai";
        $db->multi_query($sql);
        $db->next_result();

        if ($result = $db->store_result()) {
            while ($row = $result->fetch_assoc()) {

                $id_jadwal = $row['id_jadwal'];
                $id_instruktur = $row['id_instruktur'];

                foreach ($array_tgl[$index_hari] as $tgl_pertemuan) {
                    $sql = "INSERT INTO detail_jadwal (id_jadwal, id_instruktur, tgl_pertemuan) VALUES ('$id_jadwal', '$id_instruktur', '$tgl_pertemuan')";
                    $db->query($sql);
                }

                $msg = "Pertemuan bulan $month tahun $year telah ditambahkan, silahkan konfirmasi kehadiran anda";

                $sql = "SELECT COUNT(*) jumlah_notifikasi FROM notifikasi_instruktur WHERE deskripsi = '$msg' AND MONTH(tgl_dibuat) = $month AND id_instruktur = '$id_instruktur'";
                $data_notifikasi = $db->query($sql);
                $data_notifikasi = $data_notifikasi->fetch_assoc();
                if ($data_notifikasi['jumlah_notifikasi'] <= 0) {
                    $sql = "INSERT INTO notifikasi_instruktur (id_instruktur, deskripsi) VALUES('$id_instruktur', '$msg')";
                    $db->query($sql);
                }
            }
        }
    }

    push_toast('Data berhasil disinkronkan');
}
if (isset($_POST['reassign_instruktur'])) {
    $id_detail_jadwal = escape($_POST['reassign_instruktur']);
    $id_instruktur_baru = escape($_POST['instruktur']);

    $sql = "SELECT *, dj.id_instruktur id_instruktur_lama, i.nama nama_instruktur_lama, k.nama nama_kelas_lama FROM detail_jadwal dj 
    JOIN jadwal j ON dj.id_jadwal = j.id_jadwal 
    JOIN instruktur i ON dj.id_instruktur = i.id_instruktur 
    JOIN kelas k ON j.id_kelas = k.id_kelas WHERE dj.id_detail_jadwal = '$id_detail_jadwal'";
    $data_detail_jadwal_lama = $db->query($sql);
    $data_detail_jadwal_lama = $data_detail_jadwal_lama->fetch_assoc();

    $id_instruktur_lama = $data_detail_jadwal_lama['id_instruktur'];
    $nama_instruktur_lama = $data_detail_jadwal_lama['nama_instruktur_lama'];
    $nama_kelas_lama = $data_detail_jadwal_lama['nama_kelas_lama'];
    $jam_mulai = $data_detail_jadwal_lama['jam_mulai'];
    $tgl_pertemuan = $data_detail_jadwal_lama['tgl_pertemuan'];

    function is_instructor_exist()
    {
        global $db, $jam_mulai, $tgl_pertemuan, $id_instruktur_baru;
        $sql = "SELECT * FROM jadwal j 
                JOIN detail_jadwal dj ON j.id_jadwal = dj.id_jadwal
                WHERE jam_mulai = '$jam_mulai'
                AND tgl_pertemuan = '$tgl_pertemuan'
                AND dj.id_instruktur = '$id_instruktur_baru'";
        $data_instruktur = $db->query($sql);
        $data_instruktur->fetch_assoc();
        return $data_instruktur->num_rows > 0;
    }

    function get_nama_instruktur_baru()
    {
        global $db, $id_instruktur_baru;
        $sql = "SELECT nama nama_instruktur_baru FROM instruktur WHERE id_instruktur = '$id_instruktur_baru'";
        $data_instruktur_baru = $db->query($sql);
        $data_instruktur_baru = $data_instruktur_baru->fetch_assoc();
        return $data_instruktur_baru['nama_instruktur_baru'];
    }

    if (!is_instructor_exist()) {
        $nama_instruktur_baru = get_nama_instruktur_baru();

        $sql = "UPDATE detail_jadwal SET id_instruktur = '$id_instruktur_baru' WHERE id_detail_jadwal = '$id_detail_jadwal'";
        $db->query($sql);

        $sql = "INSERT INTO notifikasi_instruktur (id_instruktur, deskripsi) VALUES('$id_instruktur_lama', 'Anda telah digantikan oleh $nama_instruktur_baru di kelas $nama_kelas_lama pada tanggal $tgl_pertemuan')";
        $db->query($sql);

        $sql = "INSERT INTO notifikasi_instruktur (id_instruktur, deskripsi) VALUES('$id_instruktur_baru', 'Anda menggantikan $nama_instruktur_lama di kelas $nama_kelas_lama pada tanggal $tgl_pertemuan')";
        $db->query($sql);

        if (isset($_POST['pengajuan'])) {
            $id_pengajuan = escape($_POST['pengajuan']);
            $sql = "UPDATE pengajuan SET id_detail_jadwal = NULL, status = 'Selesai' WHERE id_pengajuan = '$id_pengajuan'";
            $db->query($sql);

            $sql = "UPDATE detail_jadwal SET status_kehadiran_instruktur = NULL WHERE id_detail_jadwal = '$id_detail_jadwal'";
            $db->query($sql);
        }

        push_toast('Instruktur berhasil ditetapkan');
    } else {
        push_toast('Gagal menetapkan instruktur', 'error', 'Instruktur yang bersangkutan sudah ada di jadwal lain di hari dan jam yang sama');
    }
}
if (isset($_POST['delete'])) {
    $id_detail_jadwal = escape($_POST['delete']);
    $sql = "DELETE FROM detail_jadwal WHERE id_detail_jadwal = '$id_detail_jadwal'";
    $db->query($sql);
    push_toast('Pertemuan Dihapus');
}
if (isset($_POST['bulk_delete'])) {
    $data_pertemuan = $_POST['delete_pertemuan'];
    try {
        foreach ($data_pertemuan as $id_detail_jadwal) {
            $sql = "DELETE FROM detail_jadwal WHERE id_detail_jadwal = '$id_detail_jadwal'";
            $db->query($sql);
        }
        push_toast('Pertemuan Dihapus');
    } catch (\Throwable $th) {
        push_toast('Pertemuan Gagal Dihapus', 'error', 'Constraint integrity error');
    }
}
redirect('../../client/admin/pertemuan.php');
