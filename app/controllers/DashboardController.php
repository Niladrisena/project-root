<?php
class DashboardController extends Controller {
    private $dashboardModel;

    public function __construct() {
        // 1. Autoloader intercepts this, finds app/middleware/AuthMiddleware.php, and includes it automatically.
        AuthMiddleware::handle(); 
        
        // 2. Load dependencies
        $this->dashboardModel = $this->model('Dashboard');
    }

    public function index() {
        // Fetch user context safely
        $user = Auth::user();
        
        $data = [
            'title' => 'Enterprise Dashboard',
            'user' => $user
        ];

        // Role-based routing
        if (Auth::role('owner') || Auth::role('admin')) {
            $data['stats'] = $this->dashboardModel->getAdminStats();
            $data['activities'] = $this->dashboardModel->getRecentActivity(6);
            $data['admin_documents'] = $this->dashboardModel->getRecentAdminDocuments(8);
            $data['project_status_breakdown'] = $this->dashboardModel->getProjectStatusBreakdown();
            $data['project_updates'] = $this->dashboardModel->getRecentProjectUpdates(7);
            $data['view_content'] = 'dashboard/admin';
        } else {
            $data['stats'] = $this->dashboardModel->getEmployeeStats($user['id']);
            $data['view_content'] = 'dashboard/employee';
        }

        $this->view('layouts/main', $data);
    }

    public function chartData() {
        AuthMiddleware::handle();

        if (!Auth::role('owner') && !Auth::role('admin')) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Forbidden']);
            exit;
        }

        header('Content-Type: application/json');
        echo json_encode($this->dashboardModel->getChartData());
        exit;
    }

    public function employee() {
    AuthMiddleware::handle(); 
    $user_id = Session::get('user_id'); 
    
    $dashboardModel = $this->model('EmployeeDashboard');
    $attendanceModel = $this->model('Attendance');

    // CRITICAL: Fetch today's actual status to prevent the button from resetting
    $todayAttendance = $attendanceModel->getTodayStatus($user_id);

    $data = [
        'view_content' => 'dashboard/employee',
        'title' => 'My Workspace',
        'attendance' => $todayAttendance, // Passes the clock-in/out state to the UI
        'tasks' => $dashboardModel->getActiveTasks($user_id),
        'projects' => $dashboardModel->getAssignedProjects($user_id),
        'weekly_hours' => $dashboardModel->getWeeklyTimesheet($user_id),
        'leave_balance' => $dashboardModel->getLeaveBalance($user_id),
        'assets' => $dashboardModel->getAssignedAssets($user_id),
        'notifications' => $dashboardModel->getNotifications($user_id)
    ];

    $this->view('layouts/main', $data);
}

    public function downloadDocument($id) {
        AuthMiddleware::handle();

        if (!Auth::role('owner') && !Auth::role('admin')) {
            http_response_code(403);
            exit('403 - Forbidden');
        }

        $documentModel = $this->model('AdminDocument');
        $document = $documentModel->findDocumentById($id);

        if (!$document || empty($document['file_path']) || !file_exists($document['file_path'])) {
            http_response_code(404);
            exit('404 - Document not found');
        }

        $mimeType = $document['mime_type'] ?: 'application/octet-stream';
        $downloadName = basename($document['original_name']);

        header('Content-Description: File Transfer');
        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: attachment; filename="' . rawurlencode($downloadName) . '"');
        header('Content-Length: ' . filesize($document['file_path']));
        header('Cache-Control: private, no-transform, no-store, must-revalidate');
        header('Pragma: public');
        header('Expires: 0');
        readfile($document['file_path']);
        exit;
    }

}
