<?php
// ENTERPRISE FIX: Define global absolute root path
define('ROOT_PATH', realpath(__DIR__ . '/../'));

// 1. Enterprise Error & Exception Handling Initialization
require_once ROOT_PATH . '/core/ErrorHandler.php';
ErrorHandler::init();

// 2. Initialize Session
require_once ROOT_PATH . '/core/Session.php';
Session::init();

// 3. Explicitly Load Helper Functions
if (file_exists(ROOT_PATH . '/app/helpers/functions.php')) {
    require_once ROOT_PATH . '/app/helpers/functions.php';
    
}

// 4. Global SPL Autoloader
spl_autoload_register(function ($className) {
    $directories = [
        ROOT_PATH . '/core/',
        ROOT_PATH . '/app/controllers/',
        ROOT_PATH . '/app/models/',
        ROOT_PATH . '/app/middleware/'
    ];

    $className = str_replace('\\', '/', $className);

    foreach ($directories as $directory) {
        $file = $directory . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// =========================================================================
// 5. 🚀 ENTERPRISE CONFIGURATOR IGNITION
// Overrides default PHP server settings (Timezone, Locale) and locks 
// Global Constants (Currency, Company Name) before the MVC Router boots.
// =========================================================================
require_once ROOT_PATH . '/core/SystemConfig.php';
SystemConfig::apply();

// 6. Boot the Application
$app = new App();