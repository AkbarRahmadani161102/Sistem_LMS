<?php

include_once '../util/db.php';

if (escape(isset($_POST['create_instruktur']))) {
    $id_instruktur = escape($_POST['id_instruktur']);
    $deskripsi = escape($_POST['deskripsi']);
    $rating = escape($_POST['rating']);

    $id_siswa = $_SESSION['user_id'];
    $sql = "SELECT * FROM kuesioner_instruktur k, instruktur i, siswa s WHERE k.id_siswa = s.id_siswa AND k.id_instruktur = i.id_instruktur AND s.id_siswa = '$id_siswa' AND k.id_instruktur = '$id_instruktur'";

    $feedback_created = $db->query($sql)->num_rows > 0;
    if ($feedback_created) {
        $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Gagal menambah', 'icon_color' => 'red', 'text' => 'Feedback dengan instruktur yang sama masih ada, hapus untuk membuat baru'];
        redirect("../../client/siswa/umpan_balik_instruktur.php");
    }

    if (strlen($deskripsi) > 0) {
        $sql = "INSERT INTO kuesioner_instruktur (id_siswa, id_instruktur, deskripsi, rating) VALUES('$id_siswa', '$id_instruktur',' $deskripsi', $rating)";
        $db->query($sql);
        $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Kuesioner baru berhasil diunggah', 'icon_color' => 'greenlight'];
        redirect("../../client/siswa/umpan_balik_instruktur.php");
    }
} else if (escape(isset($_POST['delete_instruktur']))) {
    try {
        $id_kuesioner = escape($_POST['delete_instruktur']);
        $sql = "DELETE FROM kuesioner_instruktur WHERE id_kuesioner = $id_kuesioner";
        $db->query($sql) or die($db->errno);
        $_SESSION['toast'] = ['icon' => 'success', 'title' => 'Kuesioner berhasil dihapus', 'icon_color' => 'greenlight'];
    } catch (\Throwable $th) {
        $_SESSION['toast'] = ['icon' => 'error', 'title' => 'Gagal menghapus', 'icon_color' => 'red', 'text' => 'Constraint integrity error'];
    }

    redirect("../../client/siswa/umpan_balik_instruktur.php");
}
