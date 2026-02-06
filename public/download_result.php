<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/bootstrap.php';

use App\Classes\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

Auth::requireLogin();

// ============================================
// DIRECT PDO CONNECTION (Same as test-db.php)
// ============================================
try {
    $pdo = new PDO('mysql:host=db;dbname=school', 'root', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get student ID from session
$student_id = $_SESSION['student_id'];
$student_class = $_SESSION['student_class'];

// Fetch fresh student data from database
$sql = "SELECT * FROM student_data WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $student_id, PDO::PARAM_INT);
$stmt->execute();
$student_data = $stmt->fetch(PDO::FETCH_ASSOC);

// Determine table name based on class
$classMap = [
    'first' => 'ahmed',
    'second' => 'mohamed',
];
$table_name = $classMap[strtolower($student_class)] ?? 'student_marks';

// FETCH ALL MARKS (DIRECT QUERY - Same as test-db.php)
$result = $pdo->query('SELECT * FROM ' . $table_name);
$student_marks = $result->fetchAll(PDO::FETCH_ASSOC);

// Calculate total marks by looping through ALL rows
$total_marks = 0;
foreach ($student_marks as $mark) {
    if (isset($mark['marks'])) {
        $total_marks += (int)$mark['marks'];
    }
}

if (empty($student_marks) || empty($student_data)) {
    die("No data available for download.");
}

try {
    // Create spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Student Results');

    // Set column widths
    $sheet->getColumnDimension('A')->setWidth(15);
    $sheet->getColumnDimension('B')->setWidth(30);
    $sheet->getColumnDimension('C')->setWidth(15);
    $sheet->getColumnDimension('D')->setWidth(15);

    // Title
    $sheet->setCellValue('A1', 'Student Result Report');
    $sheet->mergeCells('A1:D1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

    // Student info
    $row = 3;
    $sheet->setCellValue('A' . $row, 'Student Name:');
    $sheet->getStyle('A' . $row)->getFont()->setBold(true);
    $sheet->setCellValue('B' . $row, $student_data['student_name'] ?? 'N/A');
    
    $row++;
    $sheet->setCellValue('A' . $row, 'Roll Number:');
    $sheet->getStyle('A' . $row)->getFont()->setBold(true);
    $sheet->setCellValue('B' . $row, $student_data['roll_number'] ?? 'N/A');
    
    $row++;
    $sheet->setCellValue('A' . $row, 'Class:');
    $sheet->getStyle('A' . $row)->getFont()->setBold(true);
    $sheet->setCellValue('B' . $row, $student_data['class'] ?? 'N/A');

    // Headers
    $row = 7;
    $headers = ['ID', 'Subject', 'Marks', 'Percentage'];
    $columns = ['A', 'B', 'C', 'D'];
    foreach ($headers as $col => $header) {
        $sheet->setCellValue($columns[$col] . $row, $header);
        $sheet->getStyle($columns[$col] . $row)->getFont()->setBold(true);
        $sheet->getStyle($columns[$col] . $row)->getFill()->setFillType('solid')->getStartColor()->setARGB('FFC0DCFF');
    }

    // Data rows
    $row = 8;
    foreach ($student_marks as $mark) {
        $sheet->setCellValue('A' . $row, $mark['id']);
        $sheet->setCellValue('B' . $row, $mark['subject']);
        $sheet->setCellValue('C' . $row, $mark['marks']);
        $percentage = isset($mark['percentage']) ? $mark['percentage'] : (isset($mark['percntage']) ? $mark['percntage'] : '0');
        $sheet->setCellValue('D' . $row, $percentage);
        $row++;
    }

    // Total row
    $sheet->setCellValue('A' . $row, 'Total');
    $sheet->mergeCells('A' . $row . ':C' . $row);
    $sheet->getStyle('A' . $row . ':D' . $row)->getFont()->setBold(true);
    $sheet->getStyle('A' . $row . ':D' . $row)->getFill()->setFillType('solid')->getStartColor()->setARGB('FFE6E6E6');
    $sheet->setCellValue('D' . $row, $total_marks . ' / 400');

    // Clear output buffer
    if (ob_get_length()) {
        ob_end_clean();
    }

    // Generate Excel file with proper headers
    $writer = new Xlsx($spreadsheet);
    
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="student_result_' . date('YmdHis') . '.xlsx"');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');

    $writer->save('php://output');
    exit();

} catch (Exception $e) {
    die("Error generating Excel file: " . $e->getMessage());
}
