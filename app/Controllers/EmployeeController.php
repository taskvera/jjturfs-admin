<?php
namespace App\Controllers;

use Core\GlobalLogger;
use App\Models\EmployeeModel;

/**
 * Class EmployeeController
 *
 * Responsible for handling Employee-related actions
 * such as listing (index), showing details, etc.
 */
class EmployeeController
{
    /**
     * @var GlobalLogger
     */
    protected $logger;

    /**
     * @var EmployeeModel
     */
    protected $employeeModel;

    /**
     * Constructor.
     *
     * @param GlobalLogger $logger
     */
    public function __construct(GlobalLogger $logger)
    {
        $this->logger = $logger;
        // Pass this logger into EmployeeModel so we can log inside the model as well
        $this->employeeModel = new EmployeeModel($logger);
    }

    /**
     * Index action: List all employees in an HTML view.
     *
     * @return void
     */
    public function index(): void
    {
        $this->logger->info("[EmployeeController] index() called.");

        // Get all employees from the model
        $employees = $this->employeeModel->getAllEmployees();

        // Log how many we retrieved
        $this->logger->debug("[EmployeeController] number of employees fetched", [
            'count' => count($employees)
        ]);

        // Load an HTML view to display them
        // (Create app/Views/HumanResources/AllEmployeesView.php or adapt the file name if needed)
        require_once __DIR__ . '/../Views/HumanResources/AllEmployeesView.php';
    }

    /**
     * Show a single employee record.
     *
     * @param int $id The ID of the employee.
     * @return void
     */
    public function show(int $id): void {
        $this->logger->info("[EmployeeController] show() called", [
            'employee_id' => $id
        ]);

        // Fetch the employee record.
        $employee = $this->employeeModel->getEmployeeById($id);

        if (!$employee) {
            $this->logger->warn("[EmployeeController] Employee not found in show()", [
                'employee_id' => $id
            ]);
            header("HTTP/1.0 404 Not Found");
            echo "404 - Employee not found.";
            return;
        }

        // Fetch permissions for this employee.
        $permissions = $this->employeeModel->getPermissions($id);
        // Merge permissions into the employee array (or pass as separate variable).
        $employee['permissions'] = $permissions;

        $this->logger->info("[EmployeeController] show() found employee", [
            'employee_id' => $employee['id'],
            'first_name'  => $employee['first_name'],
            'last_name'   => $employee['last_name']
        ]);

        // Load the view – the view now will have both $employee and $employee['permissions'] available.
        require_once __DIR__ . '/../Views/HumanResources/EmployeeRecordView.php';
    }

    /**
     * Save employee menu permissions.
     *
     * Expects a POST request with:
     * - employee_id
     * - menu[] (an array of menu options)
     *
     * Returns JSON.
     */
    public function savePermissions(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("HTTP/1.1 405 Method Not Allowed");
            echo json_encode(['error' => 'Method not allowed']);
            exit();
        }
        
        $employeeId = $_POST['employee_id'] ?? null;
        if (!$employeeId) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['error' => 'Employee ID is missing']);
            exit();
        }
        
        $menuOptions = $_POST['menu'] ?? [];
        
        // Call the model method to save the menu options
        $result = $this->employeeModel->saveMenuOptions((int)$employeeId, $menuOptions);
        
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(['error' => 'Failed to save menu options']);
        }
        exit();
    }


}
