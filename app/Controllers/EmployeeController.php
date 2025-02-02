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
    public function show(int $id): void
    {
        $this->logger->info("[EmployeeController] show() called", [
            'employee_id' => $id
        ]);

        // Fetch the employee
        $employee = $this->employeeModel->getEmployeeById($id);

        if (!$employee) {
            $this->logger->warn("[EmployeeController] Employee not found in show()", [
                'employee_id' => $id
            ]);
            // You might choose to show a 404 page, or redirect, etc.
            header("HTTP/1.0 404 Not Found");
            echo "404 - Employee not found.";
            return;
        }

        // If we have a valid record, log some details
        $this->logger->info("[EmployeeController] show() found employee", [
            'employee_id' => $employee['id'],
            'first_name'  => $employee['first_name'],
            'last_name'   => $employee['last_name']
        ]);

        // Load a view to show employee details
        // (Create app/Views/EmployeeShowView.php if it doesn’t already exist)
        require_once __DIR__ . '/../Views/EmployeeShowView.php';
    }
}
