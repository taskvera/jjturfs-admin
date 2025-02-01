<?php
namespace App\Security;

use Core\GlobalLogger;

/**
 * Class RateLimitCheck
 *
 * Checks if the login attempts exceed the allowed rate.
 */
class RateLimitCheck implements LoginCheckInterface {
    /**
     * @var int Maximum allowed login attempts per IP.
     */
    protected $maxAttempts = 5;

    /**
     * @var int Time window in seconds for rate limiting.
     */
    protected $timeWindow = 300; // 5 minutes

    /**
     * @var GlobalLogger Logger instance.
     */
    protected $logger;

    /**
     * RateLimitCheck constructor.
     *
     * @param GlobalLogger $logger Global logger instance.
     */
    public function __construct(GlobalLogger $logger) {
        $this->logger = $logger;
    }

    /**
     * Check if the login attempts from the client's IP are within allowed limits.
     *
     * @param array $requestData Not used directly here.
     * @param array|null $user Not used in this check.
     * @return bool True if the rate limit is not exceeded; false otherwise.
     */
    public function check(array $requestData, ?array $user = null): bool {
        $clientIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

        // Convert IPv6 loopback to IPv4 loopback if desired.
    if ($clientIp === '::1') {
        $clientIp = '127.0.0.1';
    }
        // For demonstration, we simulate a rate limit check.
        // In production, implement actual rate limiting (e.g., using Redis or a database).
        $attempts = $this->getAttemptCountForIp($clientIp);
        $this->logger->debug("Rate limit check", ['clientIp' => $clientIp, 'attempts' => $attempts]);
        if ($attempts > $this->maxAttempts) {
            $this->logger->warn("Rate limit exceeded", ['clientIp' => $clientIp, 'attempts' => $attempts]);
            return false;
        }
        return true;
    }

    /**
     * Retrieve the number of login attempts for the given IP.
     *
     * This is a stub. In production, use persistent storage to track attempts.
     *
     * @param string $ip The client's IP address.
     * @return int The current count of login attempts.
     */
    protected function getAttemptCountForIp(string $ip): int {
        // Simulated value; replace with actual tracking.
        return 1;
    }

    /**
     * Get the failure message when the rate limit is exceeded.
     *
     * @return string
     */
    public function getFailureMessage(): string {
        return "Too many login attempts. Please try again later.";
    }
}
