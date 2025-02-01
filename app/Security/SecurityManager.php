<?php
namespace App\Security;

use Core\GlobalLogger;

/**
 * Class SecurityManager
 *
 * Manages a collection of login security checks and executes them.
 * Each check implements the LoginCheckInterface.
 */
class SecurityManager {
    /**
     * @var GlobalLogger Global logger instance for logging each check.
     */
    protected $logger;

    /**
     * @var LoginCheckInterface[] List of registered security checks.
     */
    protected $checks = [];

    /**
     * SecurityManager constructor.
     *
     * @param GlobalLogger $logger Global logger instance.
     */
    public function __construct(GlobalLogger $logger) {
        $this->logger = $logger;
        $this->logger->debug("SecurityManager initialized");
    }

    /**
     * Add a security check.
     *
     * @param LoginCheckInterface $check The security check to add.
     * @return void
     */
    public function addCheck(LoginCheckInterface $check): void {
        $this->checks[] = $check;
        $this->logger->debug("Added security check", ['check' => get_class($check)]);
    }

    /**
     * Run all registered checks.
     *
     * @param array $requestData The login request data.
     * @param array|null $user The user data if available.
     * @return string|null Returns null if all checks pass, or a failure message if one check fails.
     */
    public function runChecks(array $requestData, ?array $user = null): ?string {
        foreach ($this->checks as $check) {
            $this->logger->debug("Running security check", ['check' => get_class($check)]);
            if (!$check->check($requestData, $user)) {
                $failureMessage = $check->getFailureMessage();
                $this->logger->warn("Security check failed", [
                    'check'   => get_class($check),
                    'message' => $failureMessage
                ]);
                return $failureMessage;
            }
        }
        return null;
    }
}
