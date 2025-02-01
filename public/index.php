<?php
/*
/public/index.php
Used to bootstrap the app, start logging, start analytics tracking, check login status,
and push to one of two routers - auth router and app router based on login status.
*/

// Include the GlobalLogger class (adjust the path as needed)
require_once __DIR__ . '/../core/GlobalLogger.php';

// Start a session if needed
session_start();

// Optionally load environment variables.
// You might use a library like vlucas/phpdotenv to load a .env file.
// For this example, we'll assume that environment variables are already set.
$appEnv = getenv('APP_ENV') ?: 'production';

// Configure the logger.
// NOTE: Create a "logs" folder in your project root (i.e., alongside README.md and app/) so the log file can be written.
$loggerConfig = [
    'environment' => $appEnv,
    'logFile'     => __DIR__ . '/../logs/app.log'
];
$logger = GlobalLogger::getInstance($loggerConfig);

// Log that the application has started.
$logger->info("Application bootstrapped");

// Check login status (example check)
$isLoggedIn = isset($_SESSION['user_id']);

// Redirect to appropriate router
if ($isLoggedIn) {
    require_once __DIR__ . '/../routes/app_router.php';
} else {
    require_once __DIR__ . '/../routes/auth_router.php';
}
?>
