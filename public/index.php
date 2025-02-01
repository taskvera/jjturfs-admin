<?php
// public/index.php

require_once __DIR__ . '/../core/GlobalLogger.php';
require_once __DIR__ . '/../core/Router.php';

// Include your controllers as needed
require_once __DIR__ . '/../app/Controllers/AuthController.php';

// Start session and logger setup...
session_start();
$appEnv = getenv('APP_ENV') ?: 'production';
$loggerConfig = [
    'environment' => $appEnv,
    'logFile'     => __DIR__ . '/../logs/app.log'
];
$logger = GlobalLogger::getInstance($loggerConfig);
$logger->info("Application bootstrapped");

// Instantiate your router
$router = new Router();

// Define routes for authentication
$authController = new AuthController($logger);
$router->add('GET', '/^\/login$/', [$authController, 'showLogin']);
$router->add('POST', '/^\/login$/', [$authController, 'handleLogin']);
$router->add('GET', '/^\/logout$/', [$authController, 'logout']);

// Dispatch the request
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($method, $uri);
