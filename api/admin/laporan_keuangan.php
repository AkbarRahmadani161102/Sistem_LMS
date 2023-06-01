<?php

require '../../vendor/autoload.php';
include_once '../util/db.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Calculation\Calculation;

define('IDR_NUMBERING_FORMAT', 'Rp #,##0');
if (isset($_GET['jurnal_umum'])) {
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

    $bulan = escape($_GET['bulan']);
    $tahun = escape($_GET['tahun']);

    $sql = "SELECT DISTINCT YEAR(tgl_pembayaran) tahun, MONTH(tgl_pembayaran) bulan FROM tunggakan WHERE status = 'Lunas' AND YEAR(tgl_pembayaran) = $tahun
    UNION
    SELECT DISTINCT YEAR(tgl_penerimaan) tahun, MONTH(tgl_penerimaan) bulan FROM gaji WHERE tgl_penerimaan IS NOT NULL AND YEAR(tgl_penerimaan) = $tahun";

    $data_pertemuan = $db->query($sql);

    define(
        'XLSX_JURNAL_UMUM_STYLE_HEADER',
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
        'XLSX_JURNAL_UMUM_STYLE_SUBHEADER',
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
        'XLSX_JURNAL_UMUM_STYLE_TABLE_HEADER',
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
        'XLSX_JURNAL_UMUM_STYLE_TABLE_DATA',
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
        ->setCreator(XLSX_AUTHOR)
        ->setCompany(XLSX_COMPANY)
        ->setCategory('Report')
        ->setLastModifiedBy(XLSX_AUTHOR)
        ->setTitle("JURNAL UMUM $tahun")
        ->setSubject('Yearly Report');

    $data_pertemuan->fetch_assoc();

    /**
     * Fungsi mapping 
     * @param Array $arr Array data mutasi
     * @param String $title Prefix keterangan/Key value (Jika $catatan_mutasi bernilai TRUE)
     * @param String|Null $tgl (Opsional) Key yang digunakan untuk mengakses tanggal mutasi
     * @param String $mutasi Tipe mutasi (Opsi: 'Debit', 'Kredit')
     * @param Boolean|Null $catatan_mutasi (Opsional) Key yang digunakan untuk mengakses keterangan (digunakan untuk catatan mutasi)
     */
    function iterator($arr, $title, $tgl, $mutasi, $catatan_mutasi = NULL)
    {
        $date_grouper = '00/00/0000';
        global $sheet, $index;
        foreach ($arr as $value) {

            // CELL TGL
            if ($tgl) {
                $current_loop_date = date_format(date_create($value[$tgl]), "d/m/Y");
                if ($date_grouper !== $current_loop_date) {
                    $sheet->setCellValue("A$index", $current_loop_date);
                    $date_grouper = $current_loop_date;
                }
            }

            // CELL KETERANGAN
            if ($catatan_mutasi)
                $sheet->setCellValue("B$index", $value[$title]);
            else
                $sheet->setCellValue("B$index",  "$title " . $value['nama']);

            // CELL MUTASI
            $nominal = $value['nominal'];

            if ($catatan_mutasi)
                $mutasi = $value['tipe_mutasi'];

            if ($mutasi === 'Debit') {
                $sheet->setCellValue("C$index", $nominal);
                $sheet->getStyle("C$index")->getNumberFormat()->setFormatCode(IDR_NUMBERING_FORMAT);
            } else if ($mutasi === 'Kredit') {
                $sheet->setCellValue("D$index", $nominal);
                $sheet->getStyle("D$index")->getNumberFormat()->setFormatCode(IDR_NUMBERING_FORMAT);
            }

            // CELL SALDO
            $sbs_index = $index - 1;
            $sheet->setCellValue("E$index", "=E$sbs_index+C$index-D$index");
            $sheet->getStyle("E$index")->getNumberFormat()->setFormatCode(IDR_NUMBERING_FORMAT);
            $sheet->getStyle("A$index:E$index")->applyFromArray(XLSX_JURNAL_UMUM_STYLE_TABLE_DATA);
            $index++;
        }
    }

    foreach ($data_pertemuan as $pertemuan) {
        $header_bulan = strtoupper(BULAN[$pertemuan['bulan'] - 1]);

        $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet,  $header_bulan);
        $spreadsheet->addSheet($sheet);

        // HEADER
        $sheet->setCellValue('A1', 'JURNAL UMUM');
        $sheet->mergeCells('A1:E2');
        $sheet->getStyle('A1:E2')->applyFromArray(XLSX_JURNAL_UMUM_STYLE_HEADER);

        // SUB HEADER
        $sheet->setCellValue('A3', "PER $header_bulan $tahun");
        $sheet->mergeCells('A3:E3');
        $sheet->getStyle('A3:E3')->applyFromArray(XLSX_JURNAL_UMUM_STYLE_SUBHEADER);

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

        $sheet->getStyle('A5:A6')->applyFromArray(XLSX_JURNAL_UMUM_STYLE_TABLE_HEADER);
        $sheet->getStyle('B5:B6')->applyFromArray(XLSX_JURNAL_UMUM_STYLE_TABLE_HEADER);
        $sheet->getStyle('C5:D5')->applyFromArray(XLSX_JURNAL_UMUM_STYLE_TABLE_HEADER);
        $sheet->getStyle('C6')->applyFromArray(XLSX_JURNAL_UMUM_STYLE_TABLE_HEADER);
        $sheet->getStyle('D6')->applyFromArray(XLSX_JURNAL_UMUM_STYLE_TABLE_HEADER);
        $sheet->getStyle('E5:E6')->applyFromArray(XLSX_JURNAL_UMUM_STYLE_TABLE_HEADER);

        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);

        //// Data
        $bulan_pertemuan = $pertemuan['bulan'];
        $tahun_pertemuan = $pertemuan['tahun'];

        $sql = "SELECT *, SUM(subnominal) nominal FROM (SELECT t.tenggat_pembayaran, t.deskripsi, t.nominal subnominal, j.nama nama FROM tunggakan t
        JOIN detail_kelas dk ON t.id_siswa = dk.id_siswa
        JOIN kelas k ON dk.id_kelas = k.id_kelas
        JOIN jenjang j ON k.id_jenjang = j.id_jenjang
        JOIN siswa s ON t.id_siswa = s.id_siswa
        WHERE t.status = 'Lunas'
        AND MONTH(t.tgl_dibuat) = $bulan_pertemuan
        AND YEAR(t.tgl_dibuat) = $tahun_pertemuan
        GROUP BY t.id_siswa, dk.id_kelas) b
        GROUP BY nama";

        $data_tunggakan = $db->query($sql);
        $data_tunggakan->fetch_assoc();

        $sql = "SELECT *, i.nama nama FROM gaji g
        JOIN instruktur i ON g.id_instruktur = i.id_instruktur
        WHERE g.tgl_penerimaan IS NOT NULL
        AND MONTH(g.tgl_penerimaan) = $bulan_pertemuan
        AND YEAR(g.tgl_penerimaan) = $tahun_pertemuan";
        $data_gaji_instruktur = $db->query($sql);
        $data_gaji_instruktur->fetch_assoc();

        $index = 7;

        // Tunggakan
        iterator($data_tunggakan, 'SPP', 'tenggat_pembayaran', 'Debit');

        // Catatan Mutasi
        $sql = "SELECT * FROM detail_mutasi_jurnal_umum 
        WHERE MONTH(tgl_laporan) = '$bulan_pertemuan' 
        AND YEAR(tgl_laporan) = '$tahun_pertemuan' 
        ORDER BY tipe_mutasi ASC";

        $data_mutasi = $db->query($sql);
        $data_mutasi->fetch_assoc();

        iterator($data_mutasi, 'keterangan', NULL, 'tipe_mutasi', TRUE);

        // Gaji Admin (Static)
        $sbs_index = $index - 1;

        $sheet->setCellValue("B$index", 'GAJI Admin');
        $sheet->setCellValue("D$index", '10000');
        $sheet->setCellValue("E$index", "=E$sbs_index+C$index-D$index");
        $sheet->getStyle("D$index")->getNumberFormat()->setFormatCode(IDR_NUMBERING_FORMAT);
        $sheet->getStyle("E$index")->getNumberFormat()->setFormatCode(IDR_NUMBERING_FORMAT);
        $sheet->getStyle("A$index:E$index")->applyFromArray(XLSX_JURNAL_UMUM_STYLE_TABLE_DATA);
        $index++;

        // Gaji Instruktur
        iterator($data_gaji_instruktur, 'GAJI', 'tgl_penerimaan', 'Kredit');

        $sbs_index = $index - 1;
        $sheet->setCellValue("E$index", "=E$sbs_index");
        $sheet->setCellValue("F$index", "SALDO");
        $sheet->getStyle("E$index")->getFont()->setBold(true);
        $sheet->getStyle("E$index")->getNumberFormat()->setFormatCode(IDR_NUMBERING_FORMAT);
        $sheet->getStyle("F$index")->getFont()->setBold(true);
        $sheet->getStyle("A$index:E$index")->applyFromArray(XLSX_JURNAL_UMUM_STYLE_TABLE_DATA);

        $bulan_pertemuan = str_pad($bulan_pertemuan, 2, '0', STR_PAD_LEFT);
        $tgl_perubahan = "$tahun_pertemuan-$bulan_pertemuan-01";

        $calc = new Calculation($spreadsheet);
        $total_kredit = $calc->calculateFormula("=SUM(D7:D$sbs_index)");
        $total_saldo = $spreadsheet->getActiveSheet()->getCell("E$sbs_index")->getCalculatedValue();

        $sql = "INSERT INTO perubahan_saldo_kredit (tgl_perubahan, kredit, saldo)
                VALUES ('$tgl_perubahan', $total_kredit, $total_saldo)
                ON DUPLICATE KEY UPDATE kredit = $total_kredit, saldo = $total_saldo";

        $db->query($sql) or die($db->error);
    }



    $spreadsheet->removeSheetByIndex(0);

    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment;filename=JURNAL UMUM $tahun.xlsx");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    die;
}

if (isset($_GET['laporan_transport'])) {
    define(
        'XLSX_LAPORAN_TRANSPORT_STYLE_HEADER',
        [
            'font' => [
                'size' => 14,
                'bold' => true
            ],
            'alignment' => [
                'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ]
        ]
    );
    define(
        'XLSX_LAPORAN_TRANSPORT_STYLE_TABLE_HEADER',
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
        'XLSX_LAPORAN_TRANSPORT_STYLE_TABLE_DATA_DATE',
        [
            'font' => [
                'size' => 10,
                'bold' => true
            ],
            'alignment' => [
                'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]
    );
    define(
        'XLSX_LAPORAN_TRANSPORT_CELL_BORDER',
        [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000'],
                ],
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000']
                ]
            ]
        ]
    );

    $sql = "SELECT DISTINCT MONTH(tgl_pertemuan) bulan FROM detail_jadwal";
    $bulan_pertemuan = $db->query($sql) or die($db->error);
    $tahun = $_GET['tahun'];

    $spreadsheet = new Spreadsheet();

    $spreadsheet->getProperties()
        ->setCreator(XLSX_AUTHOR)
        ->setCompany(XLSX_COMPANY)
        ->setCategory('Report, Transport')
        ->setLastModifiedBy(XLSX_AUTHOR)
        ->setTitle("Laporan Transport Tahun $tahun")
        ->setSubject('Yearly Transport Report');


    foreach ($bulan_pertemuan as $pertemuan) {
        $header_bulan = strtoupper(BULAN[$pertemuan['bulan'] - 1]);
        $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet,  $header_bulan);
        $spreadsheet->addSheet($sheet);

        $nama_instruktur = [];
        $sql = "SELECT * FROM instruktur";
        $data_instruktur = $db->query($sql) or die($db->error);


        $sheet->getColumnDimension('A')->setWidth(30);

        // HEADER - Overriden

        // CONTENT

        //// Header

        $sheet
            ->setCellValue('A2', 'TANGGAL')
            ->getStyle('A2')
            ->applyFromArray(XLSX_LAPORAN_TRANSPORT_STYLE_TABLE_HEADER);

        $instruktur_list_index = 2; // Initial index
        foreach ($data_instruktur as $index => $instruktur) {
            $nama_instruktur[] = $instruktur['nama'];

            $sheet
                ->setCellValue([$instruktur_list_index, 2], strtoupper($instruktur['nama']))
                ->getStyle([$instruktur_list_index, 2])
                ->applyFromArray(XLSX_LAPORAN_TRANSPORT_STYLE_TABLE_HEADER);

            $sheet->getColumnDimensionByColumn($instruktur_list_index)->setWidth(15);
            $instruktur_list_index++;
        }

        ////// Override Main Header
        $sheet->setCellValue('A1', "PER $header_bulan $tahun")->getStyle('A1')->applyFromArray(XLSX_LAPORAN_TRANSPORT_STYLE_HEADER);
        $sheet->mergeCells([1, 1, $instruktur_list_index - 1, 1]);

        //// Data
        $month = $pertemuan['bulan'];

        $date_list_index = 3; // Initial Index

        // loop dari tanggal 1 hingga tanggal terakhir pada bulan yang ditentukan
        for ($day = 1; $day <= date('t', mktime(0, 0, 0, $month, 1, $tahun)); $day++) {
            $date = mktime(0, 0, 0, $month, $day, $tahun);
            $search_date = date('Y-m-d', $date);
            $applied_data = date('d/m/Y', $date);
            $is_sunday = date('l', $date) === 'Sunday';

            $instruktur_list_index = 2;
            $sheet->setCellValue("A$date_list_index", $applied_data)->getStyle("A$date_list_index")->applyFromArray(XLSX_LAPORAN_TRANSPORT_STYLE_TABLE_DATA_DATE);
            if (!$is_sunday) {
                foreach ($nama_instruktur as $nama) {
                    $sql = "SELECT tgl_pertemuan, nama, status_kehadiran_instruktur FROM detail_jadwal dj
                    JOIN instruktur i ON dj.id_instruktur = i.id_instruktur
                    WHERE nama = '$nama' AND tgl_pertemuan = '$search_date'
                    GROUP BY i.id_instruktur, dj.tgl_pertemuan
                    ORDER BY dj.tgl_pertemuan";

                    $data_pertemuan_instruktur = $db->query($sql)->fetch_assoc();
                    $is_presence = isset($data_pertemuan_instruktur['status_kehadiran_instruktur']) && $data_pertemuan_instruktur['status_kehadiran_instruktur'] === 'Hadir' ? 1 : '';
                    $sheet->setCellValue([$instruktur_list_index, $date_list_index], $is_presence);
                    $instruktur_list_index++;
                }
            } else {
                $sheet->getStyle([$instruktur_list_index, $date_list_index, count($nama_instruktur) + 1, $date_list_index])
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('ffff0000');
            }
            $date_list_index++;
        }

        $sheet->setCellValue([1, $date_list_index + 1], 'UANG TRANSPORT')
            ->getStyle([1, $date_list_index + 1])
            ->getFont()
            ->setBold(true);

        foreach ($nama_instruktur as $index => $nama) {
            $cellInstruktur = $index + 2;
            $biaya_transport = XLSX_DATA_BIAYA_TRANSPORT;
            $sheet->setCellValue([$cellInstruktur, $date_list_index], "=SUM({$sheet->getCell([$cellInstruktur, 3])->getCoordinate()}:{$sheet->getCell([$cellInstruktur,$date_list_index - 1])->getCoordinate()})");
            $sheet->setCellValue([$cellInstruktur, $date_list_index + 1], "=$biaya_transport*{$sheet->getCell([$cellInstruktur,$date_list_index])->getCoordinate()}");
        }

        $sheet
            ->getStyle([1, $date_list_index, count($nama_instruktur) + 1, $date_list_index + 1])
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('ff974806');

        $sheet
            ->getStyle([1, 2, count($nama_instruktur) + 1, $date_list_index + 1])
            ->applyFromArray(XLSX_LAPORAN_TRANSPORT_CELL_BORDER);
    }

    $spreadsheet->removeSheetByIndex(0);

    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment;filename=LAPORAN TRANSPORT TAHUN $tahun.xlsx");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
}

if (isset($_GET['slip_gaji_instruktur'])) {
    define(
        'XLSX_SLIP_GAJI_INSTRUKTUR_STYLE_HEADER',
        [
            'font' => [
                'size' => 14,
                'bold' => true
            ],
            'alignment' => [
                'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ]
        ]
    );
    define(
        'XLSX_SLIP_GAJI_INSTRUKTUR_STYLE_TABLE_HEADER',
        [
            'font' => [
                'size' => 11,
                'bold' => true
            ],
        ]
    );
    define(
        'XLSX_SLIP_GAJI_INSTRUKTUR_STYLE_TABLE_DATA_CENTER_BOLD',
        [
            'font' => [
                'size' => 11,
                'bold' => true
            ],
            'alignment' => [
                'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]
    );
    define(
        'XLSX_SLIP_GAJI_INSTRUKTUR_STYLE_TABLE_DATA_CENTER',
        [
            'font' => [
                'size' => 11
            ],
            'alignment' => [
                'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]
    );
    define(
        'XLSX_SLIP_GAJI_INSTRUKTUR_STYLE_TABLE_DATA_TOTAL_LABEL',
        [
            'font' => [
                'size' => 11,
                'bold' => true
            ],
            'alignment' => [
                'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ],
        ]
    );
    define(
        'XLSX_SLIP_GAJI_INSTRUKTUR_CELL_BORDER',
        [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000'],
                ],
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000']
                ]
            ]
        ]
    );

    $tahun = escape($_GET['tahun']);
    $bulan = escape($_GET['bulan']);
    $nama_bulan = strtoupper(BULAN[$bulan - 1]);

    $spreadsheet = new Spreadsheet();

    $spreadsheet->getProperties()
        ->setCreator(XLSX_AUTHOR)
        ->setCompany(XLSX_COMPANY)
        ->setCategory('Report', 'Slip', 'Salary')
        ->setLastModifiedBy(XLSX_AUTHOR)
        ->setTitle("Slip Gaji Instruktur Tahun $tahun Bulan $nama_bulan")
        ->setSubject('Yearly Salary Report');

    $sql = "SELECT i.id_instruktur, i.nama FROM detail_jadwal dj
    JOIN instruktur i ON dj.id_instruktur = i.id_instruktur
    WHERE YEAR(tgl_pertemuan) = $tahun
    AND MONTH(tgl_pertemuan) = $bulan
    GROUP BY i.id_instruktur";

    $nama_instruktur = $db->query($sql);

    foreach ($nama_instruktur as $instruktur) {
        $header_nama_instruktur = strtoupper($instruktur['nama']);
        $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet,  $header_nama_instruktur);
        $spreadsheet->addSheet($sheet);

        // Set Cell Width
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);

        // HEADER
        $sheet->setCellValue('A1', 'SLIP GAJI')
            ->mergeCells('A1:E1')
            ->getStyle('A1:E1')
            ->applyFromArray(XLSX_SLIP_GAJI_INSTRUKTUR_STYLE_HEADER);

        // SUBHEADER
        $sheet
            ->setCellValue('A2', "BULAN {$nama_bulan}")
            ->mergeCells('A2:E2')
            ->getStyle('A2:E2')
            ->applyFromArray(XLSX_SLIP_GAJI_INSTRUKTUR_STYLE_HEADER);

        // CONTENT

        //// Name
        $sheet->setCellValue('A4', 'NAMA:');

        $sheet->setCellValue('B4', $header_nama_instruktur);

        $sheet->getStyle('A4:B4')->applyFromArray(XLSX_SLIP_GAJI_INSTRUKTUR_STYLE_TABLE_HEADER);

        //// Header
        $sheet->setCellValue('A5', 'NO');
        $sheet->setCellValue('B5', 'NAMA KLP');
        $sheet->setCellValue('C5', 'JUMLAH');
        $sheet->setCellValue('D5', 'HONOR');
        $sheet->setCellValue('E5', 'TOTAL');

        $sheet->getStyle('A5:E5')->applyFromArray(XLSX_SLIP_GAJI_INSTRUKTUR_STYLE_TABLE_DATA_CENTER_BOLD);

        // Data
        $sql = "SELECT k.nama nama_klp, COUNT(dj.id_detail_jadwal) jumlah, je.biaya_per_pertemuan honor, SUM(je.biaya_per_pertemuan) total FROM detail_jadwal dj
        JOIN jadwal j ON dj.id_jadwal = j.id_jadwal
        JOIN kelas k ON j.id_kelas = k.id_kelas
        JOIN jenjang je ON k.id_jenjang = je.id_jenjang
        WHERE dj.id_instruktur = '{$instruktur['id_instruktur']}' AND status_kehadiran_instruktur = 'Hadir'
        GROUP BY k.id_kelas";

        $data_pertemuan = $db->query($sql) or die($db->error);
        $starting_list_index = 6;
        foreach ($data_pertemuan as $index => $pertemuan) {
            $sheet->setCellValue("A$starting_list_index", $index + 1)->getStyle("A$starting_list_index")->applyFromArray(XLSX_SLIP_GAJI_INSTRUKTUR_STYLE_TABLE_DATA_CENTER_BOLD);
            $sheet->setCellValue("B$starting_list_index", $pertemuan['nama_klp']);
            $sheet->setCellValue("C$starting_list_index", $pertemuan['jumlah'])->getStyle("C$starting_list_index")->applyFromArray(XLSX_SLIP_GAJI_INSTRUKTUR_STYLE_TABLE_DATA_CENTER);
            $sheet->setCellValue("D$starting_list_index", $pertemuan['honor'])->getStyle("D$starting_list_index")->applyFromArray(XLSX_SLIP_GAJI_INSTRUKTUR_STYLE_TABLE_DATA_CENTER);
            $sheet->setCellValue("E$starting_list_index", $pertemuan['total'])->getStyle("E$starting_list_index")->applyFromArray(XLSX_SLIP_GAJI_INSTRUKTUR_STYLE_TABLE_DATA_CENTER);
            $starting_list_index++;
        }

        $last_list_index = $starting_list_index - 1;

        $sheet
            ->setCellValue("A$starting_list_index", "JUMLAH GAJI BLN $nama_bulan")
            ->mergeCells("A$starting_list_index:D$starting_list_index")
            ->getStyle("A$starting_list_index:E$starting_list_index")
            ->applyFromArray(XLSX_SLIP_GAJI_INSTRUKTUR_STYLE_TABLE_DATA_TOTAL_LABEL);

        $sheet->setCellValue("E$starting_list_index", "=SUM(E6:E$last_list_index)")->getStyle("E$starting_list_index")->applyFromArray(XLSX_SLIP_GAJI_INSTRUKTUR_STYLE_TABLE_DATA_CENTER);

        $admin_title_index = $starting_list_index + 2;
        $admin_name_index = $starting_list_index + 5;

        $roles = strtoupper(implode(', ', $_SESSION['roles']));
        $sheet->setCellValue("D$admin_title_index", "$roles BIMBEL SMART");
        $sheet->setCellValue("D$admin_name_index", strtoupper($_SESSION['nama']));

        // Bordering
        $sheet->getStyle("A5:E$starting_list_index")->applyFromArray(XLSX_SLIP_GAJI_INSTRUKTUR_CELL_BORDER);

        // Number Formatting
        $sheet->getStyle("D5:E$starting_list_index")->getNumberFormat()->setFormatCode(IDR_NUMBERING_FORMAT);
    }

    $spreadsheet->removeSheetByIndex(0);

    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment;filename=SLIP GAJI INSTRUKTUR TAHUN $tahun BULAN $nama_bulan.xlsx");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
}

if (isset($_GET['redirect_catatan_mutasi_jurnal_umum'])) {
    $bulan = escape($_GET['bulan']);
    $tahun = escape($_GET['tahun']);

    redirect("../../client/admin/laporan_keuangan.php?catatan_mutasi_jurnal_umum&tahun=$tahun&bulan=$bulan");
}

if (isset($_POST['create_catatan_mutasi_jurnal_umum'])) {
    $tgl_laporan = escape($_POST['tgl_laporan']);
    $keterangan = escape($_POST['keterangan']);
    $tipe_mutasi = escape($_POST['tipe_mutasi']);
    $nominal = escape($_POST['nominal']);

    $sql = "INSERT INTO detail_mutasi_jurnal_umum (tgl_laporan, keterangan, tipe_mutasi, nominal) 
    VALUES('$tgl_laporan', '$keterangan', '$tipe_mutasi', '$nominal')";

    $db->query($sql);

    push_toast('Catatan mutasi berhasil ditambahkan');
?>
    <script>
        history.back()
    </script>
<?php
}

if (isset($_POST['delete_catatan_mutasi_jurnal_umum'])) {
    $id_mutasi = escape($_POST['delete_catatan_mutasi_jurnal_umum']);

    $sql = "DELETE FROM detail_mutasi_jurnal_umum WHERE id_detail_mutasi_jurnal_umum = $id_mutasi";

    $db->query($sql);

    push_toast('Catatan mutasi berhasil dihapus');
?>
    <script>
        history.back()
    </script>
<?php
}
