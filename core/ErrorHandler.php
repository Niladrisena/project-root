<?php
class ErrorHandler {
    
    public static function init() {
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
        error_reporting(E_ALL);

        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleFatalError']);
    }

    public static function handleError($level, $message, $file, $line) {
        if (error_reporting() !== 0) {
            throw new ErrorException($message, 0, $level, $file, $line);
        }
    }

    public static function handleException($exception) {
        $code = $exception->getCode();
        if ($code != 404) $code = 500;
        
        http_response_code($code);

        $logMessage = "[" . date('Y-m-d H:i:s') . "] ";
        $logMessage .= "Uncaught Exception: '" . get_class($exception) . "'\n";
        $logMessage .= "Message: '" . $exception->getMessage() . "'\n";
        $logMessage .= "Stack trace: " . $exception->getTraceAsString() . "\n";
        $logMessage .= "Thrown in '" . $exception->getFile() . "' on line " . $exception->getLine() . "\n\n";

        // ENTERPRISE FIX: Absolute path resolution
        $rootPath = dirname(__DIR__);
        $logDir = $rootPath . '/storage/logs/';
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        error_log($logMessage, 3, $logDir . 'error.log');

        self::renderFriendlyError($code, $exception->getMessage());
    }

    public static function handleFatalError() {
        $error = error_get_last();
        if ($error !== null && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
            self::handleException(new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']));
        }
    }

    private static function renderFriendlyError($code, $devMessage = '') {
        // ENTERPRISE FIX: Safely load config using absolute root path
        $rootPath = dirname(__DIR__);
        $configFile = $rootPath . '/config/app.php';
        
        $env = 'production';
        if (file_exists($configFile)) {
            $config = require $configFile;
            $env = $config['environment'] ?? 'production';
        }

        if ($env === 'development') {
            ini_set('display_errors', 1);
            echo "<pre style='background:#111; color:#ff5555; padding:20px;'>FATAL ERROR: {$devMessage}</pre>";
            return; 
        }

        echo "<div style='font-family: sans-serif; text-align: center; padding: 50px; background: #f9fafb; height: 100vh;'>";
        echo "<h1 style='color: #1f2937; font-size: 3rem;'>System Error {$code}</h1>";
        echo "<p style='color: #6b7280;'>An unexpected error occurred. Administrators have been notified.</p>";
        echo "<a href='/' style='color: #2563eb; text-decoration: none; font-weight: bold;'>Return to Dashboard</a>";
        echo "</div>";
        exit;
    }
}