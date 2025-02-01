<?php
// app/Controllers/AuthController.php
namespace App\Controllers;

use Core\GlobalLogger;

class AuthController {
    protected $logger;

    /**
     * Constructor.
     *
     * @param GlobalLogger $logger A logger instance.
     */
    public function __construct(GlobalLogger $logger) {
        $this->logger = $logger;
    }

    /**
     * Display the login form.
     */
    public function showLogin() {
        $this->logger->info("Displaying login page");
        require_once __DIR__ . '/../Views/LoginView.php';
    }

    /**
     * Handle the login form submission.
     */
    public function handleLogin() {
        $this->logger->info("Processing login request");

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // For demonstration, we use hard-coded credentials.
        if ($username === 'admin' && $password === 'password') {
            $_SESSION['user_id'] = 1;
            $this->logger->info("User logged in successfully", ['username' => $username]);
            header("Location: /dashboard");
            exit();
        } else {
            $this->logger->warn("Login failed", ['username' => $username]);
            $error = "Invalid username or password.";
            require_once __DIR__ . '/../Views/LoginView.php';
        }
    }

    /**
     * Log the user out.
     */
    public function logout() {
        $this->logger->info("Processing logout request");
        session_unset();
        session_destroy();
        header("Location: /login");
        exit();
    }
}
