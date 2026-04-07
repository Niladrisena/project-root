<?php
class ItController extends Controller {
    
    public function __construct() {
        AuthMiddleware::handle();
    }

    /**
     * Enforce IT Security Clearance
     */
    private function verifyClearance() {
        $role_id = (int) Session::get('role_id');
        // 1 = Owner, 5 = IT Manager
        if (!in_array($role_id, [1, 5])) {
            header("HTTP/1.1 403 Forbidden");
            $this->view('layouts/main', ['view_content' => 'errors/403', 'title' => 'Access Denied']);
            exit;
        }
    }

    /**
     * Render the Global IT Command Center
     */
    public function dashboard() {
        $this->verifyClearance();

        $itModel = $this->model('ItTicket');
        
        $tickets = $itModel->getAllTickets();
        $stats = $itModel->getGlobalStats();

        $this->view('layouts/main', [
            'view_content' => 'it/dashboard',
            'tickets' => $tickets,
            'stats' => $stats,
            'title' => 'IT Operations Command Center'
        ]);
    }

    /**
     * Endpoint to instantly update ticket statuses
     */
    public function update_status() {
        $this->verifyClearance();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            csrf_verify($_POST['csrf_token'] ?? '');
            
            $ticket_id = (int) $_POST['ticket_id'];
            $new_status = sanitize($_POST['status']);
            
            if ($this->model('ItTicket')->updateStatus($ticket_id, $new_status)) {
                Session::set('flash_success', "Ticket IT-" . str_pad($ticket_id, 4, '0', STR_PAD_LEFT) . " status updated to " . strtoupper(str_replace('_', ' ', $new_status)) . ".");
            } else {
                Session::set('flash_error', 'Database Error: Failed to update ticket status.');
            }
        }
        $this->redirect('/it/dashboard');
    }
    /**
     * ==========================================
     * ENTERPRISE ASSET MANAGEMENT (EAM) ENGINE
     * ==========================================
     */
    public function assets() {
        $this->verifyClearance();
        $assetModel = $this->model('Asset');

        $this->view('layouts/main', [
            'view_content' => 'it/assets',
            'assets' => $assetModel->getAllAssets(),
            'stats' => $assetModel->getStats(),
            'employees' => $assetModel->getActiveEmployees(),
            'title' => 'Asset Fleet Management'
        ]);
    }

    public function register_asset() {
        $this->verifyClearance();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            csrf_verify($_POST['csrf_token'] ?? '');
            
            $data = [
                'category' => sanitize($_POST['category']),
                'brand_model' => sanitize($_POST['brand_model']),
                'serial_number' => sanitize($_POST['serial_number']),
                'purchase_cost' => (float) $_POST['purchase_cost']
            ];

            if ($this->model('Asset')->registerAsset($data, Session::get('user_id'))) {
                Session::set('flash_success', 'New asset registered into the corporate ledger.');
            } else {
                Session::set('flash_error', 'Failed to register asset.');
            }
        }
        $this->redirect('/it/assets');
    }

    public function process_asset_action() {
        $this->verifyClearance();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            csrf_verify($_POST['csrf_token'] ?? '');
            
            $assetModel = $this->model('Asset');
            $admin_id = Session::get('user_id');
            $asset_id = (int) $_POST['asset_id'];
            $action = $_POST['action_type']; // 'assign', 'return', 'damage'
            $notes = sanitize($_POST['notes']);

            if ($action === 'assign') {
                $user_id = (int) $_POST['user_id'];
                $assetModel->assignAsset($asset_id, $user_id, $notes, $admin_id);
                Session::set('flash_success', 'Asset successfully deployed to employee.');
            } 
            elseif ($action === 'return') {
                $assetModel->processReturn($asset_id, 'available', $notes, $admin_id);
                Session::set('flash_success', 'Asset returned to IT inventory successfully.');
            } 
            elseif ($action === 'damage') {
                $assetModel->processReturn($asset_id, 'damaged', $notes, $admin_id);
                Session::set('flash_error', 'Asset flagged as damaged. Requires maintenance review.');
            }
        }
        $this->redirect('/it/assets');
    }
    /**
     * ==========================================
     * ENTERPRISE TIME & ATTENDANCE ROUTING
     * ==========================================
     */
    public function attendance() {
        $this->verifyClearance();
        $timeModel = $this->model('TimeManager');
        $user_id = Session::get('user_id');

        $this->view('layouts/main', [
            'view_content' => 'it/attendance',
            'my_attendance' => $timeModel->getTodayAttendance($user_id),
            'team_attendance' => $timeModel->getGlobalAttendanceToday(),
            'title' => 'Attendance Tracker'
        ]);
    }

    public function clock_event() {
        $this->verifyClearance();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            csrf_verify($_POST['csrf_token'] ?? '');
            $result = $this->model('TimeManager')->logClockEvent(Session::get('user_id'));
            
            if ($result === 'clocked_in') Session::set('flash_success', 'Clock-In successful. Have a great shift!');
            elseif ($result === 'clocked_out') Session::set('flash_success', 'Clock-Out successful. Shift completed.');
            else Session::set('flash_error', 'Your timesheet for today is already closed.');
        }
        $this->redirect('/it/attendance');
    }

    public function leaves() {
        $this->verifyClearance();
        $this->view('layouts/main', [
            'view_content' => 'it/leaves',
            'leaves' => $this->model('TimeManager')->getGlobalLeaves(),
            'title' => 'Leave Management'
        ]);
    }

    public function process_leave() {
        $this->verifyClearance();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            csrf_verify($_POST['csrf_token'] ?? '');
            
            if (isset($_POST['action_type']) && $_POST['action_type'] === 'apply') {
                // Apply for personal leave
                $data = [
                    'user_id' => Session::get('user_id'),
                    'leave_type' => sanitize($_POST['leave_type']),
                    'start_date' => $_POST['start_date'],
                    'end_date' => $_POST['end_date'],
                    'reason' => sanitize($_POST['reason'])
                ];
                if ($this->model('TimeManager')->submitLeaveRequest($data)) {
                    Session::set('flash_success', 'Leave request successfully submitted for review.');
                }
            } else {
                // Manager approving/rejecting a team leave
                $leave_id = (int) $_POST['leave_id'];
                $status = sanitize($_POST['status']);
                if ($this->model('TimeManager')->processLeave($leave_id, $status, Session::get('user_id'))) {
                    Session::set('flash_success', 'Leave request status updated to ' . strtoupper($status));
                }
            }
        }
        $this->redirect('/it/leaves');
    }
    /**
     * ==========================================
     * GLOBAL SYSTEM CONFIGURATION ENGINE
     * ==========================================
     */
    public function settings() {
        $this->verifyClearance();
        $settingModel = $this->model('Setting');

        $this->view('layouts/main', [
            'view_content' => 'it/settings',
            'settings' => $settingModel->getAllSettings(),
            'title' => 'Global System Configuration'
        ]);
    }

    public function update_settings() {
        $this->verifyClearance();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            csrf_verify($_POST['csrf_token'] ?? '');
            
            // Clean and prepare the data payload
            $data = [
                'company_name' => sanitize($_POST['company_name']),
                'support_email' => sanitize($_POST['support_email']),
                'system_timezone' => sanitize($_POST['system_timezone']),
                // Maintenance mode checkbox (if checked, it sends '1', otherwise defaults to '0')
                'maintenance_mode' => isset($_POST['maintenance_mode']) ? '1' : '0',
                'session_timeout_minutes' => (int) $_POST['session_timeout_minutes'],
                'max_file_upload_mb' => (int) $_POST['max_file_upload_mb'],
                'password_policy' => sanitize($_POST['password_policy'])
            ];

            if ($this->model('Setting')->updateSettings($data, Session::get('user_id'))) {
                Session::set('flash_success', 'Global System Configurations successfully updated across all nodes.');
            } else {
                Session::set('flash_error', 'Database Error: Failed to synchronize settings.');
            }
        }
        $this->redirect('/it/settings');
    }
    /**
     * ==========================================
     * PERSONAL SECURITY & ACCOUNT ENGINE
     * Manages the IT Manager's own credentials
     * ==========================================
     */
    public function security() {
        $this->verifyClearance();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            csrf_verify($_POST['csrf_token'] ?? '');
            
            // Reusing the elite Account model we built earlier!
            $accountModel = $this->model('Account'); 
            $user_id = Session::get('user_id');

            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            // 1. Fetch current hash from DB
            $db_hash = $accountModel->getPasswordHash($user_id);

            // 2. Strict Validation Engine
            if (!password_verify($current_password, $db_hash)) {
                Session::set('flash_error', 'SECURITY ALERT: Current password is incorrect.');
            } elseif ($new_password !== $confirm_password) {
                Session::set('flash_error', 'VALIDATION FAILED: New passwords do not match.');
            } elseif (strlen($new_password) < 8) {
                Session::set('flash_error', 'WEAK PASSWORD: New password must be at least 8 characters.');
            } else {
                // 3. Cryptographic Hashing & Update
                $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                
                if ($accountModel->updatePassword($user_id, $new_hash)) {
                    Session::set('flash_success', 'Security Profile Updated: Your personal password was changed successfully.');
                } else {
                    Session::set('flash_error', 'Database Error: Could not update security profile.');
                }
            }
            
            // Redirect back to clear POST data
            $this->redirect('/it/security');
        }

        // Render the Security UI
        $this->view('layouts/main', [
            'view_content' => 'it/security',
            'title' => 'Personal Account Security'
        ]);
    }

}