<?php
/*
/public/index.php
Used to bootstrap app, start logging, start analytics tracking, check login status, and push to one of two routers - auth router and app router based on login status.
*/

// Autoload dependencies (if using Composer)
// require_once __DIR__ . '/../vendor/autoload.php';

// Start a session (if needed)
session_start();

// Load environment variables
// require_once __DIR__ . '/../config/config.php';

// Include logging & analytics tracking (example)
// require_once __DIR__ . '/../bootstrap/logging.php';
// require_once __DIR__ . '/../bootstrap/analytics.php';

// Check login status
$isLoggedIn = isset($_SESSION['user_id']); // Example login check

// Redirect to appropriate router
if ($isLoggedIn) {
    require_once __DIR__ . '/../routes/app_router.php';
} else {
    require_once __DIR__ . '/../routes/auth_router.php';
}
?>
