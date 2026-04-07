<?php
class PermissionMiddleware {
    
    /**
     * Enterprise RBAC Engine
     * Evaluates if the current session role has clearance for the requested module.
     */
    public static function handle($action = '', $module = '') {
        $role_id = Session::get('role_id');

        // 1. System Owner (Global Admin) gets absolute access to everything
        if ($role_id == 1) {
            return true;
        }

        // 2. Dynamic Role Clearance Matrix
        $authorized = false;

        switch ($module) {
            case 'hr':
            case 'employees':
                if ($role_id == 3) $authorized = true; // HR Manager Clearance
                break;
            case 'assets':
            case 'it':
                if ($role_id == 5) $authorized = true; // IT Manager Clearance
                break;
            case 'finance':
            case 'payroll':
                if ($role_id == 6) $authorized = true; // Finance Clearance
                break;
            case 'projects':
                if ($role_id == 4) $authorized = true; // Project Manager Clearance
                break;
            case 'bd':
            case 'business':
            case 'leads':
                if ($role_id == 7) $authorized = true; // Business Development
                break;
        }

        // 3. Graceful Denial Engine (Fixes the crash and the ugly UI)
        if (!$authorized) {
            header("HTTP/1.1 403 Forbidden");
            self::renderElite403();
            exit;
        }

        return true;
    }

    /**
     * Standalone 403 Renderer
     * By echoing this directly with the Tailwind CDN, we guarantee it NEVER crashes 
     * due to file path directory issues, and it always looks like a million-dollar SaaS.
     */
    private static function renderElite403() {
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>403 - Access Denied</title>
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body class="bg-gray-50 flex items-center justify-center h-screen">
            <div class="max-w-2xl mx-auto text-center py-20 px-4">
                <div class="w-32 h-32 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-8 shadow-sm border border-red-100">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h1 class="text-7xl font-black text-gray-900 tracking-tight">403</h1>
                <h2 class="text-2xl font-bold text-gray-800 mt-4">Security Clearance Required</h2>
                <p class="text-gray-500 mt-3 max-w-md mx-auto">Your current role profile does not have the necessary permissions to access this module. Please contact the System Administrator if you believe this is a mistake.</p>
                <button onclick="history.back()" class="mt-8 px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-md transition">&larr; Return to Safety</button>
            </div>
        </body>
        </html>';
    }
}