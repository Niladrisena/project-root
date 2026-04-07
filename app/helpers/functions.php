<?php
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * 🚀 Generates a secure CSRF token and binds it to the active session
 */
function csrf_token() {
    if (!Session::get('csrf_token')) {
        Session::set('csrf_token', bin2hex(random_bytes(32)));
    }
    return Session::get('csrf_token');
}

/**
 * 🚀 Validates the token safely without crashing the application
 */
function csrf_verify($token) {
    $sessionToken = Session::get('csrf_token') ?? '';
    
    // hash_equals prevents timing attacks during string comparison
    if (empty($token) || !hash_equals($sessionToken, $token)) {
        
        // Destroy the compromised token to force a fresh one
        Session::remove('csrf_token'); 
        
        // Set a polite UI warning instead of a white screen of death
        Session::set('flash_error', 'Security session expired or network changed. Please login again.');
        
        // Safely redirect back to login (or referrer if applicable)
        $redirectUrl = function_exists('base_url') ? base_url('/auth/login') : '/';
        header("Location: " . $redirectUrl);
        exit;
    }
    return true;
}

function base_url($path = '') {
    $config = require '../config/app.php';
    // Remove trailing slash from config URL, remove leading slash from path
    $baseUrl = rtrim($config['base_url'], '/');
    $path = ltrim($path, '/');
    
    return $path ? $baseUrl . '/' . $path : $baseUrl;
}

function dd($data) {
    echo '<pre style="background:#111; color:#0f0; padding:10px; z-index:9999; position:relative;">';
    print_r($data);
    echo '</pre>';
    die();
}

function __($phrase) {
    static $lang_data = null;
    if ($lang_data === null) {
        $file = (defined('ROOT_PATH') ? ROOT_PATH : '..') . '/languages/' . (defined('SYS_LANG') ? SYS_LANG : 'en') . '.json';
        $lang_data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    }
    return $lang_data[$phrase] ?? $phrase;
}