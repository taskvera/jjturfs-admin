<?php
/**
 * Advanced Application Bootstrapper
 *
 * This is the main entry point for the application. It initializes
 * error reporting, session management, environment configuration,
 * logging, and routing. Depending on the user's login status,
 * requests are routed to either the dashboard or the login page.
 *
 * Directory Structure Assumption:
 * 
 * taskvera-jjturfs-admin.git/
 *  ├── app/
 *  │   ├── Controllers/
 *  │   │   └── AuthController.php
 *  │   ├── Models/
 *  │   │   └── UserModel.php
 *  │   └── Views/
 *  │       ├── DashboardView.php
 *  │       └── LoginView.php
 *  ├── core/
 *  │   ├── GlobalLogger.php
 *  │   └── Router.php
 *  ├── logs/
 *  │   └── app.log
 *  └── public/
 *      └── index.php
 */

// ==========================================================================
// 1. Error Reporting (For Development Only)
// ==========================================================================
error_reporting(E_ALL);
ini_set('display_errors', 1);
// NOTE: In production, disable error display and log errors instead.

// ==========================================================================
// 2. Start Session and Load Environment Settings
// ==========================================================================
session_start(); // Start PHP session management

// Load environment configuration.
// APP_ENV could be defined via an .env file, Apache environment, or default to 'production'.
$appEnv = getenv('APP_ENV') ?: 'production';

// ==========================================================================
// 3. Logger Configuration and Initialization
// ==========================================================================
$loggerConfig = [
    'environment' => $appEnv,
    // Ensure that a "logs" folder exists at the project root.
    'logFile'     => __DIR__ . '/../logs/app.log'
];

// Include core classes for logging and routing
require_once __DIR__ . '/../core/GlobalLogger.php';
require_once __DIR__ . '/../core/Router.php';

// Include authentication controller
require_once __DIR__ . '/../app/Controllers/AuthController.php';

// Initialize the GlobalLogger singleton instance.
$logger = GlobalLogger::getInstance($loggerConfig);
$logger->info("Application bootstrapped");

// ==========================================================================
// 4. Instantiate the Router
// ==========================================================================
$router = new Router();

// ==========================================================================
// 5. Check Login Status and Define Conditional Routes
// ==========================================================================
// Determine if the user is logged in by checking for a session variable.
// (In a real-world application, you might check for a valid token or user object.)
$isLoggedIn = isset($_SESSION['user_id']);

// If the user is logged in, define routes for accessing the dashboard.
if ($isLoggedIn) {
    // Route for the dashboard view (e.g., GET /dashboard)
    $router->add('GET', '/^\/dashboard$/', function() use ($logger) {
        $logger->info("Displaying dashboard for logged in user");
        // Include the dashboard view file. Ensure this file exists.
        require_once __DIR__ . '/../app/Views/DashboardView.php';
    });
    
    // Route the base URL ("/") to redirect to the dashboard.
    $router->add('GET', '/^\/$/', function() use ($logger) {
        $logger->info("User is logged in; redirecting root URL to dashboard");
        header("Location: /dashboard");
        exit();
    });
} else {
    // If the user is not logged in, route the base URL ("/") to redirect to the login page.
    $router->add('GET', '/^\/$/', function() use ($logger) {
        $logger->info("User not logged in; redirecting root URL to login");
        header("Location: /login");
        exit();
    });
}

// ==========================================================================
// 6. Define Authentication Routes Using AuthController
// ==========================================================================
$authController = new AuthController($logger);

// Route for displaying the login form (GET /login)
$router->add('GET', '/^\/login$/', [$authController, 'showLogin']);

// Route for processing login submissions (POST /login)
$router->add('POST', '/^\/login$/', [$authController, 'handleLogin']);

// Route for logging out the user (GET /logout)
$router->add('GET', '/^\/logout$/', [$authController, 'logout']);

// ==========================================================================
// 7. Dispatch the Incoming Request via the Router
// ==========================================================================
// Capture the HTTP method and the request URI.
// Dispatch the request
$method = $_SERVER['REQUEST_METHOD'];
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$routeFound = $router->dispatch($method, $uri);

// If no route matches the request, log the event and return a 404 error.
if (!$routeFound) {
    $logger->warn("No matching route found", ['method' => $method, 'uri' => $uri]);
    header("HTTP/1.0 404 Not Found");
    echo "404 Not Found";
    exit();
}

?>
