<?php
class LeaveController extends Controller {
    
    public function index() {
        AuthMiddleware::handle();
        $user_id = Session::get('user_id');
        $leaveModel = $this->model('Leave');

        $data = [
            'view_content' => 'leave/index',
            'title' => 'Leave Management',
            'balance' => $leaveModel->getLeaveBalance($user_id),
            'history' => $leaveModel->getLeaveHistory($user_id)
        ];

        $this->view('layouts/main', $data);
    }

    public function store() {
        AuthMiddleware::handle();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            csrf_verify($_POST['csrf_token'] ?? '');

            $leaveModel = $this->model('Leave');
            $user_id = Session::get('user_id');
            
            // Basic validation to ensure end date isn't before start date
            if (strtotime($_POST['end_date']) < strtotime($_POST['start_date'])) {
                Session::set('flash_error', 'End date cannot be earlier than start date.');
                header('Location: ' . base_url('/leave'));
                exit;
            }

            $data = [
                'user_id' => $user_id,
                'leave_type' => sanitize($_POST['leave_type']),
                'start_date' => $_POST['start_date'],
                'end_date' => $_POST['end_date'],
                'reason' => sanitize($_POST['reason'])
            ];

            if ($leaveModel->applyForLeave($data)) {
                Session::set('flash_success', 'Leave application submitted successfully. Pending HR approval.');
            } else {
                Session::set('flash_error', 'System Error: Could not submit leave application.');
            }
            
            header('Location: ' . base_url('/leave'));
            exit;
        }
    }
    /**
     * ==========================================
     * HR LEAVE APPROVAL ENGINE (CONTROLLER)
     * ==========================================
     */
    
    // Renders the HR Leave Management Dashboard
    public function manage() {
        // Secure it! Only HR (3) and Owners (1) can access this module
        PermissionMiddleware::handle('view', 'employees');

        $leaveModel = $this->model('Leave');
        $leaves = $leaveModel->getAllLeaves();

        $this->view('layouts/main', [
            'view_content' => 'leave/manage', // The UI we are about to build
            'leaves' => $leaves,
            'title' => 'Leave Requests Management'
        ]);
    }

    // Handles the actual Approve/Reject POST request
    public function process() {
        PermissionMiddleware::handle('edit', 'employees');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            csrf_verify($_POST['csrf_token'] ?? '');
            
            $leave_id = $_POST['leave_id'];
            $action = $_POST['action']; // Will be 'approved' or 'rejected'

            if (in_array($action, ['approved', 'rejected'])) {
                $leaveModel = $this->model('Leave');
                
                if ($leaveModel->updateLeaveStatus($leave_id, $action)) {
                    Session::set('flash_success', 'Time-off request has been ' . $action . ' successfully.');
                } else {
                    Session::set('flash_error', 'Database Error: Could not process request.');
                }
            }
            // Bounce right back to the management board
            $this->redirect('/leave/manage');
        }
    }
}