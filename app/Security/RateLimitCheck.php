<?php
namespace App\Security;

use Core\GlobalLogger;
use Core\Database;

/**
 * Class RateLimitCheck
 *
 * Implements a persistent, database-driven rate limiting system
 * using a "login_rate_limit" table to track attempts by IP address.
 * This class logs every step, so you can track exactly what's happening.
 */
class RateLimitCheck implements LoginCheckInterface {
    /**
     * @var int Maximum allowed login attempts per IP.
     */
    protected $maxAttempts = 5;

    /**
     * @var int Time window in seconds for rate limiting.
     * If the user exceeds maxAttempts within this period, the check fails.
     */
    protected $timeWindow = 300; // 5 minutes

    /**
     * @var GlobalLogger Logger instance for detailed logging.
     */
    protected $logger;

    /**
     * Constructor.
     *
     * @param GlobalLogger $logger Global logger instance.
     */
    public function __construct(GlobalLogger $logger) {
        $this->logger = $logger;
    }

    /**
     * Check if the login attempts from the client's IP exceed the allowed rate.
     *
     * @param array      $requestData Not used directly here (but could read user data).
     * @param array|null $user        Not used in this check, but you could incorporate user logic if needed.
     * @return bool True if the rate limit is NOT exceeded, false otherwise.
     */
    public function check(array $requestData, ?array $user = null): bool {
        // 1. Identify the client's IP address
        $clientIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

        // Convert IPv6 loopback to IPv4 if desired
        if ($clientIp === '::1') {
            $clientIp = '127.0.0.1';
        }

        $this->logger->debug("RateLimitCheck: Starting check for IP", ['clientIp' => $clientIp]);

        // 2. Fetch the current attempt count from DB
        $currentRecord = $this->getRateLimitRecord($clientIp);

        if ($currentRecord === null) {
            // If no record, create a new one with attempt = 1
            $this->logger->debug("RateLimitCheck: No existing record, creating a new one", ['clientIp' => $clientIp]);
            $this->createNewRecord($clientIp);
            return true;
        }

        // 3. Check if we've exceeded the time window
        if ($this->hasTimeWindowExpired($currentRecord)) {
            // If the time window is expired, reset attempts to 1
            $this->logger->debug("RateLimitCheck: Time window expired; resetting attempts", [
                'clientIp' => $clientIp,
                'attempts' => $currentRecord['attempts']
            ]);
            $this->resetRecord($clientIp);
            return true;
        }

        // 4. Increment the attempt count
        $updatedAttempts = $currentRecord['attempts'] + 1;
        $this->updateAttempts($clientIp, $updatedAttempts);

        // 5. Compare attempts to maxAttempts
        $this->logger->debug("RateLimitCheck: IP attempt incremented", [
            'clientIp' => $clientIp,
            'attempts' => $updatedAttempts,
            'maxAllowed' => $this->maxAttempts
        ]);

        if ($updatedAttempts > $this->maxAttempts) {
            // Rate limit exceeded
            $this->logger->warn("RateLimitCheck: Rate limit exceeded", [
                'clientIp' => $clientIp,
                'attempts' => $updatedAttempts,
                'timeWindow' => $this->timeWindow
            ]);
            return false;
        }

        // If we haven't exceeded the limit, allow the login attempt
        return true;
    }

    /**
     * Return an error/failure message if the rate limit is exceeded.
     *
     * @return string
     */
    public function getFailureMessage(): string {
        return "Too many login attempts. Please try again later.";
    }

    /**
     * Fetch the row from "login_rate_limit" for this IP (if it exists).
     *
     * @param string $ip The IP address.
     * @return array|null The record as an associative array, or null if not found.
     */
    protected function getRateLimitRecord(string $ip): ?array {
        try {
            $db = Database::getInstance()->getConnection();
            $query = "SELECT id, ip_address, attempts, first_attempt, last_attempt 
                      FROM login_rate_limit
                      WHERE ip_address = :ip
                      LIMIT 1";
            $stmt = $db->prepare($query);
            $stmt->execute(['ip' => $ip]);
            $record = $stmt->fetch();
            if (!$record) {
                return null; // No existing row for this IP
            }
            return $record;
        } catch (\Exception $e) {
            $this->logger->error("RateLimitCheck: DB error retrieving record", ['error' => $e->getMessage()]);
            // In case of DB error, fail closed (deny login) or fail open as needed. Here we fail open for demonstration.
            return null;
        }
    }

    /**
     * Check if the rate limit time window has expired for this record.
     *
     * @param array $record The record from the DB.
     * @return bool True if the window is expired, false otherwise.
     */
    protected function hasTimeWindowExpired(array $record): bool {
        $firstAttemptTime = strtotime($record['first_attempt'] ?? 'now');
        $elapsed = time() - $firstAttemptTime;
        return $elapsed > $this->timeWindow;
    }

    /**
     * Create a new record for this IP with attempts = 1, first_attempt = now.
     *
     * @param string $ip The IP address.
     * @return void
     */
    protected function createNewRecord(string $ip): void {
        try {
            $db = Database::getInstance()->getConnection();
            $query = "INSERT INTO login_rate_limit (ip_address, attempts, first_attempt, last_attempt)
                      VALUES (:ip, 1, NOW(), NOW())";
            $stmt = $db->prepare($query);
            $stmt->execute(['ip' => $ip]);
            $this->logger->debug("RateLimitCheck: New record created", ['clientIp' => $ip]);
        } catch (\Exception $e) {
            $this->logger->error("RateLimitCheck: DB error creating new record", ['error' => $e->getMessage()]);
        }
    }

    /**
     * Increment or update the attempt count for this IP.
     *
     * @param string $ip The IP address.
     * @param int    $newAttempts The new attempt count.
     * @return void
     */
    protected function updateAttempts(string $ip, int $newAttempts): void {
        try {
            $db = Database::getInstance()->getConnection();
            $query = "UPDATE login_rate_limit
                      SET attempts = :attempts, last_attempt = NOW()
                      WHERE ip_address = :ip";
            $stmt = $db->prepare($query);
            $stmt->execute(['attempts' => $newAttempts, 'ip' => $ip]);
            $this->logger->debug("RateLimitCheck: Updated attempts in DB", [
                'clientIp' => $ip,
                'newAttempts' => $newAttempts
            ]);
        } catch (\Exception $e) {
            $this->logger->error("RateLimitCheck: DB error updating attempts", ['error' => $e->getMessage()]);
        }
    }

    /**
     * Reset attempts when the time window expires (start a new window).
     *
     * @param string $ip The IP address.
     * @return void
     */
    protected function resetRecord(string $ip): void {
        try {
            $db = Database::getInstance()->getConnection();
            $query = "UPDATE login_rate_limit
                      SET attempts = 1,
                          first_attempt = NOW(),
                          last_attempt = NOW()
                      WHERE ip_address = :ip";
            $stmt = $db->prepare($query);
            $stmt->execute(['ip' => $ip]);
            $this->logger->debug("RateLimitCheck: Record reset for new window", ['clientIp' => $ip]);
        } catch (\Exception $e) {
            $this->logger->error("RateLimitCheck: DB error resetting record", ['error' => $e->getMessage()]);
        }
    }
}
