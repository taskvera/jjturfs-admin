<?php
// config/config.php

return [
    // If APP_ENV is set in .env, use that; otherwise default to "development"
    'app_env' => $_ENV['APP_ENV'] ?? 'development',

    // Similarly, you might store DB credentials here if you want them in your config
    // 'db_host' => $_ENV['DB_HOST'] ?? 'localhost',
    // 'db_user' => $_ENV['DB_USER'] ?? 'root',
    // 'db_pass' => $_ENV['DB_PASS'] ?? '',

    // Log file is unaffected by environment variables
    'log_file' => __DIR__ . '/../logs/app.log',
];
