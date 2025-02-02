<?php
// public/index.php

use Core\GlobalLogger;
use Core\Router;
use App\Controllers\AuthController;
use App\Controllers\BankInfoController;
use App\Controllers\EmployeeController;
// Add global CORS headers at the top of public/index.php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// STEP 1: Include Composer's autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// STEP 2: Load environment variables from the .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// STEP 3: Enable error reporting (development mode)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// STEP 4: Start the session
session_start();

// --- TEMPORARY: Set dummy session variables for testing (simulate logged in user) ---
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;       // Dummy user ID
    $_SESSION['user_type'] = 'staff'; // or 'customer'
}

// STEP 5: Load configuration (config returns an array)
$config = require __DIR__ . '/../config/config.php';

// STEP 6: Initialize the Logger using the config settings
$loggerConfig = [
    'environment' => $config['app_env'],
    'logFile'     => $config['log_file']
];
$logger = GlobalLogger::getInstance($loggerConfig);
$logger->info("Application bootstrapped");

// STEP 7: Instantiate the Router
$router = new Router();

// STEP 8: Determine if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

// STEP 9: Register routes based on login status
if ($isLoggedIn) {
    // Dashboard route
    $router->add('GET', '/^\/dashboard$/', function() use ($logger) {
        $logger->info("Displaying dashboard for logged in user");
        require_once __DIR__ . '/../app/Views/DashboardView.php';
    });
    // Root route: redirect logged-in users to the dashboard
    $router->add('GET', '/^\/$/', function() use ($logger) {
        $logger->info("User is logged in; redirecting root URL to dashboard");
        header("Location: /dashboard");
        exit();
    });
    // Direct Deposit page route
    $router->add('GET', '/^\/direct-deposit$/', function() use ($logger) {
        $logger->info("Displaying Direct Deposit page");
        require_once __DIR__ . '/../app/Views/DirectDepositView.php';
    });
} else {
    // If not logged in, redirect root URL to login
    $router->add('GET', '/^\/$/', function() use ($logger) {
        $logger->info("User not logged in; redirecting root URL to login");
        header("Location: /login");
        exit();
    });
}

// STEP 10: Authentication routes (login, logout)
$authController = new AuthController($logger);
$router->add('GET', '/^\/login$/', [$authController, 'showLogin']);
$router->add('POST', '/^\/login$/', [$authController, 'handleLogin']);
$router->add('GET', '/^\/logout$/', [$authController, 'logout']);

// STEP 11: API route for bank info lookup
$bankInfoController = new BankInfoController($logger);
$router->add('GET', '/^\/api\/lookup-bank-info$/', [$bankInfoController, 'lookup']);

// Instantiate JobController and add the API endpoint route.
$jobController = new \App\Controllers\JobController($logger);
$router->add('GET', '/^\/api\/jobs$/', [$jobController, 'list']);

// STEP X: Instantiate EmployeeController
$employeeController = new EmployeeController($logger);

// STEP Y: Add a route for employees index
$router->add('GET', '/^\/employees$/', [$employeeController, 'index']);
$router->add('GET', '/^.*employees\/(\d+)$/', function($id) use ($employeeController, $logger) {
    $logger->debug("Route match for /employees/(\d+). Captured param:", ['param' => $id]);
    $employeeController->show((int)$id);
});

$router->add('POST', '/^\/employees\/save-permissions$/', [$employeeController, 'savePermissions']);

// Instantiate VendorController (assuming $logger is already defined)
$vendorController = new \App\Controllers\VendorController($logger);
$router->add('GET', '/^\/vendors$/', [$vendorController, 'index']);
$router->add('GET', '/^\/vendors\/(\d+)$/', function($id) use ($vendorController, $logger) {
    $logger->debug("Route match for vendors with ID:", ['id' => $id]);
    $vendorController->show((int)$id);
});


// STEP 12: Dispatch the incoming request
$method = $_SERVER['REQUEST_METHOD'];
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$routeFound = $router->dispatch($method, $uri);
if (!$routeFound) {
    $logger->warn("No matching route found", ['method' => $method, 'uri' => $uri]);
    header("HTTP/1.0 404 Not Found");
    echo "404 Not Found";
    exit();
}
