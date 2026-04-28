<?php
class AdminpanelController extends Controller {
    private $projectModel;
    private $taskModel;

    public function __construct() {
        AuthMiddleware::handle();

        if (!Auth::role('owner') && !Auth::role('admin')) {
            http_response_code(403);
            exit('403 - Forbidden');
        }

        $this->projectModel = $this->model('Project');
        $this->taskModel = $this->model('Task');
    }

    public function projectManagement() {
        $projects = $this->projectModel->getAdminProjectCatalog();

        $this->view('layouts/main', [
            'title' => 'Admin Project Management',
            'projects' => $projects,
            'view_content' => 'adminpanel/project_management'
        ]);
    }

    public function projectShow($id) {
        $project = $this->projectModel->getAdminProjectDetails((int) $id);
        if (!$project) {
            http_response_code(404);
            exit('404 - Project not found');
        }

        $tasks = $this->taskModel->getTasksByProject((int) $id);
        $resources = $this->projectModel->getProjectResources((int) $id);
        $files = $this->projectModel->getProjectFiles((int) $id);
        $progress = $this->projectModel->calculateProgress((int) $id);

        $statusCounts = [
            'todo' => 0,
            'in_progress' => 0,
            'review' => 0,
            'completed' => 0,
        ];

        foreach ($tasks as $task) {
            $status = $task['status'] ?? 'todo';
            if ($status === 'done') {
                $status = 'completed';
            }
            if (isset($statusCounts[$status])) {
                $statusCounts[$status]++;
            }
        }

        $this->view('layouts/main', [
            'title' => 'Project Details',
            'project' => $project,
            'tasks' => $tasks,
            'resources' => $resources,
            'files' => $files,
            'progress' => $progress,
            'status_counts' => $statusCounts,
            'view_content' => 'adminpanel/project_show'
        ]);
    }

    public function resourceManagement() {
        $this->renderPlaceholder('Resource Management', 'adminpanel/resource_management');
    }

    public function roleManagement() {
        $this->renderPlaceholder('Role Management', 'adminpanel/role_management');
    }

    public function revenueManagement() {
        $this->renderPlaceholder('Revenue Management', 'adminpanel/revenue_management');
    }

    public function analyticsReporting() {
        $this->renderPlaceholder('Analytics & Reporting', 'adminpanel/analytics_reporting');
    }

    public function riskManagement() {
        $this->renderPlaceholder('Risk Management', 'adminpanel/risk_management');
    }

    private function renderPlaceholder($title, $viewContent) {
        $this->view('layouts/main', [
            'title' => $title,
            'view_content' => $viewContent
        ]);
    }
}
