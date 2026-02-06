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

        // Load environment variables
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $dsn = "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'] . ";charset=" . $_ENV['DB_CHARSET'];
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASS'];

        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . $_ENV['DB_CHARSET'],
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            self::$conn = new PDO($dsn, $user, $pass, $options);
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
