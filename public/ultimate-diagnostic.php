<?php
/**
 * ULTIMATE DIAGNOSTIC
 * Shows exactly what's in the database and what the query returns
 */

require_once __DIR__ . '/../app/bootstrap.php';

use App\Config\Database;
use PDO;

echo "\n╔════════════════════════════════════════════════════════════════╗\n";
echo "║          ULTIMATE DIAGNOSTIC - WHAT'S IN DATABASE              ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

try {
    // Direct PDO connection (same as app uses)
    $db = Database::getInstance();
    
    // Test 1: Raw count
    echo "STEP 1: Row Counts in Each Table\n";
    echo "─────────────────────────────────────────────────────────────────\n";
    
    foreach (['ahmed', 'mohamed', 'student_data'] as $table) {
        $result = $db->query("SELECT COUNT(*) as cnt FROM `" . $table . "`");
        $row = $result->fetch(PDO::FETCH_ASSOC);
        echo "{$table}: " . $row['cnt'] . " total rows\n";
    }
    
    echo "\n" . str_repeat("─", 65) . "\n\n";
    
    // Test 2: Exact query used in index.php
    echo "STEP 2: Simulate PHP Query from index.php\n";
    echo "─────────────────────────────────────────────────────────────────\n";
    
    $table_name = "ahmed";
    echo "Table: {$table_name}\n";
    echo "Query: SELECT * FROM \`{$table_name}\`\n";
    echo "Method: fetchAll(PDO::FETCH_ASSOC)\n\n";
    
    $sql = "SELECT * FROM `" . $table_name . "`";
    $stmt = $db->prepare($sql);
    $success = $stmt->execute();
    
    if (!$success) {
        echo "ERROR: Query failed!\n";
        print_r($stmt->errorInfo());
    } else {
        echo "Query executed successfully\n";
    }
    
    $student_marks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\nResult count: " . count($student_marks) . " rows\n";
    echo "Result array:\n";
    echo json_encode($student_marks, JSON_PRETTY_PRINT) . "\n\n";
    
    echo str_repeat("─", 65) . "\n\n";
    
    // Test 3: Try different fetch method
    echo "STEP 3: Try With Fresh Query\n";
    echo "─────────────────────────────────────────────────────────────────\n";
    
    $sql = "SELECT * FROM `" . $table_name . "`";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    
    $fresh_rows = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $fresh_rows[] = $row;
    }
    
    echo "Fetched manually with while loop: " . count($fresh_rows) . " rows\n";
    echo "Data: " . json_encode($fresh_rows, JSON_PRETTY_PRINT) . "\n\n";
    
    echo str_repeat("─", 65) . "\n\n";
    
    // Test 4: Check table schema
    echo "STEP 4: Table Schemas\n";
    echo "─────────────────────────────────────────────────────────────────\n";
    
    foreach (['ahmed','mohamed'] as $table) {
        echo "\nTable: {$table}\n";
        $result = $db->query("DESCRIBE `" . $table . "`");
        $cols = $result->fetchAll(PDO::FETCH_ASSOC);
        foreach ($cols as $col) {
            echo "  - " . $col['Field'] . " (" . $col['Type'] . ") NULL:" . $col['Null'] . " KEY:" . $col['Key'] . "\n";
        }
    }
    
    echo "\n" . str_repeat("═", 65) . "\n";
    echo "END OF DIAGNOSTIC\n";
    echo str_repeat("═", 65) . "\n";
    
} catch (Exception $e) {
    echo "\nEXCEPTION: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
?>
