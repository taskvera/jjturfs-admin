<?php
namespace App\Models;

use Core\Database;
use Core\GlobalLogger;
use PDO;

class VendorModel
{
    /**
     * @var GlobalLogger|null
     */
    protected $logger;

    /**
     * Constructor. Optionally inject a GlobalLogger.
     *
     * @param GlobalLogger|null $logger
     */
    public function __construct(?GlobalLogger $logger = null)
    {
        $this->logger = $logger ?? GlobalLogger::getInstance();
    }

    /**
     * Retrieve all vendors from the database.
     *
     * @return array
     */
    public function getAllVendors(): array
    {
        if ($this->logger) {
            $this->logger->debug("[VendorModel] getAllVendors() called");
        }

        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT id, name, email, contact, location, created_at FROM vendors ORDER BY id ASC";

            if ($this->logger) {
                $this->logger->debug("[VendorModel] Running query", ['sql' => $sql]);
            }

            $stmt = $db->prepare($sql);
            $stmt->execute();
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($this->logger) {
                $this->logger->debug("[VendorModel] getAllVendors() returned", ['count' => count($records)]);
            }

            return $records ?: [];
        } catch (\Exception $e) {
            if ($this->logger) {
                $this->logger->error("[VendorModel] getAllVendors() exception", ['error' => $e->getMessage()]);
            }
            return [];
        }
    }

    /**
     * Retrieve a single vendor record by ID.
     *
     * @param int $id
     * @return array|null
     */
    public function getVendorById(int $id): ?array
    {
        if ($this->logger) {
            $this->logger->info("[VendorModel] getVendorById() called", ['id' => $id]);
        }

        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT id, name, email, contact, location, created_at FROM vendors WHERE id = :id LIMIT 1";

            if ($this->logger) {
                $this->logger->debug("[VendorModel] Running query for vendor", [
                    'sql' => $sql,
                    'param_id' => $id,
                ]);
            }

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $record = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($record === false) {
                if ($this->logger) {
                    $this->logger->warn("[VendorModel] getVendorById() returned no rows", ['id' => $id]);
                }
                return null;
            } else {
                if ($this->logger) {
                    $this->logger->info("[VendorModel] getVendorById() found vendor", [
                        'id' => $record['id'],
                        'name' => $record['name']
                    ]);
                }
                return $record;
            }
        } catch (\Exception $e) {
            if ($this->logger) {
                $this->logger->error("[VendorModel] getVendorById() exception", [
                    'error' => $e->getMessage(),
                    'id' => $id
                ]);
            }
            return null;
        }
    }
}
