<?php
// core/Autoloader.php
spl_autoload_register(function ($class) {
    // Base directory for the namespace prefix
    $baseDir = __DIR__ . '/../';
    
    // Replace namespace separators with directory separators and append ".php"
    $file = $baseDir . str_replace('\\', '/', $class) . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    }
});
