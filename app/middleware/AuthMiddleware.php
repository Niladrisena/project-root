<?php
class AuthMiddleware {
    /**
     * Enterprise access control barrier.
     * Prevents unauthorized access to controllers.
     */
    public static function handle() {
        // Ensure Session is started
        if (session_status() === PHP_SESSION_NONE) {
            Session::init();
        }

        // Verify Authentication State (using Auth helper from Step 3)
        if (!Auth::check()) {
            // Set error message for the login view
            Session::set('flash_error', 'Unauthorized Access. Please log in.');
            
            // Redirect securely
            $config = require '../config/app.php';
            $baseUrl = rtrim($config['base_url'], '/');
            header("Location: {$baseUrl}/auth/login");
            exit;
        }

        // Enterprise Security: Check for session timeout (e.g., 30 mins idle)
        $timeout = 1800; 
        if (Session::get('last_activity') && (time() - Session::get('last_activity')) > $timeout) {
            Auth::logout();
            Session::set('flash_error', 'Session expired due to inactivity. Please log in again.');
            $config = require '../config/app.php';
            header("Location: " . $config['base_url'] . "/auth/login");
            exit;
        }
        
        // Update activity timestamp
        Session::set('last_activity', time());
    }
}