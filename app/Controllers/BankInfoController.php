<?php
namespace App\Controllers;

use Core\GlobalLogger;
use Core\Database;

class BankInfoController {
    protected $logger;

    public function __construct(GlobalLogger $logger) {
        $this->logger = $logger;
    }

    /**
     * Lookup a routing number:
     * - Validates the input.
     * - Calls the external API.
     * - Saves/updates the bank info in the internal database.
     * - Returns JSON to the caller.
     */
    public function lookup(): void {
        // Set proper header for JSON output.
        header('Content-Type: application/json');

        // Get the routing number from GET parameters.
        $rn = $_GET['rn'] ?? '';
        if (!preg_match('/^\d{9}$/', $rn)) {
            echo json_encode(['error' => 'Invalid routing number.']);
            return;
        }

        // Build the external API URL.
        $apiUrl = "https://www.routingnumbers.info/api/data.json?rn=" . $rn;

        // Use file_get_contents or cURL to fetch the API data.
        $response = file_get_contents($apiUrl);
        if ($response === false) {
            echo json_encode(['error' => 'Failed to retrieve data from external API.']);
            return;
        }

        // Decode the returned JSON.
        $data = json_decode($response, true);
        if ($data === null) {
            echo json_encode(['error' => 'Invalid JSON response from external API.']);
            return;
        }

        // Save or update the bank info in your internal database.
        $this->saveBankInfo($data);

        // Return the bank info to the UI.
        echo json_encode($data);
    }

    /**
     * Save (or update) bank info to your internal database.
     * Adjust the column names as needed to match your database schema.
     *
     * @param array $data The bank info returned from the external API.
     */
    protected function saveBankInfo(array $data): void {
        try {
            $db = Database::getInstance()->getConnection();

            // Assume your external API returns a key "routing_number" and "customer_name"
            // Adjust these keys to match the actual response structure.
            $routingNumber = $data['routing_number'] ?? '';
            $bankName      = $data['customer_name'] ?? '';
            $address       = $data['address'] ?? '';
            $city          = $data['city'] ?? '';
            $state         = $data['state'] ?? '';
            $zip           = $data['zip'] ?? '';
            $phone         = $data['phone'] ?? '';

            // Check if a record for this routing number already exists.
            $stmt = $db->prepare("SELECT COUNT(*) AS count FROM bank_info WHERE routing_number = :rn");
            $stmt->execute(['rn' => $routingNumber]);
            $row = $stmt->fetch();

            if ($row && $row['count'] > 0) {
                // Record exists—update it.
                $sql = "UPDATE bank_info SET 
                            bank_name = :bank_name,
                            address = :address,
                            city = :city,
                            state = :state,
                            zip = :zip,
                            phone = :phone,
                            updated_at = NOW()
                        WHERE routing_number = :rn";
            } else {
                // New record—insert it.
                $sql = "INSERT INTO bank_info (
                            routing_number, bank_name, address, city, state, zip, phone, created_at, updated_at
                        ) VALUES (
                            :rn, :bank_name, :address, :city, :state, :zip, :phone, NOW(), NOW()
                        )";
            }

            $stmt = $db->prepare($sql);
            $stmt->execute([
                'rn'        => $routingNumber,
                'bank_name' => $bankName,
                'address'   => $address,
                'city'      => $city,
                'state'     => $state,
                'zip'       => $zip,
                'phone'     => $phone
            ]);
        } catch (\Exception $e) {
            // Log the error but do not block the user’s experience.
            $this->logger->error("Failed to save bank info", ['error' => $e->getMessage()]);
        }
    }
}
