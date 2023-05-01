<?php

require '../../vendor/autoload.php';
include_once '../util/db.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function sync_tunggakan()
{
    global $db;
    $bulan_entity = date('n');
    $bulan = date('m');
    $hari = 10;
    $tahun = date('Y');

    $tanggal_trigger = "$tahun-$bulan-$hari";
    $tenggat = date('Y-m-t', strtotime($tanggal_trigger));

    $sql = "SELECT s.id_siswa, j.nama nama_jenjang, j.biaya_pendidikan FROM siswa s
    JOIN detail_kelas dk ON s.id_siswa = dk.id_siswa
    JOIN kelas k ON dk.id_kelas = k.id_kelas
    JOIN jenjang j ON k.id_jenjang = j.id_jenjang AND s.id_siswa NOT IN (SELECT id_siswa FROM tunggakan WHERE MONTH(tenggat_pembayaran) = '$bulan_entity')";

    $data_siswa = $db->query($sql);
    $data_siswa->fetch_assoc();
    foreach ($data_siswa as $siswa) {
        $biaya_pendidikan =  $siswa['biaya_pendidikan'];
        $id_siswa = $siswa['id_siswa'];
        $sql = "INSERT INTO tunggakan (id_siswa, tenggat_pembayaran, nominal, tgl_dibuat)
        VALUES('$id_siswa', '$tenggat', '$biaya_pendidikan', '$tenggat')";
        $db->query($sql);
    }
}

function sync_gaji_instruktur()
{
    global $db;
    $month = date('m');

    $sql = "SELECT dj.id_instruktur, SUM(biaya_per_pertemuan) nominal FROM detail_jadwal dj
    JOIN jadwal j ON dj.id_jadwal = j.id_jadwal
    JOIN kelas k ON j.id_kelas = j.id_kelas
    JOIN jenjang je ON k.id_jenjang = je.id_jenjang
    WHERE MONTH(tgl_pertemuan) = $month
    AND dj.status_kehadiran_instruktur = 'Hadir'
    AND dj.id_instruktur NOT IN (SELECT id_instruktur FROM gaji WHERE MONTH(tgl_pertemuan) = $month)
    GROUP BY dj.id_instruktur";

    $data_instruktur = $db->query($sql);

    foreach ($data_instruktur as $value) {
        $id_instruktur = $value['id_instruktur'];
        $nominal = $value['nominal'];
        $sql = "INSERT INTO gaji (id_instruktur, nominal) VALUES ('$id_instruktur', '$nominal')";
        $db->query($sql);
    }
}

sync_tunggakan();
sync_gaji_instruktur();

$nama_bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

$bulan = $_GET['bulan'];
$tahun = $_GET['tahun'];

$sql = "SELECT DISTINCT YEAR(tgl_pembayaran) tahun, MONTH(tgl_pembayaran) bulan FROM tunggakan WHERE status = 'Lunas' AND YEAR(tgl_pembayaran) = $tahun
UNION
SELECT DISTINCT YEAR(tgl_penerimaan) tahun, MONTH(tgl_penerimaan) bulan FROM gaji WHERE tgl_penerimaan IS NOT NULL AND YEAR(tgl_penerimaan) = $tahun";
$data_pertemuan = $db->query($sql);

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

define(
    'XLSX_STYLE_TABLE_HEADER',
    [
        'font' => [
            'size' => 12,
            'bold' => true
        ],
        'alignment' => [
            'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],
        'borders' => [
            'outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => '000'],
            ],
        ],
    ]
);

define(
    'XLSX_STYLE_TABLE_DATA',
    [
        'font' => [
            'size' => 11,
        ],
        'borders' => [
            'outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => '000'],
            ],
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['rgb' => '000']
            ]
        ],
    ]
);

$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()
    ->setCreator(XLSX_Author)
    ->setCompany(XLSX_Company)
    ->setCategory('Report')
    ->setLastModifiedBy(XLSX_Author)
    ->setTitle("JURNAL UMUM $tahun")
    ->setSubject('Yearly Report');

$data_pertemuan->fetch_assoc();

define('XLSX_DATA_STARTING_INDEX', 7);

function iterator($arr, string $title, string $tgl, string $mutasi)
{
    $date_grouper = '00/00/0000';
    global $sheet, $index, $mask;
    foreach ($arr as $value) {
        $current_loop_date = date_format(date_create($value[$tgl]), "d/m/Y");
        if ($date_grouper !== $current_loop_date) {
            $sheet->setCellValue("A$index", $current_loop_date);
            $date_grouper = $current_loop_date;
        }

        $nominal = $value['nominal'];
        $sbs_index = $index - 1;
        $sheet->setCellValue("B$index",  "$title " . $value['nama']);
        if ($mutasi === 'DEBIT') {
            $sheet->setCellValue("C$index", $nominal);
            $sheet->getStyle("C$index")->getNumberFormat()->setFormatCode($mask);
        } else if ($mutasi === 'KREDIT') {
            $sheet->setCellValue("D$index", $nominal);
            $sheet->getStyle("D$index")->getNumberFormat()->setFormatCode($mask);
        }
        $sheet->setCellValue("E$index", "=E$sbs_index+C$index-D$index");
        $sheet->getStyle("E$index")->getNumberFormat()->setFormatCode($mask);
        $sheet->getStyle("A$index:E$index")->applyFromArray(XLSX_STYLE_TABLE_DATA);
        $index++;
    }
}

foreach ($data_pertemuan as $pertemuan) {
    $header_bulan = strtoupper($nama_bulan[$pertemuan['bulan'] - 1]);

    $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet,  $header_bulan);
    $spreadsheet->addSheet($sheet);

    // HEADER
    $sheet->setCellValue('A1', 'JURNAL UMUM');
    $sheet->mergeCells('A1:E2');
    $sheet->getStyle('A1:E2')->applyFromArray(XLSX_STYLE_HEADER);

    // SUB HEADER
    $sheet->setCellValue('A3', "PER $header_bulan $tahun");
    $sheet->mergeCells('A3:E3');
    $sheet->getStyle('A3:E3')->applyFromArray(XLSX_STYLE_SUBHEADER);

    // CONTENT 

    //// Header
    $sheet->setCellValue('A5', 'TGL');
    $sheet->setCellValue('B5', 'KETERANGAN');
    $sheet->setCellValue('C5', 'MUTASI');
    $sheet->setCellValue('C6', 'DEBIT');
    $sheet->setCellValue('D6', 'KREDIT');
    $sheet->setCellValue('E5', 'SALDO');

    $sheet->mergeCells('A5:A6');
    $sheet->mergeCells('B5:B6');
    $sheet->mergeCells('C5:D5');
    $sheet->mergeCells('E5:E6');

    $sheet->getStyle('A5:A6')->applyFromArray(XLSX_STYLE_TABLE_HEADER);
    $sheet->getStyle('B5:B6')->applyFromArray(XLSX_STYLE_TABLE_HEADER);
    $sheet->getStyle('C5:D5')->applyFromArray(XLSX_STYLE_TABLE_HEADER);
    $sheet->getStyle('C6')->applyFromArray(XLSX_STYLE_TABLE_HEADER);
    $sheet->getStyle('D6')->applyFromArray(XLSX_STYLE_TABLE_HEADER);
    $sheet->getStyle('E5:E6')->applyFromArray(XLSX_STYLE_TABLE_HEADER);

    $sheet->getColumnDimension('A')->setWidth(15);
    $sheet->getColumnDimension('B')->setWidth(30);
    $sheet->getColumnDimension('C')->setWidth(15);
    $sheet->getColumnDimension('D')->setWidth(15);
    $sheet->getColumnDimension('E')->setWidth(15);

    //// Data
    $bulan_pertemuan = $pertemuan['bulan'];
    $tahun_pertemuan = $pertemuan['tahun'];

    $sql = "SELECT t.*, SUM(t.nominal) nominal, j.nama nama FROM tunggakan t
    JOIN detail_kelas dk ON t.id_siswa = dk.id_siswa
    JOIN kelas k ON dk.id_kelas = k.id_kelas
    JOIN jenjang j ON k.id_jenjang = j.id_jenjang
    WHERE t.status = 'Lunas'
    AND MONTH(t.tgl_pembayaran) = $bulan_pertemuan
    AND YEAR(t.tgl_pembayaran) = $tahun_pertemuan
    GROUP BY tgl_pembayaran, nama
    ORDER BY tgl_pembayaran";

    $data_tunggakan = $db->query($sql);
    $data_tunggakan->fetch_assoc();

    $sql = "SELECT *, i.nama nama FROM gaji g
    JOIN instruktur i ON g.id_instruktur = i.id_instruktur
    WHERE g.tgl_penerimaan IS NOT NULL
    AND MONTH(g.tgl_penerimaan) = $bulan_pertemuan
    AND YEAR(g.tgl_penerimaan) = $tahun_pertemuan";
    $data_gaji_instruktur = $db->query($sql);
    $data_gaji_instruktur->fetch_assoc();

    $index = XLSX_DATA_STARTING_INDEX;
    $mask = 'Rp #,##0';

    // Tunggakan
    iterator($data_tunggakan, 'SPP', 'tgl_pembayaran', 'DEBIT');

    // Gaji Admin
    $sbs_index = $index - 1;

    $sheet->setCellValue("B$index", 'GAJI Admin');
    $sheet->setCellValue("D$index", '10000');
    $sheet->setCellValue("E$index", "=E$sbs_index+C$index-D$index");
    $sheet->getStyle("D$index")->getNumberFormat()->setFormatCode($mask);
    $sheet->getStyle("E$index")->getNumberFormat()->setFormatCode($mask);
    $sheet->getStyle("A$index:E$index")->applyFromArray(XLSX_STYLE_TABLE_DATA);
    $index++;

    // Gaji Instruktur
    iterator($data_gaji_instruktur, 'GAJI', 'tgl_penerimaan', 'KREDIT');
    
    $sheet->setCellValue("E$index", "=E$sbs_index");
    $sheet->setCellValue("F$index", "SALDO");
    $sheet->getStyle("E$index")->getFont()->setBold(true);
    $sheet->getStyle("E$index")->getNumberFormat()->setFormatCode($mask);
    $sheet->getStyle("F$index")->getFont()->setBold(true);
    $sheet->getStyle("A$index:E$index")->applyFromArray(XLSX_STYLE_TABLE_DATA);
}

$spreadsheet->removeSheetByIndex(0);

header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=JURNAL UMUM $tahun.xlsx");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
