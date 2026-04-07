<?php
class TaskController extends Controller {
    private $taskModel;

    public function __construct() {
        AuthMiddleware::handle();
        $this->taskModel = $this->model('Task');
    }

    /* ========================================================
       ADMIN / OWNER MODULE (EXISTING CODE)
       ======================================================== */

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            csrf_verify($_POST['csrf_token'] ?? ''); // Enterprise security check

            $projectId = $_POST['project_id'];

            $data = [
                'project_id' => $projectId,
                'title' => sanitize($_POST['title']),
                'status' => sanitize($_POST['status']),
                'deadline' => $_POST['deadline'],
                'assigned_to' => Session::get('user_id') // Default assignment
            ];

            if ($this->taskModel->createTask($data)) {
                Session::set('flash_success', 'Task added successfully.');
            } else {
                Session::set('flash_error', 'Failed to add task to database.');
            }

            // Redirect back to the project dashboard
            $this->redirect('/project/show/' . $projectId);
        }
    }

    /* ========================================================
       EMPLOYEE WORKSPACE & AJAX MODULE (NEW CODE)
       ======================================================== */

    /**
     * Employee Task Board (Kanban & Priority Workspace)
     */
    public function board() {
        AuthMiddleware::handle(); 
        $user_id = Session::get('user_id');

        $this->view('layouts/main', [
            'view_content' => 'tasks/index',
            'title' => 'My Task Center',
            'tasks' => $this->taskModel->getMyTasks($user_id) // Fetching the dynamic data!
        ]);
    }

    /**
     * AJAX Endpoint: Update Status
     */
    public function ajax_update_status() {
        AuthMiddleware::handle();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $task_id = $_POST['task_id'] ?? 0;
            $status = $_POST['status'] ?? 'pending';
            $user_id = Session::get('user_id');
            
            $success = $this->taskModel->updateStatus($task_id, $user_id, $status);
            echo json_encode(['success' => $success]);
            exit;
        }
    }

    /**
     * AJAX Endpoint: Toggle Timer
     */
    public function ajax_toggle_timer() {
        AuthMiddleware::handle();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $task_id = $_POST['task_id'] ?? 0;
            $user_id = Session::get('user_id');
            
            $result = $this->taskModel->toggleTimer($task_id, $user_id);
            echo json_encode(['success' => true, 'data' => $result]);
            exit;
        }
    }
}