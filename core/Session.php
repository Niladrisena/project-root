<?php
class Session {
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            
            // 🚀 ELITE FIX: Dynamically detect HTTPS vs HTTP
            $isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' || 
                        (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443);

            ini_set('session.use_only_cookies', 1);
            ini_set('session.use_strict_mode', 1);
            ini_set('session.cookie_httponly', 1);
            // Dynamic secure flag: Works on local HTTP and live HTTPS
            ini_set('session.cookie_secure', $isSecure ? 1 : 0); 
            // Lax allows local network IP persistence
            ini_set('session.cookie_samesite', 'Lax'); 
            ini_set('session.gc_maxlifetime', 1800); // 30 min server-side timeout

            session_start();
        }
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        return $_SESSION[$key] ?? null;
    }

    public static function remove($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function destroy() {
        session_unset();
        session_destroy();
    }

    public static function regenerate() {
        session_regenerate_id(true);
    }
}