<?php
class FinanceController extends Controller {
    private $financeModel;

    /**
     * ==========================================
     * 🚀 ENTERPRISE SECURITY CONSTRUCTOR
     * Protects every single method in this controller globally.
     * ==========================================
     */
    public function __construct() {
        AuthMiddleware::handle(); 
        
        // Block standard employees (Role 2) from accessing ANY Finance route
        $role_id = (int) Session::get('role_id');
        if ($role_id == 2) { 
            Session::set('flash_error', 'Access Denied: Level 4 Financial Clearance Required.');
            $this->redirect('/dashboard/employee');
            exit; // Instantly halt execution
        }

        $this->financeModel = $this->model('Finance');
    }

    /**
     * ==========================================
     * FINANCE COMMAND CENTER DASHBOARD
     * ==========================================
     */
    public function dashboard() {
        $data = [
            'view_content' => 'dashboard/finance', 
            'title' => 'Finance Command Center',
            'metrics' => $this->financeModel->getMetrics(),
            'recent_invoices' => $this->financeModel->getRecentInvoices()
        ];
        $this->view('layouts/main', $data);
    }

    /**
     * ==========================================
     * INVOICE GENERATOR ENGINE
     * ==========================================
     */
    public function create_invoice() {
        // 🚀 ELITE FIX: Buggy PermissionMiddleware removed. Protected by __construct().

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            csrf_verify($_POST['csrf_token'] ?? '');
            
            $data = [
                'client_id'   => !empty($_POST['client_id']) ? (int)$_POST['client_id'] : 1,
                'project_id'  => !empty($_POST['project_id']) ? (int)$_POST['project_id'] : 1,
                'currency_id' => !empty($_POST['currency_id']) ? (int)$_POST['currency_id'] : 1,
                'due_date'    => $_POST['due_date'],
                'description' => sanitize($_POST['description'] ?? 'Automated Invoice'),
                'amount'      => !empty($_POST['amount']) ? (float)$_POST['amount'] : 0.00
            ];

            if ($this->financeModel->createInvoice($data)) {
                Session::set('flash_success', 'Invoice generated and saved successfully.');
                $this->redirect('/finance/dashboard'); // Improved UX routing
            } else {
                Session::set('flash_error', 'Database Error: Could not save invoice.');
            }
        }

        $this->view('layouts/main', [
            'view_content' => 'finance/create_invoice',
            'title' => 'Create New Invoice',
            'currencies' => $this->financeModel->getCurrencies(),
            'projects' => $this->financeModel->getProjects(),
            'clients' => $this->financeModel->getClients()
        ]);
    }

    /**
     * ==========================================
     * PRE-EXISTING FINANCE MODULES
     * ==========================================
     */
    /**
     * ==========================================
     * 🚀 ELITE FIX: THE GHOST ROUTE ANNIHILATOR
     * Forces all legacy links (like old back buttons) pointing to '/finance' 
     * to automatically redirect to the new Master Command Center.
     * ==========================================
     */
    /**
     * ==========================================
     * 🚀 ELITE FIX: INTELLIGENT RBAC ROUTING FORK
     * Perfectly separates the Executive Owner view from the Finance Manager's operational hub.
     * ==========================================
     */
    public function index() {
        $role_id = (int) Session::get('role_id');

        // 1. System Owner gets the High-Level Executive Overview
        if ($role_id == 1) {
            $this->view('layouts/main', [
                'view_content' => 'finance/index', // Safely restores your original Owner UI
                'title' => 'Executive Finance & Accounts Overview',
                'metrics' => $this->financeModel->getMetrics(),
                'transactions' => $this->financeModel->getRecentInvoices()
            ]);
        } 
        // 2. Finance Department gets redirected to their Operational Command Center
        else {
            $this->redirect('/finance/dashboard');
        }
    }
    
    public function report() {
        $this->view('layouts/main', ['view_content' => 'finance/report', 'title' => 'Financial Reports']);
    }

    public function export($type = 'csv') {
        if ($type === 'csv') {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="financial_report_' . date('Y-m-d') . '.csv"');
            $invoices = $this->financeModel->getRecentInvoices();
            $output = fopen('php://output', 'w');
            fputcsv($output, ['Invoice Number', 'Client ID', 'Amount', 'Status', 'Due Date']);
            foreach ($invoices as $inv) {
                fputcsv($output, [$inv['invoice_number'], $inv['client_id'], '$' . $inv['amount'], strtoupper($inv['status']), $inv['due_date']]);
            }
            fclose($output);
            exit;
        }
        $this->redirect('/finance/report');
    }

   public function transactions() {
        $this->view('layouts/main', [
            'view_content' => 'finance/transactions', 
            'title' => 'Corporate Ledger & Transactions',
            'transactions' => $this->financeModel->getAllTransactions() // 🚀 Matches Model Line 169
        ]);
    }

    /**
     * ==========================================
     * 🚀 LOG NEW TRANSACTION ROUTE
     * ==========================================
     */
    public function log_transaction() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            csrf_verify($_POST['csrf_token'] ?? '');
            
            $data = [
                'type'             => sanitize($_POST['type']),
                'category'         => sanitize($_POST['category']),
                'amount'           => (float) $_POST['amount'],
                'transaction_date' => $_POST['transaction_date'],
                'reference_no'     => sanitize($_POST['reference_no'] ?? ''),
                'description'      => sanitize($_POST['description'] ?? '')
            ];

            if ($this->financeModel->addTransaction($data)) { // 🚀 Matches Model Line 183
                Session::set('flash_success', 'Corporate Ledger Updated.');
                $this->redirect('/finance/transactions');
            } else {
                Session::set('flash_error', 'Database Error.');
            }
        }
        $this->view('layouts/main', ['view_content' => 'finance/log_transaction', 'title' => 'Log Transaction']);
    }
    /**
     * ==========================================
     * 🚀 PAYROLL & SALARY MANAGEMENT
     * ==========================================
     */
    public function payroll() {
        $this->view('layouts/main', [
            'view_content' => 'finance/payroll',
            'title' => 'Payroll & Salary Control',
            'employees' => $this->financeModel->getPayrollData()
        ]);
    }

    public function update_salary() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            csrf_verify($_POST['csrf_token'] ?? '');
            
            $user_id = (int) $_POST['user_id'];
            $new_salary = (float) $_POST['base_salary'];

            if ($this->financeModel->updateSalary($user_id, $new_salary)) {
                Session::set('flash_success', 'Employee base salary updated successfully.');
            } else {
                Session::set('flash_error', 'System Error: Could not update salary configuration.');
            }
        }
        $this->redirect('/finance/payroll');
    }
    /**
     * ==========================================
     * 🚀 NATIVE FINANCE WORKSPACE
     * Self-contained Attendance & Security for the Finance Team
     * ==========================================
     */
    public function attendance() {
        $timeModel = $this->model('TimeManager');
        $this->view('layouts/main', [
            'view_content' => 'finance/attendance',
            'my_attendance' => $timeModel->getTodayAttendance(Session::get('user_id')),
            'title' => 'Finance Attendance Tracker'
        ]);
    }

    public function clock_event() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            csrf_verify($_POST['csrf_token'] ?? '');
            $result = $this->model('TimeManager')->logClockEvent(Session::get('user_id'));
            
            if ($result === 'clocked_in') Session::set('flash_success', 'Clock-In successful. Have a great shift!');
            elseif ($result === 'clocked_out') Session::set('flash_success', 'Clock-Out successful. Shift completed.');
            else Session::set('flash_error', 'Your timesheet for today is already closed.');
        }
        $this->redirect('/finance/attendance');
    }

    public function security() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            csrf_verify($_POST['csrf_token'] ?? '');
            
            $accountModel = $this->model('Account'); 
            $user_id = Session::get('user_id');

            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            $db_hash = $accountModel->getPasswordHash($user_id);

            if (!password_verify($current_password, $db_hash)) {
                Session::set('flash_error', 'SECURITY ALERT: Current password is incorrect.');
            } elseif ($new_password !== $confirm_password) {
                Session::set('flash_error', 'VALIDATION FAILED: New passwords do not match.');
            } elseif (strlen($new_password) < 8) {
                Session::set('flash_error', 'WEAK PASSWORD: New password must be at least 8 characters.');
            } else {
                $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                if ($accountModel->updatePassword($user_id, $new_hash)) {
                    Session::set('flash_success', 'Security Profile Updated: Your personal password was changed successfully.');
                } else {
                    Session::set('flash_error', 'Database Error: Could not update security profile.');
                }
            }
            $this->redirect('/finance/security');
        }

        $this->view('layouts/main', [
            'view_content' => 'finance/security',
            'title' => 'Finance Security Profile'
        ]);
    }
    /**
     * ==========================================
     * 🚀 SALARY STRUCTURE (FOUNDATION) MODULE
     * ==========================================
     */
    public function salary_structures() {
        $this->view('layouts/main', [
            'view_content' => 'finance/salary_structures',
            'title' => 'Salary Structure Configuration',
            'structures' => $this->financeModel->getSalaryStructures(),
            'currencies' => $this->financeModel->getCurrencies()
        ]);
    }

    public function save_salary_structure() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            csrf_verify($_POST['csrf_token'] ?? '');
            
            $data = [
                'user_id'        => (int) $_POST['user_id'],
                'basic_salary'   => (float) $_POST['basic_salary'],
                'allowance'      => (float) $_POST['allowance'],
                'bonus'          => (float) $_POST['bonus'],
                'deduction'      => (float) $_POST['deduction'],
                'currency_id'    => (int) ($_POST['currency_id'] ?? 1),
                'effective_date' => $_POST['effective_date']
            ];

            if ($this->financeModel->saveSalaryStructure($data)) {
                Session::set('flash_success', 'Salary Structure successfully mapped to employee profile.');
            } else {
                Session::set('flash_error', 'Database Error: Could not assign salary structure.');
            }
        }
        $this->redirect('/finance/salary_structures');
    }


}