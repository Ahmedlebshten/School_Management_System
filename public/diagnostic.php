<?php
/**
 * DIAGNOSTIC: Check what the database actually returns
 * Run this to see if the query returns multiple rows
 */

require_once __DIR__ . '/app/bootstrap.php';

use App\Config\Database;
use PDO;

try {
    $db = Database::getInstance();
    
    echo "\n╔════════════════════════════════════════════════════════════════╗\n";
    echo "║              DATABASE DIAGNOSTIC REPORT                        ║\n";
    echo "╚════════════════════════════════════════════════════════════════╝\n\n";
    
    // Test 1: Direct query to ahmed table
    echo "TEST 1: Direct Query to AHMED table\n";
    echo "─────────────────────────────────────────────────────────────────\n";
    echo "SQL: SELECT * FROM \`ahmed\`\n\n";
    
    $sql = "SELECT * FROM `ahmed`";
    $stmt = $db->prepare($sql);
    
    if (!$stmt->execute()) {
        die("Query failed: " . implode(", ", $stmt->errorInfo()));
    }
    
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Result Count: " . count($rows) . " rows\n\n";
    
    if (count($rows) > 0) {
        echo "Data Returned:\n";
        foreach ($rows as $i => $row) {
            echo "  Row " . ($i+1) . ": ";
            echo json_encode($row) . "\n";
        }
    } else {
        echo "ERROR: No rows returned!\n";
    }
    
    echo "\n" . str_repeat("─", 65) . "\n\n";
    
    // Test 2: Direct query to student_data table
    echo "TEST 2: Direct Query to STUDENT_DATA table\n";
    echo "─────────────────────────────────────────────────────────────────\n";
    echo "SQL: SELECT * FROM \`student_data\`\n\n";
    
    $sql = "SELECT * FROM `student_data`";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Result Count: " . count($rows) . " rows\n\n";
    
    if (count($rows) > 0) {
        echo "Data Returned:\n";
        foreach ($rows as $i => $row) {
            echo "  Row " . ($i+1) . ": ";
            echo json_encode($row) . "\n";
        }
    }
    
    echo "\n" . str_repeat("─", 65) . "\n\n";
    
    // Test 3: Check table schema
    echo "TEST 3: AHMED Table Schema\n";
    echo "─────────────────────────────────────────────────────────────────\n";
    
    $sql = "DESCRIBE `ahmed`";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $schema = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($schema, JSON_PRETTY_PRINT) . "\n\n";
    
    // Test 4: Row count for each table
    echo str_repeat("─", 65) . "\n";
    echo "TEST 4: Row Counts\n";
    echo str_repeat("─", 65) . "\n";
    
    $tables = ['ahmed', 'mohamed', 'student_data'];
    foreach ($tables as $table) {
        $sql = "SELECT COUNT(*) as count FROM \`" . $table . "\`";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "Table '{$table}': " . $result['count'] . " rows\n";
    }
    
    echo "\n" . str_repeat("═", 65) . "\n";
    echo "✓ DIAGNOSTIC COMPLETE\n";
    echo str_repeat("═", 65) . "\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
    exit(1);
}
?>
