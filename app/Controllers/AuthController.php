<?php
// app/Controllers/AuthController.php

class AuthController {
    protected $logger;

    /**
     * Constructor.
     *
     * @param GlobalLogger $logger A logger instance for logging events.
     */
    public function __construct($logger) {
        $this->logger = $logger;
    }

    /**
     * Display the login form.
     *
     * This method is mapped to GET /login.
     */
    public function showLogin() {
        $this->logger->info("Displaying login page");

        // Include the login view.
        // The view should contain your HTML form.
        require_once __DIR__ . '/../Views/LoginView.php';
    }

    /**
     * Handle the login form submission.
     *
     * This method is mapped to POST /login.
     */
    public function handleLogin() {
        $this->logger->info("Processing login request");

        // Retrieve username and password from POST data.
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // For demonstration purposes, we use hard-coded credentials.
        // In a real application, you would query the database via a model.
        if ($username === 'admin' && $password === 'password') {
            // Set a session variable to indicate the user is logged in.
            $_SESSION['user_id'] = 1;
            $this->logger->info("User logged in successfully", ['username' => $username]);

            // Redirect to a dashboard page or another protected route.
            header("Location: /dashboard");
            exit();
        } else {
            // Log a warning for the failed login attempt.
            $this->logger->warn("Login failed", ['username' => $username]);

            // Optionally, pass an error message to the view.
            $error = "Invalid username or password.";
            require_once __DIR__ . '/../app/Views/LoginView.php';
        }
    }

    /**
     * Log the user out.
     *
     * This method is mapped to GET /logout.
     */
    public function logout() {
        $this->logger->info("Processing logout request");

        // Unset all session variables and destroy the session.
        session_unset();
        session_destroy();

        // Redirect the user to the login page.
        header("Location: /login");
        exit();
    }
}
