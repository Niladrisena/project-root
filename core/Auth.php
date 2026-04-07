<?php
class Auth {
    private static function fallbackModuleAccess($roleSlug, $action, $module) {
        $roleSlug = strtolower((string) $roleSlug);
        $action = strtolower((string) $action);
        $module = strtolower((string) $module);

        $matrix = [
            'owner' => ['*' => ['*']],
            'pm' => [
                'projects' => ['view', 'create', 'edit', 'delete', 'manage'],
            ],
            'hr' => [
                'employees' => ['view', 'create', 'edit', 'delete', 'manage'],
                'hr' => ['view', 'create', 'edit', 'delete', 'manage'],
            ],
            'it' => [
                'it' => ['view', 'create', 'edit', 'delete', 'manage'],
                'assets' => ['view', 'create', 'edit', 'delete', 'manage'],
            ],
            'finance' => [
                'finance' => ['view', 'create', 'edit', 'delete', 'manage', 'view_financials'],
                'payroll' => ['view', 'create', 'edit', 'delete', 'manage'],
                'accounts' => ['view', 'manage', 'view_financials'],
            ],
            'bd' => [
                'bd' => ['view', 'create', 'edit', 'delete', 'manage'],
                'business' => ['view', 'create', 'edit', 'delete', 'manage'],
                'business_development' => ['view', 'create', 'edit', 'delete', 'manage'],
                'leads' => ['view', 'create', 'edit', 'delete', 'manage'],
            ],
        ];

        if (!isset($matrix[$roleSlug])) {
            return false;
        }

        if (isset($matrix[$roleSlug]['*'])) {
            return true;
        }

        if (!isset($matrix[$roleSlug][$module])) {
            return false;
        }

        return in_array($action, $matrix[$roleSlug][$module], true);
    }

    private static function systemRoleMap() {
        return [
            1 => ['slug' => 'owner', 'name' => 'System Owner'],
            2 => ['slug' => 'employee', 'name' => 'Employee'],
            3 => ['slug' => 'hr', 'name' => 'HR Manager'],
            4 => ['slug' => 'pm', 'name' => 'Project Manager'],
            5 => ['slug' => 'it', 'name' => 'IT Manager'],
            6 => ['slug' => 'finance', 'name' => 'Finance Manager'],
            7 => ['slug' => 'bd', 'name' => 'Business Development'],
        ];
    }

    public static function roleMeta($roleId, $fallback = []) {
        $roleId = (int) $roleId;
        $systemRoles = self::systemRoleMap();

        if (isset($systemRoles[$roleId])) {
            return $systemRoles[$roleId];
        }

        return [
            'slug' => $fallback['role_slug'] ?? '',
            'name' => $fallback['role_name'] ?? 'User',
        ];
    }

    public static function check() {
        return Session::get('user_id') !== null;
    }

    public static function user() {
        if (!self::check()) return null;
        
        $db = Database::getInstance();
        $db->query("SELECT u.id, u.first_name, u.last_name, u.email, u.role_id, r.slug as role_slug, r.name as role_name 
                    FROM users u 
                    JOIN roles r ON u.role_id = r.id 
                    WHERE u.id = :id AND u.status = 'active' AND u.deleted_at IS NULL");
        $db->bind(':id', Session::get('user_id'));
        $user = $db->fetch();

        if (!$user) {
            return null;
        }

        $roleMeta = self::roleMeta($user['role_id'] ?? 0, $user);
        $user['role_slug'] = $roleMeta['slug'];
        $user['role_name'] = $roleMeta['name'];

        return $user;
    }

    public static function role($role_slug) {
        $user = self::user();
        return $user && $user['role_slug'] === $role_slug;
    }

    public static function can($permission_action, $module) {
        if (!self::check()) return false;
        $user = self::user();
        
        // Super admin override
        if ($user['role_slug'] === 'owner') return true;

        $db = Database::getInstance();
        $db->query("SELECT p.id FROM permissions p
                    JOIN role_permissions rp ON p.id = rp.permission_id
                    WHERE rp.role_id = :role_id AND p.action = :action AND p.module = :module");
        $db->bind(':role_id', $user['role_id']);
        $db->bind(':action', $permission_action);
        $db->bind(':module', $module);

        try {
            $permission = $db->fetch();
            if (!empty($permission['id'])) {
                return true;
            }
        } catch (\Throwable $e) {
            error_log('[Auth::can] ' . $e->getMessage());
        }

        return self::fallbackModuleAccess($user['role_slug'] ?? '', $permission_action, $module);
    }

    public static function login($user) {
        Session::regenerate();
        $roleMeta = self::roleMeta($user['role_id'] ?? 0, $user);
        Session::set('user_id', $user['id']);
        Session::set('role_slug', $roleMeta['slug']);
        Session::set('role_name', $roleMeta['name']);
        Session::set('last_activity', time());
    }

    public static function logout() {
        Session::destroy();
    }
}
