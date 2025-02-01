<?php
namespace Core;

use PDO;
use PDOException;

/**
 * Class Database
 *
 * Provides a singleton PDO database connection.
 * Expects credentials to be set in environment variables with no fallback.
 *
 * Required Environment Variables:
 *   - DB_HOST
 *   - DB_NAME
 *   - DB_USER
 *   - DB_PASS
 *
 * @package Core
 */
class Database {
    /**
     * @var Database|null Singleton instance.
     */
    private static $instance = null;

    /**
     * @var PDO The PDO database connection.
     */
    private $connection;

    /**
     * Private constructor.
     *
     * @throws \Exception if any credential is missing or if the connection fails.
     */
    private function __construct() {
        $host = getenv('DB_HOST');
        $dbname = getenv('DB_NAME');
        $user = getenv('DB_USER');
        $pass = getenv('DB_PASS');

        if (!$host || !$dbname || $user === false || $pass === false) {
            throw new \Exception("Database credentials are not properly set in the environment. Please ensure DB_HOST, DB_NAME, DB_USER, and DB_PASS are defined.");
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

    /**
     * Get the singleton instance of Database.
     *
     * @return Database
     * @throws \Exception if the connection cannot be established.
     */
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Get the PDO connection.
     *
     * @return PDO
     */
    public function getConnection(): PDO {
        return $this->connection;
    }
}
