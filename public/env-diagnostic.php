<?php
/**
 * Environment Variable Diagnostic
 * 
 * This script shows what environment variables PHP can actually see
 * and helps diagnose configuration issues.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "═══════════════════════════════════════════════════════════════\n";
echo "          PHP ENVIRONMENT VARIABLES DIAGNOSTIC\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

echo "PHP Configuration:\n";
echo "  PHP Version: " . phpversion() . "\n";
echo "  OS: " . php_uname() . "\n";
echo "  Display Errors: " . (ini_get('display_errors') ? 'ON' : 'OFF') . "\n\n";

echo "Database Environment Variables (from getenv):\n";
echo "───────────────────────────────────────────────────────────────\n";
$db_vars = ['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'DB_CHARSET', 'DB_PORT'];
foreach ($db_vars as $var) {
    $value = getenv($var);
    $status = ($value !== false) ? "✓ SET" : "✗ MISSING";
    echo "  {$var}: {$status}";
    if ($value !== false) {
        echo " = " . ($var === 'DB_PASS' ? '***' : $value);
    }
    echo "\n";
}

echo "\nAll Environment Variables (via getenv):\n";
echo "───────────────────────────────────────────────────────────────\n";
$env = getenv();
if (empty($env)) {
    echo "  ✗ getenv() returned empty array!\n";
} else {
    echo "  Total variables: " . count($env) . "\n";
    echo "  First 10 variables:\n";
    foreach (array_slice($env, 0, 10) as $key => $val) {
        $display_val = (strlen($val) > 50) ? substr($val, 0, 47) . "..." : $val;
        echo "    {$key} = {$display_val}\n";
    }
}

echo "\n$_SERVER Variables (HTTP_* and DB_*):\n";
echo "───────────────────────────────────────────────────────────────\n";
foreach ($_SERVER as $key => $val) {
    if (strpos($key, 'DB_') === 0 || strpos($key, 'HTTP_') === 0) {
        $display_val = (strlen($val) > 50) ? substr($val, 0, 47) . "..." : $val;
        echo "  {$key} = {$display_val}\n";
    }
}

echo "\n.env File Check:\n";
echo "───────────────────────────────────────────────────────────────\n";
$env_path = __DIR__ . '/../.env';
if (file_exists($env_path)) {
    echo "  ✓ File exists: {$env_path}\n";
    echo "  Size: " . filesize($env_path) . " bytes\n";
    echo "  Readable: " . (is_readable($env_path) ? "YES" : "NO") . "\n";
} else {
    echo "  ✗ File does not exist: {$env_path}\n";
}

echo "\nDotenv Status:\n";
echo "───────────────────────────────────────────────────────────────\n";
if (class_exists('Dotenv\Dotenv')) {
    echo "  ✓ Dotenv class is available (package installed)\n";
    echo "  ✗ But it should NOT be loaded automatically\n";
} else {
    echo "  ✓ Dotenv is not loaded in memory\n";
}

echo "\nNetwork Test:\n";
echo "───────────────────────────────────────────────────────────────\n";
$db_host = getenv('DB_HOST');
if ($db_host) {
    echo "  Testing DNS resolution for: {$db_host}\n";
    $ip = gethostbyname($db_host);
    if ($ip !== $db_host) {
        echo "  ✓ DNS resolved: {$db_host} → {$ip}\n";
    } else {
        echo "  ✗ DNS resolution failed for: {$db_host}\n";
    }
} else {
    echo "  ✗ DB_HOST not set, cannot test DNS resolution\n";
}

echo "\n════════════════════════════════════════════════════════════════\n";
?>
