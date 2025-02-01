<?php
// core/GlobalLogger.php
namespace Core;

class GlobalLogger {
    // Log level constants
    const LEVEL_TRACE    = 100;
    const LEVEL_DEBUG    = 200;
    const LEVEL_INFO     = 300;
    const LEVEL_WARN     = 400;
    const LEVEL_ERROR    = 500;
    const LEVEL_CRITICAL = 600;

    protected $minLevel;
    protected $logFile;
    protected $environment;
    protected static $instance;

    /**
     * Private constructor.
     *
     * @param array $config Configuration options.
     */
    private function __construct(array $config = [])
    {
        $this->environment = $config['environment'] ?? 'production';
        $this->minLevel = $config['minLevel'] ??
            ($this->environment === 'development' ? self::LEVEL_TRACE : self::LEVEL_INFO);
        $this->logFile = $config['logFile'] ?? __DIR__ . '/../logs/app.log';
    }

    /**
     * Get the singleton instance.
     *
     * @param array $config Optional configuration on first call.
     * @return GlobalLogger
     */
    public static function getInstance(array $config = []): GlobalLogger {
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
    protected function getLevelName($level): string {
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
    protected function log($level, $message, array $context = [])
    {
        if ($level < $this->minLevel) {
            return;
        }

        $timestamp = date('Y-m-d H:i:s');
        $levelName = $this->getLevelName($level);
        $debugInfo = '';

        if ($this->environment === 'development' || $level >= self::LEVEL_ERROR) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
            $caller = $backtrace[2] ?? ($backtrace[1] ?? []);
            $file = $caller['file'] ?? 'unknown file';
            $line = $caller['line'] ?? 'unknown line';
            $function = $caller['function'] ?? 'global';
            $debugInfo = " in {$file} on line {$line} (function: {$function})";
        }

        if (!empty($context)) {
            $message .= ' | ' . json_encode($context);
        }

        $logEntry = "[{$timestamp}] {$levelName}: {$message}{$debugInfo}" . PHP_EOL;
        file_put_contents($this->logFile, $logEntry, FILE_APPEND | LOCK_EX);

        if ($this->environment === 'development') {
            echo $logEntry;
        }
    }

    // Public logging methods
    public function trace($message, array $context = []) { $this->log(self::LEVEL_TRACE, $message, $context); }
    public function debug($message, array $context = []) { $this->log(self::LEVEL_DEBUG, $message, $context); }
    public function info($message, array $context = [])  { $this->log(self::LEVEL_INFO, $message, $context); }
    public function warn($message, array $context = [])  { $this->log(self::LEVEL_WARN, $message, $context); }
    public function error($message, array $context = []) { $this->log(self::LEVEL_ERROR, $message, $context); }
    public function critical($message, array $context = []) { $this->log(self::LEVEL_CRITICAL, $message, $context); }
}
