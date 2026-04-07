<?php
class AdminpanelController extends Controller {
    public function __construct() {
        AuthMiddleware::handle();

        if (!Auth::role('owner') && !Auth::role('admin')) {
            http_response_code(403);
            exit('403 - Forbidden');
        }
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
