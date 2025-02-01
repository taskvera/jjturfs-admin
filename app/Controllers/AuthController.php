<?php
namespace App\Controllers;

use Core\GlobalLogger;
use App\Models\UserModel;
use App\Security\SecurityManager;
use App\Security\IpBlacklistCheck;
use App\Security\RateLimitCheck;

/**
 * Class AuthController
 *
 * Handles authentication for both web and API/mobile requests. This controller:
 * - Displays the login form.
 * - Processes login submissions by running a series of pluggable security checks
 *   before and after user credential validation.
 * - Logs every significant step using the GlobalLogger.
 * - Sets session variables and redirects users based on their type (staff vs. customer).
 *
 * @package App\Controllers
 */
class AuthController
{
    /**
     * Global logger instance.
     *
     * @var GlobalLogger
     */
    protected $logger;

    /**
     * User model instance for database interactions.
     *
     * @var UserModel
     */
    protected $userModel;

    /**
     * AuthController constructor.
     *
     * Initializes the logger and user model.
     *
     * @param GlobalLogger $logger A logger instance.
     */
    public function __construct(GlobalLogger $logger)
    {
        $this->logger = $logger;
        $this->logger->debug("AuthController constructed");
        // Instantiate the user model (in production, consider using dependency injection)
        $this->userModel = new UserModel();
    }

    /**
     * Display the login form for web users.
     *
     * Logs the event and includes the login view.
     *
     * @return void
     */
    public function showLogin(): void
    {
        $this->logger->info("Displaying login form to web user");
        require_once __DIR__ . '/../Views/LoginView.php';
    }

    /**
     * Handle the login form submission.
     *
     * This method performs the following steps:
     * 1. Logs the receipt of login data.
     * 2. Instantiates the SecurityManager and registers pre-validation checks.
     * 3. Runs pre-validation checks (e.g., IP blacklist, rate limiting).
     * 4. Attempts to retrieve the user by email.
     * 5. Validates the provided password.
     * 6. Runs post-validation checks (e.g., account status, risk assessments).
     * 7. On success, sets session variables and redirects the user based on their type.
     * 8. On failure, logs the failure and displays the login form with an error message.
     *
     * @return void
     */
    public function handleLogin(): void
    {
        $this->logger->info("Processing login request");

        // Retrieve credentials from POST data.
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Log the receipt of login credentials (exclude sensitive data).
        $this->logger->debug("Received login credentials", ['email' => $email]);

        // 1. Pre-Validation Checks: Instantiate SecurityManager and register pre-validation checks.
        $securityManager = new SecurityManager($this->logger);
        $securityManager->addCheck(new IpBlacklistCheck($this->logger));
        $securityManager->addCheck(new RateLimitCheck($this->logger));
        // Additional pre-validation checks (e.g., proxy detection, header validation) can be added here.

        // Run pre-validation checks (user data not yet available).
        $error = $securityManager->runChecks($_POST, null);
        if ($error !== null) {
            $this->logger->warn("Pre-validation security check failed", ['error' => $error]);
            $this->handleLoginFailure($error);
            return;
        }

        // 2. Credential Verification: Attempt to fetch the user by email.
        $user = $this->userModel->findUserByEmail($email);
        if (!$user) {
            $this->logger->warn("User not found for email", ['email' => $email]);
            $this->handleLoginFailure("Invalid email or password.");
            return;
        }

        // Verify the provided password.
        if (!password_verify($password, $user['password_hash'])) {
            $this->logger->warn("Password verification failed", ['email' => $email]);
            $this->handleLoginFailure("Invalid email or password.");
            return;
        }

        // Log successful credential validation.
        $this->logger->info("User authenticated successfully", [
            'user_id'   => $user['id'],
            'user_type' => $user['user_type']
        ]);

        // 3. Post-Validation Checks: Register additional checks that require user data.
        // For example, you could add checks for account status, MFA enrollment, or risk assessment.
        // $securityManager->addCheck(new UserStatusCheck($this->logger));
        // $securityManager->addCheck(new AccountLockoutCheck($this->logger));
        // $securityManager->addCheck(new MfaEnrollmentCheck($this->logger));
        // For now, we reuse the same SecurityManager instance.
        $error = $securityManager->runChecks($_POST, $user);
        if ($error !== null) {
            $this->logger->warn("Post-validation security check failed", ['error' => $error]);
            $this->handleLoginFailure($error);
            return;
        }

        // 4. All checks passed. For web login, set session variables.
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = $user['user_type'];
        $this->logger->info("Session established for user", [
            'user_id'   => $user['id'],
            'user_type' => $user['user_type']
        ]);

        // 5. Redirect the user based on their type.
        if ($user['user_type'] === 'staff') {
            $this->logger->info("Redirecting staff user to dashboard", ['user_id' => $user['id']]);
            header("Location: /dashboard");
        } elseif ($user['user_type'] === 'customer') {
            $this->logger->info("Redirecting customer user to portal", ['user_id' => $user['id']]);
            header("Location: /customer-portal");
        } else {
            $this->logger->error("Unknown user type encountered", [
                'user_id'   => $user['id'],
                'user_type' => $user['user_type']
            ]);
            $this->handleLoginFailure("Unknown user type encountered.");
            return;
        }
        exit();
    }

    /**
     * Handle a failed login attempt.
     *
     * Logs the failure and displays the login view with an error message.
     *
     * @param string $error The error message to display.
     * @return void
     */
    protected function handleLoginFailure(string $error): void
    {
        $this->logger->warn("Login failure encountered", ['error' => $error]);
        // For web login, set the error message and display the login view.
        $errorMessage = $error;
        require_once __DIR__ . '/../Views/LoginView.php';
        exit();
    }

    /**
     * Log out the user.
     *
     * Clears the session and redirects to the login page.
     *
     * @return void
     */
    public function logout(): void
    {
        $this->logger->info("Processing logout request");
        session_unset();
        session_destroy();
        header("Location: /login");
        exit();
    }
}
