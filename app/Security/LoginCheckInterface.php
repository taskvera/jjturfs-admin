<?php
namespace App\Security;

/**
 * Interface LoginCheckInterface
 *
 * Defines a contract for a login security check.
 */
interface LoginCheckInterface {
    /**
     * Perform the security check.
     *
     * @param array $requestData The data from the login request (e.g., $_POST data).
     * @param array|null $user The user data if already fetched (null for pre-authentication checks).
     * @return bool Returns true if the check passes; false otherwise.
     */
    public function check(array $requestData, ?array $user = null): bool;

    /**
     * Get the failure message when the check fails.
     *
     * @return string The error message to display or log.
     */
    public function getFailureMessage(): string;
}
