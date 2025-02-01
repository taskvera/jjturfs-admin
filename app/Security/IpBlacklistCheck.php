<?php
namespace App\Security;

use Core\GlobalLogger;

/**
 * Class IpBlacklistCheck
 *
 * Checks if the client's IP address is on a blacklist.
 */
class IpBlacklistCheck implements LoginCheckInterface {
    /**
     * @var array List of blacklisted IP addresses.
     */
    protected $blacklistedIps = [
        '192.168.1.100', // Example IP addresses
        '10.0.0.1'
    ];

    /**
     * @var GlobalLogger Logger for logging events.
     */
    protected $logger;

    /**
     * IpBlacklistCheck constructor.
     *
     * @param GlobalLogger $logger Global logger instance.
     */
    public function __construct(GlobalLogger $logger) {
        $this->logger = $logger;
    }

    /**
     * Perform the IP blacklist check.
     *
     * @param array $requestData Not used in this check.
     * @param array|null $user Not used in this check.
     * @return bool True if the client's IP is NOT blacklisted; false otherwise.
     */
    public function check(array $requestData, ?array $user = null): bool {
        $clientIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        // Convert IPv6 loopback to IPv4 loopback if desired.
    if ($clientIp === '::1') {
        $clientIp = '127.0.0.1';
    }
        $this->logger->debug("Checking IP against blacklist", ['clientIp' => $clientIp]);
        if (in_array($clientIp, $this->blacklistedIps)) {
            $this->logger->warn("Client IP is blacklisted", ['clientIp' => $clientIp]);
            return false;
        }
        return true;
    }

    /**
     * Get the failure message for a blacklisted IP.
     *
     * @return string
     */
    public function getFailureMessage(): string {
        return "Access denied from your IP address.";
    }
}
