<?php
require '../../vendor/autoload.php';
include_once '../util/db.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Ramsey\Uuid\Uuid;

$current_date = date('Y-m-d');

if (isset($_POST['create'])) {
    $id_siswa = Uuid::uuid4()->toString();
    $nama = escape($_POST['nama']);
    $no_telp = escape($_POST['no_telp']);
    $alamat = escape($_POST['alamat']);
    $email = escape($_POST['email']);
    $password = md5(escape($_POST['password']));

    $is_number = preg_match("/^[0-9]*$/", $no_telp) === 1;

    function is_email_available()
    {
        global $db, $email;
        $sql = "SELECT COUNT(*) used_email FROM siswa WHERE email = '$email'";
        $used_email = $db->query($sql);
        $used_email = $used_email->fetch_assoc();
        return $used_email['used_email'] === '0';
    }

    if ($is_number) {
        if (is_email_available()) {
            $sql = "INSERT INTO siswa (id_siswa, nama, no_telp, alamat, email, password) VALUES ('$id_siswa', '$nama', '$no_telp', '$alamat', '$email' , '$password')";
            $db->query($sql);
            push_toast('Data siswa berhasil ditambahkan');
        } else {
            push_toast('Gagal menambah', 'error', 'Email sudah ada');
        }
    } else {
        push_toast('Gagal menambah', 'error', 'Password mengandung karakter');
    }
    redirect("../../client/admin/siswa.php");
}
if (isset($_POST['update'])) {
    $id_siswa = escape($_POST['update']);
    $nama = escape($_POST['nama']);
    $no_telp = escape($_POST['no_telp']);
    $alamat = escape($_POST['alamat']);
    $email = escape($_POST['email']);
    $password = escape($_POST['password']);
    $status = escape($_POST['status']);
    $is_number = preg_match("/^[0-9]*$/", $no_telp) === 1;

    function is_email_changed()
    {
        global $db, $email;
        $sql = "SELECT COUNT(*) used_email FROM siswa WHERE email = '$email'";
        $used_email = $db->query($sql);
        $used_email = $used_email->fetch_assoc();
        return $used_email['used_email'] === '0';
    }

    function is_password_changed()
    {
        global $db, $password;
        $sql = "SELECT COUNT(*) used_password FROM siswa WHERE password = '$password'";
        $used_password = $db->query($sql);
        $used_password = $used_password->fetch_assoc();
        return $used_password['used_password'] === '0';
    }

    if ($is_number) {
        $ext_sql = '';

        if (is_email_changed()) {
            $ext_sql .= ", email = '$email'";
        }
        if (is_password_changed()) {
            $password = md5($password);
            $ext_sql .= ", password = '$password'";
        }

        $sql = "UPDATE siswa SET nama = '$nama', no_telp = '$no_telp', alamat = '$alamat', status = '$status', tgl_diubah = '$current_date' $ext_sql WHERE id_siswa = '$id_siswa'";
        $db->query($sql);

        push_toast('Data siswa berhasil diubah');
    } else {
        push_toast('Gagal mengubah', 'error', 'Field nomor telp mengandung karakter');
    }
    redirect("../../client/admin/siswa.php");
}
if (isset($_POST['delete'])) {
    try {
        $id_siswa = escape($_POST['delete']);

        $sql = "DELETE FROM tunggakan WHERE id_siswa = '$id_siswa'";
        $db->query($sql);

        $sql = "DELETE FROM detail_penilaian WHERE id_siswa = '$id_siswa'";
        $db->query($sql);

        $sql = "DELETE FROM pengajuan WHERE id_siswa = '$id_siswa'";
        $db->query($sql);

        $sql = "DELETE FROM kuesioner_instruktur WHERE id_siswa = '$id_siswa'";
        $db->query($sql);

        $sql = "DELETE FROM notifikasi_siswa WHERE id_siswa = '$id_siswa'";
        $db->query($sql);

        $sql = "DELETE FROM detail_penilaian WHERE id_siswa = '$id_siswa'";
        $db->query($sql);

        $sql = "DELETE FROM absensi_siswa WHERE id_siswa = '$id_siswa'";
        $db->query($sql);

        $sql = "UPDATE kelas SET id_ketua_kelas = NULL WHERE id_ketua_kelas = '$id_siswa'";
        $db->query($sql);

        $sql = "DELETE FROM detail_kelas WHERE id_siswa = '$id_siswa'";
        $db->query($sql);

        $sql = "DELETE FROM siswa WHERE id_siswa = '$id_siswa'";
        $db->query($sql);

        push_toast('Data siswa berhasil dihapus');

        if ($db->error) {
            push_toast('Gagal menghapus', 'error', 'Constraint integrity error');
        }
    } catch (\Throwable $th) {
        push_toast('Gagal menghapus', 'error', 'Constraint integrity error');
    }
    redirect("../../client/admin/siswa.php");
}

if (isset($_GET['file_import_example'])) {
    define(
        'XLSX_STYLE_HEADER',
        [
            'font' => [
                'size' => 18,
                'bold' => true
            ],
            'alignment' => [
                'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ]
        ]
    );

    define(
        'XLSX_STYLE_SUBHEADER',
        [
            'font' => [
                'size' => 12,
                'bold' => true
            ],
            'alignment' => [
                'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ]
        ]
    );


    $spreadsheet = new Spreadsheet();
    $spreadsheet->getProperties()
        ->setCreator(XLSX_AUTHOR)
        ->setCompany(XLSX_COMPANY)
        ->setCategory('Import File')
        ->setLastModifiedBy(XLSX_AUTHOR)
        ->setTitle("File Import Data Siswa")
        ->setSubject('Yearly Report');

    // $spreadsheet->getDefaultStyle()->getNumberFormat()->setFormatCode('#');
    $spreadsheet->getActiveSheet()->getStyle('B')->getNumberFormat()
    ->setFormatCode('@');

    $sheet = $spreadsheet->getActiveSheet();
    // HEADER
    $sheet->setCellValue('A1', 'DATA PENDAFTAR SISWA');
    $sheet->mergeCells('A1:E2');
    $sheet->getStyle('A1:E2')->applyFromArray(XLSX_STYLE_HEADER);

    // SUB HEADER
    $tahun = date('Y');
    $bulan = BULAN[date('m') - 1];
    $sheet->setCellValue('A3', "Tahun $tahun Bulan $bulan");
    $sheet->mergeCells('A3:E3');
    $sheet->getStyle('A3:E3')->applyFromArray(XLSX_STYLE_SUBHEADER);

    $sheet->setCellValue('A5', "Nama");
    $sheet->setCellValue('B5', "Nomor Telepon");
    $sheet->setCellValue('C5', "Alamat");
    $sheet->setCellValue('D5', "Email");
    $sheet->setCellValue('E5', "Password");

    $sheet->getColumnDimension('A')->setWidth(15);
    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->getColumnDimension('C')->setWidth(15);
    $sheet->getColumnDimension('D')->setWidth(15);
    $sheet->getColumnDimension('E')->setWidth(15);

    $sheet->getStyle('A5')->getFont()->setBold(true);
    $sheet->getStyle('B5')->getFont()->setBold(true);
    $sheet->getStyle('C5')->getFont()->setBold(true);
    $sheet->getStyle('D5')->getFont()->setBold(true);
    $sheet->getStyle('E5')->getFont()->setBold(true);

    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment;filename=File Import Data Pendaftar.xlsx");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
}

if (isset($_POST['file_import'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);

    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if ($imageFileType !== 'xlsx')
        push_toast('Gagal import data siswa', 'error', 'Pastikan format file yang diungah bertipe .xlsx');
    else {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);

        $uploaded_file = $_FILES['file']['tmp_name'];
        $data_siswa = $reader->load($uploaded_file)->getActiveSheet()->toArray();

        foreach ($data_siswa as $index => $siswa) {
            if ($index >= 5) {
                $id_siswa = Uuid::uuid4()->toString();
                $nama = $siswa[0];
                $no_telp =  $siswa[1];
                $alamat = $siswa[2];
                $status = 'Aktif';
                $email = $siswa[3];
                $password = md5($siswa[4]);
                $sql = "INSERT INTO siswa (id_siswa, nama, no_telp, alamat, status, email, password) VALUES('$id_siswa', '$nama', '$no_telp', '$alamat', '$status', '$email', '$password')";
                $db->query($sql);
            }
        }
        push_toast('Data siswa berhasil di import');
    }
    redirect("../../client/admin/siswa.php");
}
if (isset($_POST['bulk_delete'])) {
    $data_siswa = $_POST['bulk_delete'];
    try {
        foreach ($data_siswa as $id_siswa) {
            $sql = "DELETE FROM tunggakan WHERE id_siswa = '$id_siswa'";
            $db->query($sql);

            $sql = "DELETE FROM detail_penilaian WHERE id_siswa = '$id_siswa'";
            $db->query($sql);

            $sql = "DELETE FROM pengajuan WHERE id_siswa = '$id_siswa'";
            $db->query($sql);

            $sql = "DELETE FROM kuesioner_instruktur WHERE id_siswa = '$id_siswa'";
            $db->query($sql);

            $sql = "DELETE FROM notifikasi_siswa WHERE id_siswa = '$id_siswa'";
            $db->query($sql);

            $sql = "DELETE FROM detail_penilaian WHERE id_siswa = '$id_siswa'";
            $db->query($sql);

            $sql = "DELETE FROM absensi_siswa WHERE id_siswa = '$id_siswa'";
            $db->query($sql);

            $sql = "UPDATE kelas SET id_ketua_kelas = NULL WHERE id_ketua_kelas = '$id_siswa'";
            $db->query($sql);

            $sql = "DELETE FROM detail_kelas WHERE id_siswa = '$id_siswa'";
            $db->query($sql);

            $sql = "DELETE FROM siswa WHERE id_siswa = '$id_siswa'";
            $db->query($sql);
        }
        push_toast('Data Siswa Dihapus');
    } catch (\Throwable $th) {
        push_toast('Data Siswa Gagal Dihapus', 'error','Constraint integrity error');
    }
    redirect("../../client/admin/siswa.php");
}
redirect("../../client/admin/siswa.php");
