<?php
// public/index.php
/**
 * Advanced Front Controller
 *
 * Bootstraps the application: loads configuration, initializes
 * autoloading, starts the session, sets up logging, and dispatches routes.
 */

// 1. Error Reporting (for development)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. Start the session and load configuration
session_start();
$config = require __DIR__ . '/../config/config.php';

// 3. Include the autoloader
require_once __DIR__ . '/../core/Autoloader.php';

// 4. Import classes using namespaces
use Core\GlobalLogger;
use Core\Router;
use App\Controllers\AuthController;

// 5. Initialize the logger
$loggerConfig = [
    'environment' => $config['app_env'],
    'logFile'     => $config['log_file']
];
$logger = GlobalLogger::getInstance($loggerConfig);
$logger->info("Application bootstrapped");

// 6. Instantiate the router
$router = new Router();

// 7. Define routes based on authentication status
$isLoggedIn = isset($_SESSION['user_id']);
if ($isLoggedIn) {
    // User is logged in.
    // Define a route for the dashboard view.
    $router->add('GET', '/^\/dashboard$/', function() use ($logger) {
        $logger->info("Displaying dashboard for logged in user");
        require_once __DIR__ . '/../app/Views/DashboardView.php';
    });
    
    // Define the base URL ("/") to redirect to the dashboard.
    $router->add('GET', '/^\/$/', function() use ($logger) {
        $logger->info("User is logged in; redirecting root URL to dashboard");
        header("Location: /dashboard");
        exit();
    });
} else {
    // User is not logged in.
    // Define the base URL ("/") to redirect to the login page.
    $router->add('GET', '/^\/$/', function() use ($logger) {
        $logger->info("User not logged in; redirecting root URL to login");
        header("Location: /login");
        exit();
    });
}

// 8. Define authentication routes (available regardless of login status)
$authController = new AuthController($logger);
$router->add('GET', '/^\/login$/', [$authController, 'showLogin']);
$router->add('POST', '/^\/login$/', [$authController, 'handleLogin']);
$router->add('GET', '/^\/logout$/', [$authController, 'logout']);

// 9. Dispatch the incoming request
$method = $_SERVER['REQUEST_METHOD'];
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$routeFound = $router->dispatch($method, $uri);

if (!$routeFound) {
    $logger->warn("No matching route found", ['method' => $method, 'uri' => $uri]);
    header("HTTP/1.0 404 Not Found");
    echo "404 Not Found";
    exit();
}
