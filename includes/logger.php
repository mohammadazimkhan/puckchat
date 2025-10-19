<?php
// Logging System for Error Handling and Debugging

class Logger {
    private static $logFile;
    
    public static function init() {
        self::$logFile = __DIR__ . '/../logs/app.log';
        
        // Create logs directory if it doesn't exist
        $logDir = dirname(self::$logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
    }
    
    private static function writeLog($level, $message) {
        if (!self::$logFile) {
            self::init();
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[{$timestamp}] [{$level}] {$message}" . PHP_EOL;
        
        // Write to file
        file_put_contents(self::$logFile, $logEntry, FILE_APPEND | LOCK_EX);
        
        // Also log to PHP error log in production
        if (!DEBUG_MODE) {
            error_log($logEntry);
        }
    }
    
    public static function info($message) {
        self::writeLog('INFO', $message);
    }
    
    public static function error($message) {
        self::writeLog('ERROR', $message);
    }
    
    public static function warning($message) {
        self::writeLog('WARNING', $message);
    }
    
    public static function debug($message) {
        if (DEBUG_MODE) {
            self::writeLog('DEBUG', $message);
        }
    }
    
    public static function auth($message) {
        self::writeLog('AUTH', $message);
    }
}

// Initialize logger
Logger::init();
?>