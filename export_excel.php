<?php
require 'C:/xampp/htdocs/PT.Sinergi_Karya_Mandiri/vendor/autoload.php'; // Pastikan ini sesuai dengan instalasi PhpSpreadsheet Anda
include 'C:/xampp/htdocs/PT.Sinergi_Karya_Mandiri/Z_XSample/Koneksi/Koneksi.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_GET['id_gedung'])) {
    $id_gedung = $_GET['id_gedung'];

    // Ambil nama gedung dan alamat
    $sql_gedung = "SELECT nama_gedung, address FROM gedung WHERE id_gedung = ?";
    $stmt_gedung = $conn->prepare($sql_gedung);
    $stmt_gedung->bind_param("i", $id_gedung);
    $stmt_gedung->execute();
    $result_gedung = $stmt_gedung->get_result()->fetch_assoc();
    $nama_gedung = $result_gedung['nama_gedung'] ?? '';
    $alamat_gedung = $result_gedung['address'] ?? '';

    // Ambil data tower
    $sql_tower = "SELECT id_tower, nama_tower, lift_brand, lift_type, lift_no, pic, jumlah_lantai FROM audit_tower WHERE id_gedung = ?";
    $stmt_tower = $conn->prepare($sql_tower);
    $stmt_tower->bind_param("i", $id_gedung);
    $stmt_tower->execute();
    $result_tower = $stmt_tower->get_result();
    $row_tower = $result_tower->fetch_assoc();

    // Data tower
    $nama_tower = $row_tower['nama_tower'] ?? '';
    $lift_brand = $row_tower['lift_brand'] ?? '';
    $lift_type = $row_tower['lift_type'] ?? '';
    $lift_no = $row_tower['lift_no'] ?? '';
    $pic = $row_tower['pic'] ?? '';
    $jumlah_lantai = $row_tower['jumlah_lantai'] ?? '';

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header Excel
    $sheet->setCellValue('A1', "FORM GENERAL CHECK ELEVATOR PT. SINERGI KARYA MANDIRI");
    $sheet->mergeCells('A1:G1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Metadata
    $sheet->setCellValue('B3', 'NAMA GEDUNG');
    $sheet->setCellValue('C3', ": $nama_gedung");
    $sheet->setCellValue('B4', 'ALAMAT');
    $sheet->setCellValue('C4', ": $alamat_gedung");
    $sheet->setCellValue('B5', 'MERK LIFT');
    $sheet->setCellValue('C5', ": $lift_brand");
    $sheet->setCellValue('B6', 'TYPE LIFT');
    $sheet->setCellValue('C6', ": $lift_type");
    $sheet->setCellValue('B7', 'NAMA TOWER');
    // $sheet->setCellValue('C7', ": $nama_tower");
    $sheet->mergeCells('C3:E3');
    $sheet->mergeCells('C4:E4');
    $sheet->mergeCells('C5:E5');
    $sheet->mergeCells('C6:E6');
    // $sheet->mergeCells('C7:E7');
 

    $sheet->setCellValue('F3', 'NO LIFT');
    $sheet->setCellValue('G3', ": $lift_no");
    $sheet->setCellValue('F4', 'JUMLAH LANTAI');
    $sheet->setCellValue('G4', ": $jumlah_lantai");
    $sheet->setCellValue('F5', 'HARI/TANGGAL');
    $sheet->setCellValue('G5', ': '); // Tambahkan logika untuk tanggal jika perlu
    $sheet->setCellValue('F6', 'JAM');
    $sheet->setCellValue('G6', ': '); // Tambahkan logika untuk waktu jika perlu
    $sheet->setCellValue('F7', 'INSPEKTOR');
    $sheet->setCellValue('G7', ": $pic");

    // Header Data Audit
    $sheet->setCellValue('A9', 'No');
    $sheet->setCellValue('B9', 'Nama Komponen');
    $sheet->setCellValue('C9', 'Kondisi');
    $sheet->setCellValue('D9', 'Fungsi');
    $sheet->setCellValue('E9', 'Keterangan');
    $sheet->setCellValue('F9', 'Hasil Pengukuran');
    $sheet->setCellValue('G9', 'Prioritas');
    $sheet->getStyle('A9:G9')->getFont()->setBold(true);

    $sheet->setCellValue('B10', 'MESIN ROOM');

    // Isi Data
    $row = 11;
    $no = 1;
    $sql_komponen = "SELECT ak.id_komponen, k.nama_komponen, ak.kondisi, ak.fungsi, ak.keterangan, ak.hasil_pengukuran, ak.prioritas
                     FROM audit_komponen ak
                     JOIN komponen k ON ak.id_komponen = k.id_komponen
                     WHERE ak.id_tower = ?";
    $stmt_komponen = $conn->prepare($sql_komponen);
    $stmt_komponen->bind_param("i", $row_tower['id_tower']);
    $stmt_komponen->execute();
    $result_komponen = $stmt_komponen->get_result();

    while ($row_komponen = $result_komponen->fetch_assoc()) {
        $sheet->setCellValue("A$row", $no);
        $sheet->setCellValue("B$row", $row_komponen['nama_komponen']);
        $sheet->setCellValue("C$row", $row_komponen['kondisi']);
        $sheet->setCellValue("D$row", $row_komponen['fungsi']);
        $sheet->setCellValue("E$row", $row_komponen['keterangan']);
        $sheet->setCellValue("F$row", $row_komponen['hasil_pengukuran']);
        $sheet->setCellValue("G$row", $row_komponen['prioritas']);
        $sheet->setCellValue("H$row", $nama_tower);
        $row++;
    }

    // Format
    foreach (range('A', 'H') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Output Excel
    $filename = "Data_Audit_Gedung_$nama_gedung.xlsx";
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header('Cache-Control: max-age=0');
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
?>
