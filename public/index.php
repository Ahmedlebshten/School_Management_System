<?php
require_once __DIR__ . '/../app/bootstrap.php';

use App\Classes\Auth;
use App\Config\Database;

// prevent any output before headers are sent
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

Auth::requireLogin();

// Get student ID from session
$student_id = $_SESSION['student_id'] ?? null;
$student_class = $_SESSION['student_class'] ?? null;

// DATABASE CONNECTION
$pdo = Database::getInstance();

// Fetch student data
$sql = "SELECT * FROM student_data WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $student_id, \PDO::PARAM_INT);
$stmt->execute();
$student_data = $stmt->fetch(\PDO::FETCH_ASSOC);

// Determine table name safely
$classMap = [
    'first' => 'ahmed',
    'second' => 'mohamed',
];

$table_name = $classMap[strtolower($student_class)] ?? null;

$student_marks = [];
$total_marks = 0;

if ($table_name) {
    $result = $pdo->query("SELECT * FROM $table_name");
    $student_marks = $result->fetchAll(\PDO::FETCH_ASSOC);

    foreach ($student_marks as $mark) {
        if (isset($mark['marks'])) {
            $total_marks += (int)$mark['marks'];
        }
    }
}
?>