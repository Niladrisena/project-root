<?php
class OwnerMiddleware {
    public static function handle() {
        AuthMiddleware::handle();
        if (!Auth::role('owner')) {
            http_response_code(403);
            die('403 - Strict Owner Access Only.');
        }
    }
}