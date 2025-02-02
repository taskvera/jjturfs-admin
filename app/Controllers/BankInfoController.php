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

        // Use file_get_contents (or cURL) to fetch the API data.
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
     *
     * The method now:
     * - Extracts all relevant fields from the API response.
     * - Checks if a record with the given routing_number exists.
     * - If it exists, compares the stored change_date with the new change_date.
     *   - If they differ, updates the record with the new info.
     *   - Otherwise, only updates the updated_at field.
     * - If no record exists, inserts a new record.
     *
     * @param array $data The bank info returned from the external API.
     */
    protected function saveBankInfo(array $data): void {
        try {
            $db = Database::getInstance()->getConnection();

            // Extract fields from the API response.
            $routingNumber         = $data['routing_number'] ?? '';
            $rnField               = $data['rn'] ?? '';
            $newRoutingNumber      = $data['new_routing_number'] ?? '';
            $customerName          = $data['customer_name'] ?? '';
            $address               = $data['address'] ?? '';
            $city                  = $data['city'] ?? '';
            $state                 = $data['state'] ?? '';
            $zip                   = $data['zip'] ?? '';
            $telephone             = $data['telephone'] ?? '';
            $officeCode            = $data['office_code'] ?? '';
            $institutionStatusCode = $data['institution_status_code'] ?? '';
            $dataViewCode          = $data['data_view_code'] ?? '';
            $code                  = $data['code'] ?? '';
            $message               = $data['message'] ?? '';
            $recordTypeCode        = $data['record_type_code'] ?? '';
            $changeDate            = $data['change_date'] ?? ''; // e.g., "091012"

            // Check if a record for this routing number already exists.
            $stmt = $db->prepare("SELECT change_date FROM bank_info WHERE routing_number = :rn");
            $stmt->execute(['rn' => $routingNumber]);
            $existingRecord = $stmt->fetch();

            if ($existingRecord) {
                // If a record exists, compare the stored change_date with the new change_date.
                if ($existingRecord['change_date'] !== $changeDate) {
                    // If they differ, update the record with the new info.
                    $sql = "UPDATE bank_info SET 
                                rn = :rn_field,
                                new_routing_number = :new_rn,
                                customer_name = :customer_name,
                                address = :address,
                                city = :city,
                                state = :state,
                                zip = :zip,
                                telephone = :telephone,
                                office_code = :office_code,
                                institution_status_code = :institution_status_code,
                                data_view_code = :data_view_code,
                                code = :code,
                                message = :message,
                                record_type_code = :record_type_code,
                                change_date = :change_date,
                                updated_at = NOW()
                            WHERE routing_number = :routing_number";
                    $params = [
                        'rn_field'          => $rnField,
                        'new_rn'            => $newRoutingNumber,
                        'customer_name'     => $customerName,
                        'address'           => $address,
                        'city'              => $city,
                        'state'             => $state,
                        'zip'               => $zip,
                        'telephone'         => $telephone,
                        'office_code'       => $officeCode,
                        'institution_status_code' => $institutionStatusCode,
                        'data_view_code'    => $dataViewCode,
                        'code'              => $code,
                        'message'           => $message,
                        'record_type_code'  => $recordTypeCode,
                        'change_date'       => $changeDate,
                        'routing_number'    => $routingNumber
                    ];
                } else {
                    // If change_date is the same, update only the updated_at timestamp.
                    $sql = "UPDATE bank_info SET updated_at = NOW() WHERE routing_number = :routing_number";
                    $params = ['routing_number' => $routingNumber];
                }
            } else {
                // No existing record – insert a new record with all the returned info.
                $sql = "INSERT INTO bank_info (
                            routing_number, rn, new_routing_number, customer_name, address, city, state, zip,
                            telephone, office_code, institution_status_code, data_view_code, code, message,
                            record_type_code, change_date, created_at, updated_at
                        ) VALUES (
                            :routing_number, :rn_field, :new_rn, :customer_name, :address, :city, :state, :zip,
                            :telephone, :office_code, :institution_status_code, :data_view_code, :code, :message,
                            :record_type_code, :change_date, NOW(), NOW()
                        )";
                $params = [
                    'routing_number'      => $routingNumber,
                    'rn_field'            => $rnField,
                    'new_rn'              => $newRoutingNumber,
                    'customer_name'       => $customerName,
                    'address'             => $address,
                    'city'                => $city,
                    'state'               => $state,
                    'zip'                 => $zip,
                    'telephone'           => $telephone,
                    'office_code'         => $officeCode,
                    'institution_status_code' => $institutionStatusCode,
                    'data_view_code'      => $dataViewCode,
                    'code'                => $code,
                    'message'             => $message,
                    'record_type_code'    => $recordTypeCode,
                    'change_date'         => $changeDate
                ];
            }

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
        } catch (\Exception $e) {
            // Log the error but do not block the user's experience.
            $this->logger->error("Failed to save bank info", ['error' => $e->getMessage()]);
        }
    }
}
