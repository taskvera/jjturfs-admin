<?php
// core/GlobalLogger.php

namespace Core;

/**
 * Class GlobalLogger
 *
 * A singleton logger supporting multiple log levels (trace, debug, info, warn, error, critical),
 * advanced file handling, custom date formats, and optional log rotation.
 *
 * Usage Example:
 *   $logger = GlobalLogger::getInstance([
 *       'environment' => 'development',
 *       'logFile'     => __DIR__ . '/../logs/app.log',
 *       'dateFormat'  => 'Y-m-d H:i:s',
 *       'maxFileSize' => 5 * 1024 * 1024, // 5 MB (optional for rotation)
 *   ]);
 *   $logger->debug("This is a debug message", ['foo' => 'bar']);
 */
class GlobalLogger
{
    // Log level constants
    public const LEVEL_TRACE    = 100;
    public const LEVEL_DEBUG    = 200;
    public const LEVEL_INFO     = 300;
    public const LEVEL_WARN     = 400;
    public const LEVEL_ERROR    = 500;
    public const LEVEL_CRITICAL = 600;

    /**
     * @var int The current minimum log level. Messages below this level won't be logged.
     */
    protected $minLevel;

    /**
     * @var string The path to the log file.
     */
    protected $logFile;

    /**
     * @var string The application environment (e.g., 'development' or 'production').
     */
    protected $environment;

    /**
     * @var string Format string for timestamps. Default is 'Y-m-d H:i:s'.
     */
    protected $dateFormat;

    /**
     * @var int|null Optional maximum file size in bytes before rotating. Null if disabled.
     */
    protected $maxFileSize;

    /**
     * @var bool Whether to rotate the file by renaming it rather than overwriting. If false, it’s overwritten.
     */
    protected $rotateFile;

    /**
     * @var GlobalLogger|null Singleton instance.
     */
    protected static $instance;

    /**
     * Private constructor to enforce singleton usage.
     *
     * @param array $config Configuration options.
     *                      - environment: 'development' or 'production' (default: 'production')
     *                      - minLevel: int (override auto logic)
     *                      - logFile: string (path to log file)
     *                      - dateFormat: string (PHP date format)
     *                      - maxFileSize: int (bytes) for optional rotation
     *                      - rotateFile: bool (if true, rename old file on rotation)
     */
    private function __construct(array $config = [])
    {
        // Determine the environment
        $this->environment = $config['environment'] ?? 'production';

        // Determine the minLevel
        if (isset($config['minLevel'])) {
            $this->minLevel = $config['minLevel'];
        } else {
            // In development, default to logging everything; production logs info and higher
            $this->minLevel = ($this->environment === 'development')
                ? self::LEVEL_TRACE
                : self::LEVEL_INFO;
        }

        // Log file path
        $this->logFile = $config['logFile'] ?? __DIR__ . '/../logs/app.log';

        // Date format for timestamps
        $this->dateFormat = $config['dateFormat'] ?? 'Y-m-d H:i:s';

        // Optional file rotation
        $this->maxFileSize = $config['maxFileSize'] ?? null;
        $this->rotateFile  = $config['rotateFile'] ?? true;
    }

    /**
     * Get the singleton instance of the logger.
     *
     * @param array $config Optional configuration on first call.
     * @return GlobalLogger
     */
    public static function getInstance(array $config = []): GlobalLogger
    {
        if (!self::$instance) {
            self::$instance = new GlobalLogger($config);
        }
        return self::$instance;
    }

    /**
     * Convert a log level constant to its textual name.
     *
     * @param int $level The log level.
     * @return string
     */
    protected function getLevelName(int $level): string
    {
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
     * The core log method which all public logging methods call.
     *
     * @param int    $level   The log level.
     * @param string $message The log message (without context).
     * @param array  $context Additional context data.
     * @return void
     */
    protected function log(int $level, string $message, array $context = []): void
    {
        // Skip logging if below the minimum configured level
        if ($level < $this->minLevel) {
            return;
        }

        // Generate timestamp using configured date format
        $timestamp = date($this->dateFormat);

        // Convert numeric level to textual name
        $levelName = $this->getLevelName($level);

        // If environment is 'development' or level is ERROR/CRITICAL, capture debug info
        $debugInfo = '';
        if ($this->environment === 'development' || $level >= self::LEVEL_ERROR) {
            // Capture backtrace for better logging. We skip the first frames to get to the caller.
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
            $caller = $backtrace[2] ?? ($backtrace[1] ?? []);
            $file = $caller['file'] ?? 'unknown file';
            $line = $caller['line'] ?? 'unknown line';
            $function = $caller['function'] ?? 'global';
            $debugInfo = " in {$file} on line {$line} (function: {$function})";
        }

        // Merge context into message if present
        if (!empty($context)) {
            $message .= ' | ' . json_encode($context);
        }

        // Build final log line
        $logEntry = "[{$timestamp}] {$levelName}: {$message}{$debugInfo}" . PHP_EOL;

        // Optionally rotate log if it exceeds maxFileSize
        if ($this->maxFileSize !== null) {
            $this->maybeRotateLogFile();
        }

        // Write the log entry to file
        $this->writeLog($logEntry);
    }

    /**
     * Check if the log file exceeds maxFileSize. If so, rotate or reset it.
     *
     * @return void
     */
    protected function maybeRotateLogFile(): void
    {
        // If the file doesn't exist or maxFileSize is null, do nothing
        if (!file_exists($this->logFile) || $this->maxFileSize === null) {
            return;
        }

        // If file size is within limit, do nothing
        $fileSize = filesize($this->logFile) ?: 0;
        if ($fileSize < $this->maxFileSize) {
            return;
        }

        // If we are rotating, rename the file with a timestamp
        if ($this->rotateFile) {
            $timestamp = date('Ymd_His');
            $rotatedFile = $this->logFile . '.' . $timestamp;
            @rename($this->logFile, $rotatedFile);
        } else {
            // Otherwise, just empty or remove the log file
            @unlink($this->logFile);
        }
    }

    /**
     * Writes a log entry to file, with error handling.
     *
     * @param string $logEntry The formatted log line.
     * @return void
     */
    protected function writeLog(string $logEntry): void
    {
        try {
            // LOCK_EX to avoid concurrent write issues
            file_put_contents($this->logFile, $logEntry, FILE_APPEND | LOCK_EX);
        } catch (\Exception $e) {
            // Fallback: if logging fails, there's not much we can do except maybe echo or ignore.
            // We do not rethrow to avoid recursion if logging the exception itself fails.
            error_log("GlobalLogger: Failed to write log. " . $e->getMessage());
        }
    }

    // ----------------------------------------------------------------------
    // Public convenience methods for each log level
    // ----------------------------------------------------------------------
    /**
     * Log a trace-level message.
     *
     * @param string $message
     * @param array  $context
     */
    public function trace(string $message, array $context = []): void
    {
        $this->log(self::LEVEL_TRACE, $message, $context);
    }

    /**
     * Log a debug-level message.
     *
     * @param string $message
     * @param array  $context
     */
    public function debug(string $message, array $context = []): void
    {
        $this->log(self::LEVEL_DEBUG, $message, $context);
    }

    /**
     * Log an info-level message.
     *
     * @param string $message
     * @param array  $context
     */
    public function info(string $message, array $context = []): void
    {
        $this->log(self::LEVEL_INFO, $message, $context);
    }

    /**
     * Log a warn-level message.
     *
     * @param string $message
     * @param array  $context
     */
    public function warn(string $message, array $context = []): void
    {
        $this->log(self::LEVEL_WARN, $message, $context);
    }

    /**
     * Log an error-level message.
     *
     * @param string $message
     * @param array  $context
     */
    public function error(string $message, array $context = []): void
    {
        $this->log(self::LEVEL_ERROR, $message, $context);
    }

    /**
     * Log a critical-level message.
     *
     * @param string $message
     * @param array  $context
     */
    public function critical(string $message, array $context = []): void
    {
        $this->log(self::LEVEL_CRITICAL, $message, $context);
    }
}
