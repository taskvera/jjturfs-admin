<?php
/**
 * /core/GlobalLogger.php
 *
 * A simple, fully custom global logger that logs messages at various levels
 * (trace, debug, info, warn, error, critical). It can include extra debug info 
 * (file, line, function) based on the environment or level.
 *
 * Configuration options:
 *   - environment: 'development' or 'production' (affects log verbosity and output)
 *   - logFile: path to the log file
 *   - minLevel: optional integer to override the default minimum logging level
 *
 * Usage:
 *   $logger = GlobalLogger::getInstance([
 *       'environment' => getenv('APP_ENV') ?: 'production',
 *       'logFile' => __DIR__ . '/../logs/app.log'
 *   ]);
 *   $logger->info("Application started");
 */

class GlobalLogger {
    // Log level constants
    const LEVEL_TRACE    = 100;
    const LEVEL_DEBUG    = 200;
    const LEVEL_INFO     = 300;
    const LEVEL_WARN     = 400;
    const LEVEL_ERROR    = 500;
    const LEVEL_CRITICAL = 600;

    // The minimum log level to output
    protected $minLevel;

    // The log file path
    protected $logFile;

    // The current environment: 'development' or 'production'
    protected $environment;

    // Singleton instance
    protected static $instance;

    /**
     * Private constructor.
     *
     * @param array $config Configuration options.
     */
    private function __construct($config = []) {
        // Set environment (default to production)
        $this->environment = $config['environment'] ?? 'production';

        // Determine the minimum level: in development, log everything; in production, log info and above.
        if (isset($config['minLevel'])) {
            $this->minLevel = $config['minLevel'];
        } else {
            $this->minLevel = $this->environment === 'development' ? self::LEVEL_TRACE : self::LEVEL_INFO;
        }

        // Set log file location.
        // NOTE: Make sure the directory exists (for example, create a "logs" folder in your project root).
        $this->logFile = $config['logFile'] ?? __DIR__ . '/logs/app.log';
    }

    /**
     * Get the singleton instance.
     *
     * @param array $config Optional configuration on first call.
     * @return GlobalLogger
     */
    public static function getInstance($config = []) {
        if (!self::$instance) {
            self::$instance = new GlobalLogger($config);
        }
        return self::$instance;
    }

    /**
     * Convert a log level constant to its name.
     *
     * @param int $level
     * @return string
     */
    protected function getLevelName($level) {
        switch ($level) {
            case self::LEVEL_TRACE:    return 'TRACE';
            case self::LEVEL_DEBUG:    return 'DEBUG';
            case self::LEVEL_INFO:     return 'INFO';
            case self::LEVEL_WARN:     return 'WARN';
            case self::LEVEL_ERROR:    return 'ERROR';
            case self::LEVEL_CRITICAL: return 'CRITICAL';
            default:                   return 'UNKNOWN';
        }
    }

    /**
     * Core log method.
     *
     * @param int    $level   The log level.
     * @param string $message The log message.
     * @param array  $context Optional additional context.
     */
    protected function log($level, $message, $context = []) {
        // Skip messages below the minimum level.
        if ($level < $this->minLevel) {
            return;
        }

        // Timestamp the log entry.
        $timestamp = date('Y-m-d H:i:s');

        // Get the level name.
        $levelName = $this->getLevelName($level);

        // Optionally capture debug info (file, line, function) for development or higher-severity messages.
        $debugInfo = '';
        if ($this->environment === 'development' || $level >= self::LEVEL_ERROR) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
            $caller = $backtrace[2] ?? ($backtrace[1] ?? []);
            $file     = $caller['file'] ?? 'unknown file';
            $line     = $caller['line'] ?? 'unknown line';
            $function = $caller['function'] ?? 'global';
            $debugInfo = " in {$file} on line {$line} (function: {$function})";
        }

        // Append context if available.
        if (!empty($context)) {
            $message .= ' | ' . json_encode($context);
        }

        // Build the log entry.
        $logEntry = "[{$timestamp}] {$levelName}: {$message}{$debugInfo}" . PHP_EOL;

        // Write the log entry to the file.
        file_put_contents($this->logFile, $logEntry, FILE_APPEND);

        // In development, optionally echo the log.
        if ($this->environment === 'development') {
            echo $logEntry;
        }
    }

    // Public methods for each log level.

    public function trace($message, $context = []) {
        $this->log(self::LEVEL_TRACE, $message, $context);
    }

    public function debug($message, $context = []) {
        $this->log(self::LEVEL_DEBUG, $message, $context);
    }

    public function info($message, $context = []) {
        $this->log(self::LEVEL_INFO, $message, $context);
    }

    public function warn($message, $context = []) {
        $this->log(self::LEVEL_WARN, $message, $context);
    }

    public function error($message, $context = []) {
        $this->log(self::LEVEL_ERROR, $message, $context);
    }

    public function critical($message, $context = []) {
        $this->log(self::LEVEL_CRITICAL, $message, $context);
    }
}
