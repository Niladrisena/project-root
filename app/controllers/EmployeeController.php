<?php
class EmployeeController extends Controller {
    private $userModel;

    public function __construct() {
        AuthMiddleware::handle(); 
        $this->userModel = $this->model('User');
    }

    public function index() {
        // GOD LEVEL FIX: Unified RBAC Engine Call for 'employees'
        PermissionMiddleware::handle('view', 'employees');
        
        $employees = $this->userModel->getAllEmployees();
        
        $this->view('layouts/main', [
            'view_content' => 'employees/index',
            'employees' => $employees,
            'title' => 'HR & Employees Directory'
        ]);
    }

    /**
     * ==========================================
     * ADVANCED ONBOARDING ENGINE
     * ==========================================
     */
    public function create() {
        // GOD LEVEL FIX: Connects to Middleware perfectly. 
        // Role 3 (HR) is instantly authorized for 'employees'!
        PermissionMiddleware::handle('create', 'employees');

        // 1. HANDLE FORM SUBMISSION (POST)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Enterprise CSRF Protection
            csrf_verify($_POST['csrf_token'] ?? '');

            // Enterprise data extraction with 100% crash-proof fallbacks
            $data = [
                'first_name' => sanitize($_POST['first_name'] ?? ''),
                'last_name' => sanitize($_POST['last_name'] ?? ''),
                'email' => sanitize($_POST['email'] ?? ''),
                'password' => $_POST['password'] ?? '', 
                'phone' => sanitize($_POST['phone'] ?? ''),
                'role_id' => !empty($_POST['role_id']) ? $_POST['role_id'] : 2, 
                'department_id' => !empty($_POST['department_id']) ? $_POST['department_id'] : null,
                'designation_id' => !empty($_POST['designation_id']) ? $_POST['designation_id'] : null,
                'manager_id' => !empty($_POST['manager_id']) ? $_POST['manager_id'] : null,
                'join_date' => !empty($_POST['join_date']) ? $_POST['join_date'] : date('Y-m-d'),
                'salary' => !empty($_POST['salary']) ? $_POST['salary'] : 0.00
            ];

            // Attempt to save to database
            if ($this->userModel->createUser($data)) {
                Session::set('flash_success', 'Employee registered securely and mapped to corporate hierarchy.');
                // Smart Redirect: Send HR back to HR Dashboard, send Owner to Employee Directory
                $this->redirect(Session::get('role_id') == 3 ? '/hr/dashboard' : '/employee');
            } else {
                Session::set('flash_error', 'Database Error: Could not save the employee record.');
            }
        }

       // 2. HANDLE VIEW RENDERING (GET)
        // Elite MVC practice: Ask the model, don't touch its database!
        $managers = $this->userModel->getActiveManagers();

        // Load the view
        $this->view('layouts/main', [
            'view_content' => 'employees/create', // Ensures it matches your folder structure
            'managers' => $managers,
            'roles' => [], 
            'title' => 'Onboard Personnel'
        ]);
    }

    /**
     * Show Employee Profile
     */
    public function show($id) {
        PermissionMiddleware::handle('view', 'employees');
        
        $employee = $this->userModel->getEmployeeById($id);
        
        if (!$employee) {
            Session::set('flash_error', 'Employee not found.');
            $this->redirect('/employee');
        }

        $this->view('layouts/main', [
            'view_content' => 'employees/show',
            'employee' => $employee,
            'title' => 'Employee Profile: ' . sanitize($employee['first_name'])
        ]);
    }

    /**
     * Show Edit Form
     */
    public function edit($id) {
        PermissionMiddleware::handle('edit', 'employees');
        
        $employee = $this->userModel->getEmployeeById($id);
        
        if (!$employee) {
            Session::set('flash_error', 'Employee not found.');
            $this->redirect('/employee');
        }

        $this->view('layouts/main', [
            'view_content' => 'employees/edit',
            'employee' => $employee,
            'roles' => [], 
            'title' => 'Edit Employee: ' . sanitize($employee['first_name'])
        ]);
    }
}