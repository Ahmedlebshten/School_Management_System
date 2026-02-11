<?php
/**
 * ULTRA SIMPLE: Direct PDO connection
 * No app classes, no bootstrapping, just raw PHP + PDO
 */

echo "\n═══════════════════════════════════════════════════════════════\n";
echo "ULTRA SIMPLE TEST - Direct PDO Query\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

try {
    // Read database credentials from environment variables only (no fallbacks)
    $host = getenv('DB_HOST');
    $db   = getenv('DB_NAME');
    $user = getenv('DB_USER');
    $pass = getenv('DB_PASS');
    $charset = getenv('DB_CHARSET') ?: 'utf8mb4';
    
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    
    // Direct connection to MySQL using environment variables
    $pdo = new PDO(
        $dsn,
        $user,
        $pass,
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
