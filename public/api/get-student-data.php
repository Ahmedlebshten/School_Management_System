<?php
/**
 * Student Data Backend API
 * 
 * Fetches LIVE data ONLY from the real MySQL database.
 * NO caching, NO mock data, NO fallbacks.
 * 
 * Rules:
 * - If Student ID = 1 AND Class = "first" → Query table: ahmed
 * - If Student ID = 2 AND Class = "second" → Query table: mohamed
 * - Otherwise → Query table: student_data
 * 
 * Every request executes a fresh SQL query against the database.
 */

require_once __DIR__ . '/../app/bootstrap.php';

use App\Config\Database;
use PDO;

// Set JSON response header
header('Content-Type: application/json');

// Response container
$response = [
    'success' => false,
    'data' => null,
    'message' => '',
    'debug' => []
];

try {
    // ============================================
    // INPUT VALIDATION
    // ============================================
    
    $student_id = isset($_GET['student_id']) ? (int)$_GET['student_id'] : null;
    $class = isset($_GET['class']) ? trim($_GET['class']) : null;
    
    // Validate student_id
    if ($student_id === null || $student_id === 0) {
        throw new Exception("student_id parameter is required and must be a positive integer");
    }
    
    if ($student_id < 0) {
        throw new Exception("student_id must be a positive integer");
    }
    
    // Validate class
    if ($class === null || $class === '') {
        throw new Exception("class parameter is required");
    }
    
    if (strlen($class) > 50) {
        throw new Exception("class parameter exceeds maximum length");
    }
    
    // ============================================
    // DATABASE CONNECTION
    // ============================================
    
    $db = Database::getInstance();
    
    if (!$db) {
        throw new Exception("Failed to establish database connection");
    }
    
    // ============================================
    // STEP 1: Verify Student Exists in student_data
    // ============================================
    
    $student_check_sql = "SELECT id, name, class FROM student_data WHERE id = :student_id LIMIT 1";
    $student_stmt = $db->prepare($student_check_sql);
    
    if (!$student_stmt) {
        throw new Exception("Failed to prepare student verification query");
    }
    
    $student_stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    
    // LIVE QUERY #1: Execute fresh query
    if (!$student_stmt->execute()) {
        throw new Exception("Failed to execute student verification query");
    }
    
    $student_info = $student_stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$student_info) {
        throw new Exception("Student ID {$student_id} not found in database");
    }
    
    // ============================================
    // STEP 2: Verify Class Matches
    // ============================================
    
    $db_class = strtolower(trim($student_info['class']));
    $input_class = strtolower(trim($class));
    
    if ($db_class !== $input_class) {
        throw new Exception("Student ID {$student_id} belongs to class '{$student_info['class']}', not '{$class}'");
    }
    
    // ============================================
    // STEP 3: Determine Target Table
    // ============================================
    
    $target_table = determineTargetTable($student_id, $db_class);
    
    // Validate table name (prevent SQL injection)
    $allowed_tables = ['ahmed', 'mohamed', 'student_data'];
    if (!in_array($target_table, $allowed_tables, true)) {
        throw new Exception("Invalid table selection logic");
    }
    
    // ============================================
    // STEP 4: Query Student Marks from Target Table
    // ============================================
    
    // Build query with table name properly escaped
    $marks_sql = "SELECT * FROM `" . $target_table . "` ORDER BY subject ASC";
    $marks_stmt = $db->prepare($marks_sql);
    
    if (!$marks_stmt) {
        throw new Exception("Failed to prepare marks query for table '{$target_table}'");
    }
    
    // LIVE QUERY #2: Execute fresh query against database
    if (!$marks_stmt->execute()) {
        throw new Exception("Failed to execute marks query");
    }
    
    $student_marks = $marks_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($student_marks)) {
        throw new Exception("No marks found in table '{$target_table}'");
    }
    
    // Loop through all marks to ensure proper data structure
    $marks_with_validation = [];
    foreach ($student_marks as $mark) {
        $marks_with_validation[] = [
            'id' => $mark['id'] ?? null,
            'subject' => $mark['subject'] ?? null,
            'marks' => $mark['marks'] ?? null
        ];
    }
    
    // ============================================
    // STEP 5: Prepare Response with Live Data
    // ============================================
    
    $response['success'] = true;
    $response['data'] = [
        'student_id' => (int)$student_info['id'],
        'student_name' => $student_info['name'],
        'student_class' => $student_info['class'],
        'table_queried' => $target_table,
        'marks' => $marks_with_validation,  // ALL marks from table
        'total_marks' => (int)array_sum(array_column($marks_with_validation, 'marks')),
        'subjects_count' => count($marks_with_validation),  // How many rows returned
        'query_timestamp' => date('Y-m-d H:i:s')
    ];
    $response['message'] = "Successfully fetched " . count($marks_with_validation) . " rows from table '" . $target_table . "'";
    
    
    http_response_code(200);
    
} catch (Exception $e) {
    // Return error WITHOUT any mock or fallback data
    $response['success'] = false;
    $response['message'] = $e->getMessage();
    http_response_code(400);
}

// Output JSON response
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
exit;

/**
 * Determine which table to query based on Student ID and Class
 * 
 * RULES:
 * - If Student ID = 1 AND Class = "first" → Return 'ahmed'
 * - If Student ID = 2 AND Class = "second" → Return 'mohamed'
 * - Otherwise → Return 'student_data'
 * 
 * @param int $student_id The student ID
 * @param string $class_normalized The class name (lowercase, trimmed)
 * @return string The table name to query
 */
function determineTargetTable(int $student_id, string $class_normalized): string
{
    // Rule 1: Student ID = 1 AND Class = "first"
    if ($student_id === 1 && $class_normalized === 'first') {
        return 'ahmed';
    }
    
    // Rule 2: Student ID = 2 AND Class = "second"
    if ($student_id === 2 && $class_normalized === 'second') {
        return 'mohamed';
    }
    
    // Default: Query student_data table
    return 'student_data';
}

?>
