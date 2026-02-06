<?php
/**
 * Student API Backend
 * 
 * This file implements the conditional table selection logic:
 * - Student ID = 1 AND Class = "first" → Query table: ahmed
 * - Student ID = 2 AND Class = "second" → Query table: mohamed
 * - Otherwise → Query student_data (fallback)
 * 
 * Features:
 * - Always returns LIVE data from database
 * - Fresh SQL query on every request
 * - Prepared statements (SQL injection prevention)
 * - Input validation
 * - Error handling
 */

require_once __DIR__ . '/../app/bootstrap.php';

use App\Config\Database;
use PDO;

// Set JSON response header
header('Content-Type: application/json');

// Initialize response
$response = [
    'success' => false,
    'data' => [],
    'message' => ''
];

try {
    // Get request method and parameters
    $method = $_SERVER['REQUEST_METHOD'];
    $student_id = isset($_GET['student_id']) ? (int)$_GET['student_id'] : null;
    $class = isset($_GET['class']) ? trim($_GET['class']) : null;
    
    // Validate input
    if (!$student_id || $student_id <= 0) {
        throw new Exception("Invalid or missing student_id parameter");
    }
    
    if (!$class || strlen($class) === 0) {
        throw new Exception("Invalid or missing class parameter");
    }
    
    // Get database connection
    $db = Database::getInstance();
    
    // ============================================
    // CONDITIONAL TABLE SELECTION LOGIC
    // ============================================
    
    // Determine which table to query based on student ID and class
    $table_to_query = selectTableByStudentAndClass($student_id, $class);
    
    if (!$table_to_query) {
        throw new Exception("Unable to determine data source for the selected student and class");
    }
    
    // ============================================
    // FETCH LIVE DATA FROM SELECTED TABLE
    // ============================================
    
    // First, verify the student exists in student_data
    $student_check_sql = "SELECT id, name, class FROM student_data WHERE id = :student_id";
    $student_stmt = $db->prepare($student_check_sql);
    $student_stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $student_stmt->execute();
    $student_info = $student_stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$student_info) {
        throw new Exception("Student ID {$student_id} not found in the database");
    }
    
    // Verify class matches
    if (strtolower(trim($student_info['class'])) !== strtolower(trim($class))) {
        throw new Exception("Student ID {$student_id} does not belong to class '{$class}'");
    }
    
    // ============================================
    // EXECUTE FRESH SQL QUERY (LIVE DATA)
    // ============================================
    
    // Build SQL query using prepared statements
    $marks_sql = "SELECT * FROM `" . $table_to_query . "` ORDER BY id, subject ASC";
    $marks_stmt = $db->prepare($marks_sql);
    
    // Execute query (FRESH DATA ON EVERY REQUEST)
    $marks_stmt->execute();
    $student_marks = $marks_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($student_marks)) {
        throw new Exception("No marks found in table '{$table_to_query}'");
    }
    
    // Loop through ALL marks to validate and structure data
    $all_marks = [];
    foreach ($student_marks as $mark) {
        $all_marks[] = [
            'id' => $mark['id'] ?? null,
            'subject' => $mark['subject'] ?? null,
            'marks' => $mark['marks'] ?? null
        ];
    }
    
    // ============================================
    // BUILD RESPONSE WITH LIVE DATA
    // ============================================
    
    $response['success'] = true;
    $response['data'] = [
        'student_info' => $student_info,
        'table_queried' => $table_to_query,
        'marks' => $all_marks,  // ALL marks from table
        'total_marks' => array_sum(array_column($all_marks, 'marks')),
        'subjects_count' => count($all_marks),  // Total rows returned
        'row_details' => "Returned " . count($all_marks) . " rows from table '" . $table_to_query . "'"
    ];
    $response['message'] = "Successfully fetched " . count($all_marks) . " rows (LIVE DATA)";
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
    http_response_code(400);
}

// Output JSON response
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
exit;

/**
 * CONDITIONAL TABLE SELECTION FUNCTION
 * 
 * Rules:
 * - If Student ID = 1 AND Class = "first" → Query table: ahmed
 * - If Student ID = 2 AND Class = "second" → Query table: mohamed
 * - Otherwise → Use student_data as fallback
 * 
 * @param int $student_id The student ID
 * @param string $class The class name
 * @return string|null The table name to query, or null if invalid
 */
function selectTableByStudentAndClass(int $student_id, string $class): ?string
{
    // Normalize class input
    $class_lower = strtolower(trim($class));
    
    // Rule 1: Student ID = 1 AND Class = "first"
    if ($student_id === 1 && $class_lower === 'first') {
        return 'ahmed';
    }
    
    // Rule 2: Student ID = 2 AND Class = "second"
    if ($student_id === 2 && $class_lower === 'second') {
        return 'mohamed';
    }
    
    // Default fallback
    return 'student_data';
}

?>
