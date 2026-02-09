<?php

namespace App\Config;

use Dotenv\Dotenv;
use PDO;
use PDOException;

class Database
{
    private static ?PDO $conn = null;

    public static function connect(): PDO
    {
        if (self::$conn !== null) {
            return self::$conn;
        }

        // Load .env ONLY if exists (local development)
        $envPath = __DIR__ . '/../../.env';
        if (file_exists($envPath)) {
            $dotenv = Dotenv::createImmutable(dirname($envPath));
            $dotenv->load();
        }

        $dbHost    = $_ENV['DB_HOST'] ?? getenv('DB_HOST');
        $dbName    = $_ENV['DB_NAME'] ?? getenv('DB_NAME');
        $dbUser    = $_ENV['DB_USER'] ?? getenv('DB_USER');
        $dbPass    = $_ENV['DB_PASS'] ?? getenv('DB_PASS');
        $dbCharset = $_ENV['DB_CHARSET'] ?? getenv('DB_CHARSET') ?? 'utf8mb4';

        $dsn = "mysql:host={$dbHost};dbname={$dbName};charset={$dbCharset}";

        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$dbCharset}",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            self::$conn = new PDO($dsn, $dbUser, $dbPass, $options);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }

        return self::$conn;
    }

    public static function getInstance(): PDO
    {
        return self::connect();
    }
}
