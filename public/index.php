<?php
// public/index.php

use Core\GlobalLogger;
use Core\Router;
use App\Controllers\AuthController;
use App\Controllers\BankInfoController;

// STEP A: Include Composer's autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// STEP B: Load environment variables from the .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// STEP C: Error Reporting (development)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// STEP D: Start session
session_start();

// STEP E: Load config AFTER .env is loaded
$config = require __DIR__ . '/../config/config.php';

// STEP F: (Optional) Remove or comment out debug environment variable dump for production.
// echo "<h2>Environment Variables</h2>";
// echo "<pre>";
// var_dump($_ENV);
// echo "</pre>";

// STEP G: Initialize the Logger
$loggerConfig = [
    'environment' => $config['app_env'],
    'logFile'     => $config['log_file']
];
$logger = GlobalLogger::getInstance($loggerConfig);
$logger->info("Application bootstrapped");

// STEP H: Set up the Router
$router = new Router();

$isLoggedIn = isset($_SESSION['user_id']);
if ($isLoggedIn) {
    $router->add('GET', '/^\/dashboard$/', function() use ($logger) {
        $logger->info("Displaying dashboard for logged in user");
        require_once __DIR__ . '/../app/Views/DashboardView.php';
    });
    $router->add('GET', '/^\/$/', function() use ($logger) {
        $logger->info("User is logged in; redirecting root URL to dashboard");
        header("Location: /dashboard");
        exit();
    });
    // Route to display the Direct Deposit page
    $router->add('GET', '/^\/direct-deposit$/', function() use ($logger) {
        $logger->info("Displaying Direct Deposit page");
        require_once __DIR__ . '/../app/Views/DirectDepositView.php';
    });
} else {
    $router->add('GET', '/^\/$/', function() use ($logger) {
        $logger->info("User not logged in; redirecting root URL to login");
        header("Location: /login");
        exit();
    });
}

// Authentication routes
$authController = new AuthController($logger);
$router->add('GET', '/^\/login$/', [$authController, 'showLogin']);
$router->add('POST', '/^\/login$/', [$authController, 'handleLogin']);
$router->add('GET', '/^\/logout$/', [$authController, 'logout']);

// Instantiate BankInfoController and add the API endpoint route.
$bankInfoController = new BankInfoController($logger);
$router->add('GET', '/^\/api\/lookup-bank-info$/', [$bankInfoController, 'lookup']);

// STEP I: Dispatch the Request
$method = $_SERVER['REQUEST_METHOD'];
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$routeFound = $router->dispatch($method, $uri);

if (!$routeFound) {
    $logger->warn("No matching route found", ['method' => $method, 'uri' => $uri]);
    header("HTTP/1.0 404 Not Found");
    echo "404 Not Found";
    exit();
}
