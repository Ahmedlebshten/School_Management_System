<?php
/**
 * SIMPLE: Show what's actually in the database tables
 * Uses environment variables for database configuration (no fallbacks)
 */

try {
    // Read database credentials from environment variables only
    $host = getenv('DB_HOST');
    $db   = getenv('DB_NAME');
    $user = getenv('DB_USER');
    $pass = getenv('DB_PASS');
    $charset = getenv('DB_CHARSET') ?: 'utf8mb4';
    
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "═══════════════════════════════════════════════════════════════\n";
    echo "                    DATABASE CONTENT CHECK                      \n";
    echo "═══════════════════════════════════════════════════════════════\n\n";
    
    // Check ahmed table
    echo "AHMED TABLE:\n";
    echo "───────────────────────────────────────────────────────────────\n";
    $result = $pdo->query('SELECT * FROM ahmed ORDER BY subject ASC');
    $ahmed_rows = $result->fetchAll(PDO::FETCH_ASSOC);
    echo "Total rows: " . count($ahmed_rows) . "\n\n";
    
    foreach ($ahmed_rows as $i => $row) {
        echo "Row " . ($i+1) . ": " . json_encode($row) . "\n";
    }
    
    echo "\nMOHAMED TABLE:\n";
    echo "───────────────────────────────────────────────────────────────\n";
    $result = $pdo->query('SELECT * FROM mohamed ORDER BY subject ASC');
    $mohamed_rows = $result->fetchAll(PDO::FETCH_ASSOC);
    echo "Total rows: " . count($mohamed_rows) . "\n\n";
    
    foreach ($ahmed_rows as $i => $row) {
        echo "Row " . ($i+1) . ": " . json_encode($row) . "\n";
    }
    
    echo "\nSTUDENT_DATA TABLE:\n";
    echo "───────────────────────────────────────────────────────────────\n";
    $result = $pdo->query('SELECT * FROM student_data');
    $students = $result->fetchAll(PDO::FETCH_ASSOC);
    echo "Total rows: " . count($students) . "\n\n";
    
    foreach ($students as $i => $row) {
        echo "Row " . ($i+1) . ": " . json_encode($row) . "\n";
    }
    
    echo "\n" . str_repeat("═", 65) . "\n";
    echo "Schema Check\n";
    echo str_repeat("═", 65) . "\n\n";
    
    foreach (['ahmed', 'mohamed', 'student_data'] as $table) {
        echo "Table: {$table}\n";
        $result = $pdo->query("SHOW COLUMNS FROM {$table}");
        $cols = $result->fetchAll(PDO::FETCH_ASSOC);
        foreach ($cols as $col) {
            echo "  - " . $col['Field'] . " (" . $col['Type'] . ")\n";
        }
        echo "\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
?>
