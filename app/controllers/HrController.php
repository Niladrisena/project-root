<?php
class HrController extends Controller {
    
    public function dashboard() {
        AuthMiddleware::handle();
        
        // Security: Ensure only HR (or Owner) can access this route
        $role_id = Session::get('role_id');
        if ($role_id == 2) { // Assuming 2 is Employee
            Session::set('flash_error', 'Access Denied: HR clearance required.');
            $this->redirect('/dashboard/employee');
        }

        // Ensure case matches the exact filename (HRDashboard.php)
        $hrModel = $this->model('HRDashboard');

        // ==========================================
        // 🚀 ELITE FIX: THE DATA AGGREGATION PIPELINE
        // ==========================================
        $total_employees = $hrModel->getTotalEmployees();
        $present_today = $hrModel->getTodayAttendanceStats();
        
        // Calculate the missing metrics dynamically
        $absent_today = max(0, $total_employees - $present_today);
        $attendance_rate = $total_employees > 0 ? round(($present_today / $total_employees) * 100) : 100;

        $data = [
            'view_content' => 'dashboard/hr',
            'title' => 'HR Command Center',
            // Assemble the precise array the View is expecting!
            'attendance' => [
                'total' => $total_employees,
                'rate' => $attendance_rate,
                'absent' => $absent_today
            ],
            'pending_leaves' => $hrModel->getPendingLeavesCount(),
            'warnings' => $hrModel->getAttendanceWarnings(),
            'recent_hires' => $hrModel->getRecentHires()
        ];

        $this->view('layouts/main', $data);
    }
    /**
     * ==========================================
     * HR: TIME & ATTENDANCE ROUTING
     * ==========================================
     */
    public function attendance() {
        // Only System Owner (1) and HR (3) can access
        $role_id = (int) Session::get('role_id');
        if (!in_array($role_id, [1, 3])) { header("HTTP/1.1 403 Forbidden"); exit; }

        $timeModel = $this->model('TimeManager');
        $user_id = Session::get('user_id');

        $this->view('layouts/main', [
            'view_content' => 'hr/attendance',
            'my_attendance' => $timeModel->getTodayAttendance($user_id),
            'team_attendance' => $timeModel->getGlobalAttendanceToday(),
            'title' => 'HR Attendance Monitor'
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
        $this->redirect('/hr/attendance');
    }

    public function leaves() {
        $role_id = (int) Session::get('role_id');
        if (!in_array($role_id, [1, 3])) { header("HTTP/1.1 403 Forbidden"); exit; }

        $timeModel = $this->model('TimeManager');

        $this->view('layouts/main', [
            'view_content' => 'hr/leaves',
            'leaves' => $timeModel->getGlobalLeaves(),
            'balances' => $timeModel->getLeaveBalances(),
            'title' => 'HR Leave & Balance Management'
        ]);
    }

    public function process_leave() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            csrf_verify($_POST['csrf_token'] ?? '');
            $timeModel = $this->model('TimeManager');
            
            if (isset($_POST['action_type']) && $_POST['action_type'] === 'apply') {
                $data = [
                    'user_id' => Session::get('user_id'),
                    'leave_type' => sanitize($_POST['leave_type']),
                    'start_date' => $_POST['start_date'],
                    'end_date' => $_POST['end_date'],
                    'reason' => sanitize($_POST['reason'])
                ];
                if ($timeModel->submitLeaveRequest($data)) {
                    Session::set('flash_success', 'Your leave request was successfully submitted.');
                }
            } else {
                // HR Approving/Rejecting a company leave
                $leave_id = (int) $_POST['leave_id'];
                $status = sanitize($_POST['status']);
                if ($timeModel->processLeave($leave_id, $status, Session::get('user_id'))) {
                    Session::set('flash_success', 'Leave request formally ' . strtoupper($status));
                }
            }
        }
        $this->redirect('/hr/leaves');
    }
    /**
     * ==========================================
     * HR PERSONAL SECURITY & ACCOUNT ENGINE
     * Manages the HR Manager's own credentials
     * ==========================================
     */
    public function security() {
        // Security Check: Only HR (3) or System Owner (1)
        $role_id = (int) Session::get('role_id');
        if (!in_array($role_id, [1, 3])) { header("HTTP/1.1 403 Forbidden"); exit; }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            csrf_verify($_POST['csrf_token'] ?? '');
            
            // Reusing your elite Account model!
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
            
            // Redirect back to clear POST data safely
            $this->redirect('/hr/security');
        }

        // Render the Security UI
        $this->view('layouts/main', [
            'view_content' => 'hr/security',
            'title' => 'HR Personal Security'
        ]);
    }
}