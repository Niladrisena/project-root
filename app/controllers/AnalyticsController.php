<?php
class AnalyticsController extends Controller {
    private $analyticsModel;
    private $automationModel;

    public function __construct() {
        AuthMiddleware::handle();
        $this->analyticsModel = $this->model('Analytics');
        $this->automationModel = $this->model('Automation');
    }

    public function dashboard() {
        PermissionMiddleware::handle('company_dashboard', 'analytics'); // CEO/Owner level access
        
        $stats = $this->analyticsModel->getCEODashboardStats();
        
        // Fetch recent system alerts
        $this->analyticsModel->db->query("SELECT * FROM system_alerts WHERE is_read = 0 ORDER BY created_at DESC LIMIT 5");
        $alerts = $this->analyticsModel->db->fetchAll();

        $this->view('layouts/main', [
            'view_content' => 'analytics/dashboard',
            'stats' => $stats,
            'alerts' => $alerts,
            'title' => 'CEO Intelligence Dashboard'
        ]);
    }

    public function chart_financial_growth() {
        PermissionMiddleware::handle('company_dashboard', 'analytics');
        $data = $this->analyticsModel->getFinancialGrowthChart();
        $this->jsonResponse($data);
    }
}