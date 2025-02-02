<?php
namespace Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        // Use $_ENV directly instead of getenv
        $host   = $_ENV['DB_HOST']   ?? null;
        $dbname = $_ENV['DB_NAME']   ?? null;
        $user   = $_ENV['DB_USER']   ?? null;
        $pass   = $_ENV['DB_PASS']   ?? null;

        // Check for missing variables
        if (!$host || !$dbname || $user === null || $pass === null) {
            throw new \Exception("Database credentials not set in \$_ENV. Check .env file and Dotenv.");
        }

        $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
        try {
            $this->connection = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->connection;
    }
}
