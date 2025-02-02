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

    /**
     * Save menu options for an employee.
     *
     * @param int $employeeId
     * @param array $menuOptions
     * @return bool True on success, false on failure.
     */
    public function saveMenuOptions(int $employeeId, array $menuOptions): bool {
        try {
            $db = \Core\Database::getInstance()->getConnection();
            $menuJson = json_encode($menuOptions);
            
            // Check if a record already exists
            $stmt = $db->prepare("SELECT employee_id FROM employee_permissions WHERE employee_id = :employee_id");
            $stmt->execute(['employee_id' => $employeeId]);
            $existing = $stmt->fetch();
            
            if ($existing) {
                // Update existing record
                $sql = "UPDATE employee_permissions SET menu_options = :menu_options WHERE employee_id = :employee_id";
            } else {
                // Insert a new record
                $sql = "INSERT INTO employee_permissions (employee_id, menu_options) VALUES (:employee_id, :menu_options)";
            }
            
            $stmt = $db->prepare($sql);
            $stmt->execute(['employee_id' => $employeeId, 'menu_options' => $menuJson]);
            return true;
        } catch (\Exception $e) {
            if ($this->logger) {
                $this->logger->error("[EmployeeModel] saveMenuOptions() exception", [
                    'error' => $e->getMessage(),
                    'employee_id' => $employeeId
                ]);
            }
            return false;
        }
    }

        /**
     * Get permissions for a given employee.
     *
     * @param int $employeeId
     * @return array|null Returns an associative array with keys "menu_options" and "crud_options" or null if none found.
     */
    public function getPermissions(int $employeeId): ?array {
        try {
            $db = \Core\Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT menu_options, crud_options FROM employee_permissions WHERE employee_id = :employee_id");
            $stmt->execute(['employee_id' => $employeeId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                // Decode JSON strings into arrays (assuming they are stored as JSON)
                $result['menu_options'] = json_decode($result['menu_options'], true);
                $result['crud_options'] = json_decode($result['crud_options'], true);
                return $result;
            }
            return null;
        } catch (\Exception $e) {
            if ($this->logger) {
                $this->logger->error("[EmployeeModel] getPermissions() exception", [
                    'error' => $e->getMessage(),
                    'employee_id' => $employeeId
                ]);
            }
            return null;
        }
    }
}
