<?php
class PerformanceController extends Controller {
    
    public function __construct() {
        AuthMiddleware::handle();
    }

    // ELITE HELPER: Reusable Security Clearance
    private function verifyClearance() {
        $role_id = (int) Session::get('role_id');
        if (!in_array($role_id, [1, 3, 4])) {
            header("HTTP/1.1 403 Forbidden");
            $this->view('layouts/main', ['view_content' => 'errors/403', 'title' => 'Access Denied']);
            exit;
        }
    }

    public function index() {
        $this->verifyClearance();
        $performanceModel = $this->model('Performance');
        
        $reviews = $performanceModel->getAllReviews();
        $companyAverage = $performanceModel->getCompanyAverage();

        $this->view('layouts/main', [
            'view_content' => 'performance/index',
            'reviews' => $reviews,
            'companyAverage' => $companyAverage,
            'title' => 'Performance & Appraisals'
        ]);
    }

    public function create() {
        $this->verifyClearance();
        $performanceModel = $this->model('Performance');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            csrf_verify($_POST['csrf_token'] ?? '');

            // Data extraction & sanitization
            $data = [
                'employee_id' => $_POST['employee_id'],
                'reviewer_id' => Session::get('user_id'), // The person currently logged in
                'review_period' => sanitize($_POST['review_period']),
                'rating' => (float) $_POST['rating'],
                'comments' => sanitize($_POST['comments']),
                'status' => $_POST['action'] === 'submit' ? 'submitted' : 'draft'
            ];

            if ($performanceModel->createReview($data)) {
                Session::set('flash_success', 'Performance appraisal successfully recorded.');
                $this->redirect('/performance');
            } else {
                Session::set('flash_error', 'Database Error: Could not save the appraisal.');
            }
        }

        // Fetch employees for the form dropdown
        $employees = $performanceModel->getEligibleEmployees();

        $this->view('layouts/main', [
            'view_content' => 'performance/create',
            'employees' => $employees,
            'title' => 'New Performance Appraisal'
        ]);
    }
}