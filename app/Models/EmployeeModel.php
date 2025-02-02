<?php
namespace App\Models;

use Core\Database;
use Core\GlobalLogger;  // We can log from here too
use PDO;

class EmployeeModel
{
    /**
     * @var GlobalLogger|null
     */
    protected $logger;

    /**
     * Optional: Accept a GlobalLogger if you like, or you can get an instance directly.
     *
     * @param GlobalLogger|null $logger
     */
    public function __construct(?GlobalLogger $logger = null)
    {
        // If no logger injected, get the global instance
        $this->logger = $logger ?? GlobalLogger::getInstance();
    }

    /**
     * Retrieve all employees from the database.
     *
     * @return array
     */
    public function getAllEmployees(): array
    {
        if ($this->logger) {
            $this->logger->debug("[EmployeeModel] getAllEmployees() called");
        }

        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT
                        id,
                        first_name,
                        last_name,
                        email,
                        position,
                        department,
                        location,
                        created_at
                    FROM employees
                    ORDER BY id ASC";

            if ($this->logger) {
                $this->logger->debug("[EmployeeModel] About to run getAllEmployees() query", [
                    'sql' => $sql
                ]);
            }

            $stmt = $db->prepare($sql);
            $stmt->execute();
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($this->logger) {
                $this->logger->debug("[EmployeeModel] getAllEmployees() returned records", [
                    'count' => count($records)
                ]);
            }

            return $records ?: [];
        } catch (\Exception $e) {
            if ($this->logger) {
                $this->logger->error("[EmployeeModel] getAllEmployees() exception", [
                    'error' => $e->getMessage()
                ]);
            }
            return [];
        }
    }

    /**
     * Get a single employee record by ID, with detailed logs.
     *
     * @param int $id
     * @return array|null
     */
    public function getEmployeeById(int $id): ?array
    {
        if ($this->logger) {
            $this->logger->info("[EmployeeModel] getEmployeeById() called", ['id' => $id]);
        }

        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT
                        id,
                        first_name,
                        last_name,
                        email,
                        position,
                        department,
                        location,
                        created_at
                    FROM employees
                    WHERE id = :id
                    LIMIT 1";

            if ($this->logger) {
                $this->logger->debug("[EmployeeModel] Query for getEmployeeById()", [
                    'sql' => $sql,
                    'param_id' => $id,
                ]);
            }

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $record = $stmt->fetch(PDO::FETCH_ASSOC);

            // Log details about the returned record
            if ($record === false) {
                if ($this->logger) {
                    $this->logger->warn("[EmployeeModel] getEmployeeById() returned no rows", [
                        'id' => $id
                    ]);
                }
                return null;
            } else {
                if ($this->logger) {
                    $this->logger->info("[EmployeeModel] getEmployeeById() found an employee record", [
                        'id' => $record['id'],
                        'first_name' => $record['first_name'],
                        'last_name' => $record['last_name']
                    ]);
                }
                return $record;
            }
        } catch (\Exception $e) {
            if ($this->logger) {
                $this->logger->error("[EmployeeModel] getEmployeeById() exception", [
                    'error' => $e->getMessage(),
                    'id' => $id
                ]);
            }
            return null;
        }
    }
}
