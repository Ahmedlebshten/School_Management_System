<?php
/**
 * ULTRA SIMPLE: Direct PDO connection
 * No app classes, no bootstrapping, just raw PHP + PDO
 */

echo "\n═══════════════════════════════════════════════════════════════\n";
echo "ULTRA SIMPLE TEST - Direct PDO Query\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

try {
    // Direct connection to Docker MySQL (no app classes)
    $pdo = new PDO(
        'mysql:host=db;dbname=school;charset=utf8mb4',
        'root',
        'password',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
    
    echo "✓ Connected to database\n\n";
    
    // Test 1: Query with plain PDO
    echo "Test 1: Query ahmed table with plain PDO\n";
    echo "─────────────────────────────────────────────────────────────\n";
    
    $sql = "SELECT * FROM `ahmed`";
    echo "SQL: {$sql}\n\n";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    
    echo "Rows fetched: " . count($rows) . "\n";
    echo "Data:\n";
    foreach ($rows as $i => $row) {
        echo "  [" . ($i+1) . "] " . json_encode($row) . "\n";
    }
    
    echo "\n" . str_repeat("─", 65) . "\n\n";
    
    // Test 2: Try loop
    echo "Test 2: Loop through rows\n";
    echo "─────────────────────────────────────────────────────────────\n";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $count = 0;
    while ($row = $stmt->fetch()) {
        $count++;
        echo "Row {$count}: " . json_encode($row) . "\n";
    }
    
    echo "\nTotal looped: {$count} rows\n\n";
    
    // Test 3: Direct query
    echo "Test 3: Direct query (no prepare)\n";
    echo "─────────────────────────────────────────────────────────────\n";
    
    $result = $pdo->query("SELECT COUNT(*) as cnt FROM `ahmed`");
    $count_row = $result->fetch();
    echo "COUNT(*): " . $count_row['cnt'] . " rows\n\n";
    
    echo "═══════════════════════════════════════════════════════════════\n";
    echo "Test complete.\n";
    
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
?>
