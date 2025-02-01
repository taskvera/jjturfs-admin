<?php
namespace App\Security;

use Core\GlobalLogger;
use Core\Database;
use App\Security\LoginCheckInterface;

/**
 * Class IpBlacklistCheck
 *
 * Checks if the client's IP address is on a blacklist stored in the database.
 */
class IpBlacklistCheck implements LoginCheckInterface {
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
     * Perform the IP blacklist check against the database.
     *
     * Uses MySQL’s INET_ATON (IPv4) or INET6_ATON (IPv6) to compare the client IP against stored ranges.
     *
     * @param array $requestData Not used in this check.
     * @param array|null $user Not used in this check.
     * @return bool True if the client's IP is NOT blacklisted; false otherwise.
     */
    public function check(array $requestData, ?array $user = null): bool {
        $clientIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

        // Convert IPv6 loopback to IPv4 if needed.
        if ($clientIp === '::1') {
            $clientIp = '127.0.0.1';
        }

        $this->logger->debug("Checking IP against blacklist (DB)", ['clientIp' => $clientIp]);

        try {
            $db = Database::getInstance()->getConnection();

            // Determine if the client IP is IPv4 or IPv6.
            if (filter_var($clientIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $query = "SELECT COUNT(*) AS cnt 
                          FROM ip_blacklist 
                          WHERE active = 1 
                            AND ip_version = 4 
                            AND INET_ATON(ip_from) <= INET_ATON(:clientIp) 
                            AND INET_ATON(ip_to) >= INET_ATON(:clientIp)";
            } else {
                $query = "SELECT COUNT(*) AS cnt 
                          FROM ip_blacklist 
                          WHERE active = 1 
                            AND ip_version = 6 
                            AND INET6_ATON(ip_from) <= INET6_ATON(:clientIp) 
                            AND INET6_ATON(ip_to) >= INET6_ATON(:clientIp)";
            }

            $stmt = $db->prepare($query);
            $stmt->execute(['clientIp' => $clientIp]);
            $row = $stmt->fetch();

            if ($row && $row['cnt'] > 0) {
                $this->logger->warn("Client IP is blacklisted (DB)", ['clientIp' => $clientIp]);
                return false;
            }
        } catch (\Exception $e) {
            $this->logger->error("Error querying IP blacklist from DB", ['error' => $e->getMessage()]);
            // In case of error, it might be safer to deny access (fail closed).
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
