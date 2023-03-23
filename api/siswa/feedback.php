<?php

include_once '../util/db.php';

if (escape(isset($_POST['create']))) {
    $id_instruktur = escape($_POST['id_instruktur']);
    $deskripsi = escape($_POST['deskripsi']);
    $rating = escape($_POST['rating']);

    $id_siswa = $_SESSION['user_id'];
    $sql = "SELECT * FROM kuesioner_instruktur k, instruktur i, siswa s WHERE k.id_siswa = s.id_siswa AND k.id_instruktur = i.id_instruktur AND s.id_siswa = '$id_siswa' AND k.id_instruktur = '$id_instruktur'";

    $feedback_created = $db->query($sql)->num_rows > 0;
    if ($feedback_created) {
        echo ("Failed to create feedback");
        die(redirect("../../client/siswa/feedback.php"));
    }

    if (strlen($deskripsi) > 0) {
        $sql = "INSERT INTO kuesioner_instruktur (id_siswa, id_instruktur, deskripsi, rating) VALUES('$id_siswa', '$id_instruktur',' $deskripsi', $rating)";
        $db->query($sql);
        redirect("../../client/siswa/feedback.php");
    }
} else if (escape(isset($_POST['delete']))) {
    $id_kuesioner = escape($_POST['delete']);
    $sql = "DELETE FROM kuesioner_instruktur WHERE id_kuesioner = $id_kuesioner";
    $db->query($sql) or die($db->errno);
    redirect("../../client/siswa/feedback.php");
}
