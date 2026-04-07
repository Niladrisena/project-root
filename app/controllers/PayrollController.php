<?php
class PayrollController extends Controller {
    
    public function __construct() {
        AuthMiddleware::handle();
    }

    public function index() {
        // SECURITY: Both HR (3) and Finance (6) should be able to view payroll
        $role_id = (int) Session::get('role_id');
        if (!in_array($role_id, [1, 3, 6])) {
            header("HTTP/1.1 403 Forbidden");
            $this->view('layouts/main', ['view_content' => 'errors/403', 'title' => 'Access Denied']);
            exit;
        }

        $payrollModel = $this->model('Payroll');
        
        $roster = $payrollModel->getPayrollRoster();
        $metrics = $payrollModel->getPayrollMetrics($roster);

        $this->view('layouts/main', [
            'view_content' => 'payroll/index',
            'roster' => $roster,
            'metrics' => $metrics,
            'title' => 'Enterprise Payroll Run'
        ]);
    }
}