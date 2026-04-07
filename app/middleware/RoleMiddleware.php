<?php
class RoleMiddleware {
    public static function handle($required_role) {
        AuthMiddleware::handle(); // Ensure logged in
        
        if (!Auth::role($required_role) && !Auth::role('owner')) {
            http_response_code(403);
            die('403 - Unauthorized Access. Role restriction.');
        }
    }
}