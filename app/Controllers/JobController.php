<?php
namespace App\Controllers;

use Core\GlobalLogger;
use Core\Database;

class JobController {
    /**
     * @var GlobalLogger
     */
    protected $logger;

    /**
     * JobController constructor.
     *
     * @param GlobalLogger $logger
     */
    public function __construct(GlobalLogger $logger) {
        $this->logger = $logger;
    }

    /**
     * List available jobs.
     *
     * This method queries the jobs table for active/open jobs,
     * then returns a JSON payload with the job data.
     *
     * @return void
     */
    public function list(): void {
        // Set the header to return JSON
        header('Content-Type: application/json');

        try {
            $db = Database::getInstance()->getConnection();

            // Query the jobs table for active jobs.
            // Adjust the table name, columns, and WHERE clause as needed.
            $query = "SELECT id, title, location, department, description, detail_url
                      FROM jobs
                      WHERE active = 1"; // Or use a status column, etc.
            $stmt = $db->prepare($query);
            $stmt->execute();
            $jobs = $stmt->fetchAll();

            // Optionally, you might want to include additional metadata.
            $response = [
                'jobs'  => $jobs,
                'total' => count($jobs)
            ];

            echo json_encode($response);
        } catch (\Exception $e) {
            // Log the error and return an error response.
            $this->logger->error("Error fetching jobs", ['error' => $e->getMessage()]);
            echo json_encode(['error' => 'Unable to fetch jobs.']);
        }
    }
}
