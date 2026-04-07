<?php
class PmController extends Controller {
    
    public function __construct() {
        AuthMiddleware::handle();
    }

    public function dashboard() {
        $role_id = (int) Session::get('role_id');
        if (!in_array($role_id, [1, 4])) {
            header("HTTP/1.1 403 Forbidden");
            $this->view('layouts/main', ['view_content' => 'errors/403', 'title' => 'Access Denied']);
            exit;
        }

        $projectModel = $this->model('Project');
        $manager_id = Session::get('user_id');

        // Fetch Elite Analytics
        $stats = $projectModel->getPmDashboardStats($manager_id);
        $health_metrics = $projectModel->getProjectHealthMetrics($manager_id);

        // Generate AI-Like System Suggestions based on data
        $suggestions = [];
        $critical_count = 0;
        foreach ($health_metrics as $p) {
            if ($p['health_color'] === 'red') {
                $critical_count++;
                $suggestions[] = [
                    'type' => 'danger',
                    'message' => "<b>{$p['name']}</b> is at critical risk. Time elapsed is {$p['time_elapsed_pct']}% but progress is only {$p['progress_pct']}%. Reallocate resources immediately."
                ];
            } elseif ($p['health_color'] === 'yellow') {
                $suggestions[] = [
                    'type' => 'warning',
                    'message' => "<b>{$p['name']}</b> is falling behind schedule. Review blocking tasks."
                ];
            }
        }

        if (empty($suggestions) && $stats['active_projects'] > 0) {
            $suggestions[] = ['type' => 'success', 'message' => "All active projects are currently on track. Excellent resource management."];
        } elseif ($stats['active_projects'] == 0) {
            $suggestions[] = ['type' => 'info', 'message' => "Your portfolio is empty. Click 'New Project' to initiate a workflow."];
        }

        $this->view('layouts/main', [
            'view_content' => 'pm/dashboard',
            'stats' => $stats,
            'health_metrics' => $health_metrics,
            'suggestions' => $suggestions,
            'critical_count' => $critical_count,
            'title' => 'Project Command Center'
        ]);
    }

    private function ensurePmAccess() {
        $role_id = (int) Session::get('role_id');
        if (!in_array($role_id, [1, 4])) {
            header("HTTP/1.1 403 Forbidden");
            $this->view('layouts/main', ['view_content' => 'errors/403', 'title' => 'Access Denied']);
            exit;
        }
    }

    private function renderBlankTab($title, $description) {
        $this->ensurePmAccess();

        $this->view('layouts/main', [
            'view_content' => 'pm/blank_tab',
            'title' => $title,
            'page_title' => $title,
            'page_description' => $description
        ]);
    }

    public function resource_management() {
        $this->ensurePmAccess();

        $user = Auth::user();
        $projectModel = $this->model('Project');
        $projects = $projectModel->getAllForUser($user['id'], $user['role_slug']);

        $this->view('layouts/main', [
            'view_content' => 'projects/index',
            'projects' => $projects,
            'title' => 'Resource Management',
        ]);
    }

    public function hour_utilization_report() {
        $this->renderBlankTab('Hour Utilization Report', 'A blank workspace for future hour utilization summaries, timesheet insights, and team capacity reporting.');
    }

    public function revenue_management() {
        $this->renderBlankTab('Revenue Management', 'A blank workspace for future revenue tracking, billing visibility, and project financial summaries.');
    }

    public function overall_backlog() {
        $this->renderBlankTab('Overall Backlog', 'A blank workspace for future backlog review, prioritization, and pending work tracking.');
    }

    /**
     * ==========================================
     * PROJECT INITIATION ENGINE
     * Mapped perfectly to your advanced Project.php Model!
     * ==========================================
     */
    public function create_project() {
        $role_id = (int) Session::get('role_id');
        if (!in_array($role_id, [1, 4])) {
            header("HTTP/1.1 403 Forbidden");
            $this->view('layouts/main', ['view_content' => 'errors/403', 'title' => 'Access Denied']);
            exit;
        }

        $projectModel = $this->model('Project');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            csrf_verify($_POST['csrf_token'] ?? '');
            
            $data = [
                'project_manager_id' => Session::get('user_id'),
                'name' => sanitize($_POST['name']),
                'description' => sanitize($_POST['description']),
                'client_id' => !empty($_POST['client_id']) ? (int)$_POST['client_id'] : null,
                'currency_id' => !empty($_POST['currency_id']) ? (int)$_POST['currency_id'] : 1,
                'estimated_budget' => (float) $_POST['estimated_budget'],
                'total_hours' => (float) $_POST['total_hours'], // 🚀 Hour Tracker
                'priority' => $_POST['priority'],
                'start_date' => $_POST['start_date'],
                'deadline' => $_POST['deadline'],
                'status' => 'planning'
            ];

            $project_id = $projectModel->createProject($data);

            if ($project_id) {
                // 🚀 MASS ASSIGNMENT ENGINE: Distribute hours to the team
                if (!empty($_POST['team_hours']) && is_array($_POST['team_hours'])) {
                    foreach ($_POST['team_hours'] as $uid => $hours) {
                        $hours = (float) $hours;
                        if ($hours > 0) { // Only assign if the PM gave them hours
                            $projectModel->assignMember($project_id, $uid, 'member', $hours);
                        }
                    }
                }

                Session::set('flash_success', 'Project created. Hours successfully distributed across the team.');
                $this->redirect('/pm/dashboard');
            } else {
                Session::set('flash_error', 'Database Error: Could not initialize project.');
            }
        }

        // Fetch employees to show in the allocation grid
        $employees = $projectModel->getAvailableResources();

        $this->view('layouts/main', [
            'view_content' => 'pm/create_project',
            'clients' => [], 
            'currencies' => [],
            'employees' => $employees, // 🚀 Pass to UI
            'title' => 'Initiate New Project'
        ]);
    }
    /**
     * ==========================================
     * PM TEAM ROSTER
     * ==========================================
     */
    public function team() {
        $role_id = (int) Session::get('role_id');
        if (!in_array($role_id, [1, 4])) {
            header("HTTP/1.1 403 Forbidden");
            $this->view('layouts/main', ['view_content' => 'errors/403', 'title' => 'Access Denied']);
            exit;
        }

        $projectModel = $this->model('Project');
        
        // Fetch only the team members assigned to this specific PM
        $team = $projectModel->getPmTeam(Session::get('user_id'));

        $this->view('layouts/main', [
            'view_content' => 'pm/team',
            'team' => $team,
            'title' => 'My Project Team'
        ]);
    }/**
     * ==========================================
     * IT HELP DESK & ASSET MANAGEMENT
     * ==========================================
     */
    public function it_desk() {
        $role_id = (int) Session::get('role_id');
        if (!in_array($role_id, [1, 4])) {
            header("HTTP/1.1 403 Forbidden");
            $this->view('layouts/main', ['view_content' => 'errors/403', 'title' => 'Access Denied']);
            exit;
        }

        $itModel = $this->model('ItTicket');
        $user_id = Session::get('user_id');

        $tickets = $itModel->getUserTickets($user_id);
        $stats = $itModel->getTicketStats($user_id);

        $this->view('layouts/main', [
            'view_content' => 'pm/it_desk',
            'tickets' => $tickets,
            'stats' => $stats,
            'title' => 'IT Help Desk & Asset Requests'
        ]);
    }

    public function request_it_support() {
        $role_id = (int) Session::get('role_id');
        if (!in_array($role_id, [1, 4])) {
            header("HTTP/1.1 403 Forbidden");
            $this->view('layouts/main', ['view_content' => 'errors/403', 'title' => 'Access Denied']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            csrf_verify($_POST['csrf_token'] ?? '');
            
            $itModel = $this->model('ItTicket');
            
            // 🚀 ELITE FIX: Capture the exact asset if 'asset_request' is chosen
            $ticket_type = $_POST['ticket_type'];
            $asset_type = ($ticket_type === 'asset_request' && !empty($_POST['asset_type'])) ? sanitize($_POST['asset_type']) : null;

            $data = [
                'user_id' => Session::get('user_id'),
                'ticket_type' => $ticket_type,
                'asset_type' => $asset_type,
                'priority' => $_POST['priority'],
                'subject' => sanitize($_POST['subject']),
                'description' => sanitize($_POST['description'])
            ];

            if ($itModel->createTicket($data)) {
                Session::set('flash_success', 'Request successfully submitted. IT/Provisioning will be notified immediately.');
                $this->redirect('/pm/it_desk');
            } else {
                Session::set('flash_error', 'Database Error: Could not submit the request.');
            }
        }

        $userModel = $this->model('User');
        $employeeSuggestions = $userModel->getActiveEmployeeSuggestions();

        $this->view('layouts/main', [
            'view_content' => 'pm/request_it_support',
            'employeeSuggestions' => $employeeSuggestions,
            'title' => 'Submit IT Request'
        ]);
    }
    /**
     * ==========================================
     * SECURITY & ACCOUNT SETTINGS ENGINE
     * ==========================================
     */
    public function settings() {
        $role_id = (int) Session::get('role_id');
        if (!in_array($role_id, [1, 4])) {
            header("HTTP/1.1 403 Forbidden");
            $this->view('layouts/main', ['view_content' => 'errors/403', 'title' => 'Access Denied']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            csrf_verify($_POST['csrf_token'] ?? '');
            
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
                    Session::set('flash_success', 'Security Profile Updated: Password changed successfully.');
                } else {
                    Session::set('flash_error', 'Database Error: Could not update security profile.');
                }
            }
            
            // Redirect back to settings to clear POST data
            $this->redirect('/pm/settings');
        }

        // Render the Security UI
        $this->view('layouts/main', [
            'view_content' => 'pm/settings',
            'title' => 'Account Security'
        ]);
    }

}
