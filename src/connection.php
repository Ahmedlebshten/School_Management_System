<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Load .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Read from .env
$dsn = "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'] . ";charset=" . $_ENV['DB_CHARSET'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];

$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . $_ENV['DB_CHARSET'],
);

try {
    $conn = new PDO($dsn, $user, $pass, $options);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "failed: " . $e->getMessage();
}
?>
