<?php
class TimesheetController extends Controller {
    
    public function index() {
        AuthMiddleware::handle();
        $user_id = Session::get('user_id');

        // Initialize Models
        $timesheetModel = $this->model('Timesheet');
        // Fetch projects so the employee can select which project they worked on
        $projectModel = $this->model('Project'); 

        // Fetch dashboard data safely
        $data = [
            'view_content' => 'timesheet/index',
            'title' => 'My Timesheet',
            'logs' => $timesheetModel->getPersonalLogs($user_id) ?? [],
            'chart_data' => $timesheetModel->getWeeklyBreakdown($user_id) ?? [],
            'projects' => $projectModel->getAssignedProjects($user_id) ?? []
        ];

        $this->view('layouts/main', $data);
    }
public function store() {
        AuthMiddleware::handle();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            csrf_verify($_POST['csrf_token'] ?? '');

            $timesheetModel = $this->model('Timesheet');
            
            // ELITE DEFENSIVE PROGRAMMING: 
            // Use ?? to guarantee PHP never crashes if a frontend key is missing.
            $hours = $_POST['hours'] ?? null;
            $project_id = $_POST['project_id'] ?? 0; // Default to 'General' if missing
            $date = $_POST['date'] ?? date('Y-m-d');
            $description = sanitize($_POST['description'] ?? 'No description provided');

            // Hard Validation Check: Reject if hours were completely dropped
            if ($hours === null || $hours <= 0) {
                Session::set('flash_error', 'Validation Failed: Please enter valid working hours.');
                header('Location: ' . base_url('/timesheet'));
                exit;
            }

            // Map the sanitized and verified data
            $data = [
                'user_id' => Session::get('user_id'),
                'project_id' => $project_id,
                'date' => $date,
                'hours_worked' => $hours,
                'description' => $description,
                'status' => 'pending' 
            ];

            if ($timesheetModel->addEntry($data)) {
                Session::set('flash_success', 'Time logged successfully! Waiting for manager approval.');
            } else {
                Session::set('flash_error', 'System Error: Could not log your time. Please try again.');
            }
            
            // Absolute redirect back to the timesheet page
            header('Location: ' . base_url('/timesheet'));
            exit;
        }
    }
}