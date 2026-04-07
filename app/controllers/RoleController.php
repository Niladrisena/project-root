<?php
class RoleController extends Controller {
    private $roleModel;
    private $permissionModel;
    private $logModel;

    public function __construct() {
        // Secure entire controller
        RoleMiddleware::handle('admin'); // Requires at least Admin
        
        $this->roleModel = $this->model('Role');
        $this->permissionModel = $this->model('Permission');
        $this->logModel = $this->model('ActivityLog');
    }

    public function index() {
        $roles = $this->roleModel->getAllRoles();
        $this->view('layouts/main', ['view_content' => 'roles/index', 'roles' => $roles, 'title' => 'Manage Roles']);
    }

    public function manage_permissions($role_id) {
        // Ensure Owner role isn't accidentally modified
        $role = $this->roleModel->findById($role_id);
        if ($role['slug'] === 'owner' && !Auth::role('owner')) {
            $this->redirect('/roles?error=Unauthorized');
        }

        $modules = $this->permissionModel->getAllGroupedByModule();
        $active_permissions = $this->permissionModel->getRolePermissions($role_id);

        $this->view('layouts/main', [
            'view_content' => 'roles/manage_permissions',
            'role' => $role,
            'modules' => $modules,
            'active_permissions' => $active_permissions,
            'title' => 'Manage Permissions - ' . $role['name']
        ]);
    }

    public function sync_permissions() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_verify($_POST['csrf_token']);
            
            $role_id = $_POST['role_id'];
            $permissions = $_POST['permissions'] ?? []; // Array of permission IDs

            if ($this->permissionModel->syncRolePermissions($role_id, $permissions)) {
                $this->logModel->log(Session::get('user_id'), "Updated permissions for Role ID: $role_id", "RBAC");
                $this->jsonResponse(['status' => 'success', 'message' => 'Permissions saved successfully.']);
            } else {
                $this->jsonResponse(['status' => 'error', 'message' => 'Failed to save permissions.'], 500);
            }
        }
    }
}