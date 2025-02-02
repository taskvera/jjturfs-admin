<?php
namespace App\Controllers;

use Core\GlobalLogger;
use App\Models\VendorModel;

class VendorController
{
    /**
     * @var GlobalLogger
     */
    protected $logger;

    /**
     * @var VendorModel
     */
    protected $vendorModel;

    /**
     * Constructor.
     *
     * @param GlobalLogger $logger
     */
    public function __construct(GlobalLogger $logger)
    {
        $this->logger = $logger;
        // Instantiate VendorModel with the logger
        $this->vendorModel = new VendorModel($logger);
    }

    /**
     * Index action: List all vendors.
     *
     * @return void
     */
    public function index(): void
    {
        $this->logger->info("[VendorController] index() called.");

        // Get all vendors from the model
        $vendors = $this->vendorModel->getAllVendors();

        // Log the count
        $this->logger->debug("[VendorController] number of vendors fetched", [
            'count' => count($vendors)
        ]);

        // Load the view to display vendors
        require_once __DIR__ . '/../Views/Vendors/VendorsIndexView.php';
    }

    /**
     * Show a single vendor record.
     *
     * @param int $id The ID of the vendor.
     * @return void
     */
    public function show(int $id): void
    {
        $this->logger->info("[VendorController] show() called", [
            'vendor_id' => $id
        ]);

        // Fetch the vendor record
        $vendor = $this->vendorModel->getVendorById($id);

        if (!$vendor) {
            $this->logger->warn("[VendorController] Vendor not found", ['vendor_id' => $id]);
            header("HTTP/1.0 404 Not Found");
            echo "404 - Vendor not found.";
            return;
        }

        $this->logger->info("[VendorController] show() found vendor", [
            'vendor_id' => $vendor['id'],
            'name' => $vendor['name']
        ]);

        // Load the vendor detail view
        require_once __DIR__ . '/../Views/Vendors/VendorRecordView.php';
    }
}
