<?php
// config/config.php
return [
    'app_env'  => getenv('APP_ENV') ?: 'production',
    'log_file' => __DIR__ . '/../logs/app.log',
];
