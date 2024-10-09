<?php
session_start();

// Ensure session data is available
if (!isset($_SESSION['student_marks']) || !isset($_SESSION['student_data'])) {
    echo "No data available for download.";
    exit();
}

$student_marks = $_SESSION['student_marks'];
$total_marks = $_SESSION['total_marks'];

// Set headers to force download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="student_result.csv"');

// Open output stream
$output = fopen('php://output', 'w');

// Write column headers
fputcsv($output, ['Id', 'Subject', 'Marks', 'Percentage']);

// Write rows
foreach ($student_marks as $mark) {
    fputcsv($output, [$mark['id'], $mark['subject'], $mark['marks'], $mark['percntage']]);
}

// Write total marks row
fputcsv($output, []);
fputcsv($output, ['Total Marks', $total_marks . ' out of 400']);

// Close output stream
fclose($output);
exit();