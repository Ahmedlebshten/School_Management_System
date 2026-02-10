<?php

$host = getenv('DB_HOST') ?: 'mysql';
$db   = getenv('DB_NAME') ?: 'school';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: 'root';
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