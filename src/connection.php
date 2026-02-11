<?php

// Read database configuration from environment variables only (no fallbacks).
// In Kubernetes, these are provided by ConfigMaps/Secrets.
// In Docker Compose, these are in the services environment section.
// If any variable is missing, the connection will fail with a clear error.
$host = getenv('DB_HOST');
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$charset = getenv('DB_CHARSET') ?: 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $charset",
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
];

try {
    $conn = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("failed: " . $e->getMessage());
}