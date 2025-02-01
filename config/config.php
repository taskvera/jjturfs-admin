<?php
// config/config.php
return [
    'app_env'  => getenv('APP_ENV') ?: 'development',
    'log_file' => __DIR__ . '/../logs/app.log',
];
